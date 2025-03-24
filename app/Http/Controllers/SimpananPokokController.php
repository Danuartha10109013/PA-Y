<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Simpanan;
use App\Models\SimpananPokok;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SimpananPokokController extends Controller
{
    public function simpananpokok()
    {
        $data = [
            'title' => 'Simpanan Pokok',
            'simpananPokok' => SimpananPokok::where('anggota_id', auth()->user()->anggota_id)->first(), // Ambil data berdasarkan anggota_id dari user login
        ];

        return view('pages.anggota.simpanan.pokok.index', $data);
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'email' => 'required|email',
            'jenis_simpanan' => 'required|in:simpanan_pokok',
            'amount' => 'required|numeric|min:10000',
            'payment_method' => 'required|string',
        ]);

        Simpanan::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'jenis_simpanan' => $request->jenis_simpanan,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'status' => 'pending', // Default status
        ]);

        return redirect()->back()
            ->with('success', 'Pengajuan Simpanan Pokok berhasil diajukan. Harap lakukan pembayaran sebelum 1x24 jam.');
    }

    public function hasilSimpanan()
    {
        // Ambil data simpanan berdasarkan pengguna yang sedang login
        $simpanan = SimpananPokok::where('anggota_id', auth()->user()->anggota_id)->first();



        // Kirim data ke view
        return view('pages.anggota.simpanan.pokok.hasil_simpanan', [
            'no_simpanan' => $simpanan->no_simpanan_pokok,
            'nominal' => $simpanan->nominal,
            'bank' => $simpanan->bank,
            'virtual_account' => $simpanan->virtual_account,
            'expired_at' => $simpanan->expired,
        ]);
    }



    public function requestVirtualAccountToDoku(Request $request)
    {
        try {
            // Log request data
            Log::info('Memulai proses pembuatan virtual account untuk simpanan pokok', ['request' => $request->all()]);

            // Validasi input
            $request->validate([
                'bank' => 'required|string',
            ]);

            // Ambil data simpanan pokok pengguna yang sedang login
            $simpananPokok = SimpananPokok::where('anggota_id', auth()->user()->anggota_id)->first();


            if (!$simpananPokok) {
                Log::warning('Simpanan pokok tidak ditemukan untuk user', ['user_id' => auth()->id()]);
                throw new \Exception('Simpanan pokok tidak ditemukan.');
            }

            // Perbarui kolom bank di tabel simpanan pokok
            $simpananPokok->update([
                'bank' => strtoupper(trim($request->bank)), // Update kolom bank
            ]);

            Log::info('Kolom bank pada simpanan pokok berhasil diperbarui', [
                'simpanan_id' => $simpananPokok->id,
                'bank' => $simpananPokok->bank,
            ]);

            // Step 1: Get Access Token
            $clientId = config('app.doku_client_key');

            $privateKey = str_replace("\\n", "\n", config('app.doku_private_key'));
            // Ambil dari konfigurasi Laravel
            Log::info('X-CLIENT-KEY:', ['key' => $clientId]);

            if (!$privateKey) {
                throw new Exception('Private key not found in .env');
            }

            // Load private key
            $privateKeyResource = openssl_pkey_get_private($privateKey);

            if (!$privateKeyResource) {
                throw new Exception('Invalid private key: ' . openssl_error_string());
            }

            Log::info('Private key successfully loaded', [$privateKey]);


            $timestamp = gmdate("Y-m-d\TH:i:s\Z");
            $stringToSign = $clientId . "|" . $timestamp;

            $privateKeyResource = openssl_pkey_get_private($privateKey);
            openssl_sign($stringToSign, $signature, $privateKeyResource, OPENSSL_ALGO_SHA256);
            $xSignature = base64_encode($signature);

            $headers = [
                'X-SIGNATURE: ' . $xSignature,
                'X-TIMESTAMP: ' . $timestamp,
                'X-CLIENT-KEY: ' . $clientId,
                'Content-Type: application/json',
            ];

            $body = [
                "grantType" => "client_credentials",
                "additionalInfo" => ""
            ];

            $url = "https://api-sandbox.doku.com/authorization/v1/access-token/b2b";

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));

            $response = curl_exec($ch);
            curl_close($ch);

            $decodedResponse = json_decode($response, true);

            // Log Access Token Response
            Log::info("Access Token Response from DOKU: ", $decodedResponse);

            if (!isset($decodedResponse['accessToken'])) {
                throw new \Exception('Failed to get access token from DOKU');
            }

            $accessToken = $decodedResponse['accessToken'];

            // Step 2: Create Virtual Account
            $httpMethod = "POST";
            $partnerId = config('app.doku_patner_id');
            $channelId = 'H2H';
            $externalId = uniqid();
            $timestamp = now()->format('Y-m-d\TH:i:sP');

            $endpointUrl = "/virtual-accounts/bi-snap-va/v1.1/transfer-va/create-va";
            $fullUrl = 'https://api-sandbox.doku.com' . $endpointUrl;

            $bank = strtoupper(trim($simpananPokok->bank));
            $customerNumber = $this->getCustomerNumber($simpananPokok->bank);

            $channelBank = $this->mapBankToChannel($bank);
            if (!$channelBank) {
                throw new \Exception('Invalid bank name for channel format');
            }

            if ($simpananPokok->nominal <= 0) {
                throw new \Exception('Invalid nominal amount. Must be greater than zero.');
            }

            $totalAmountValue = number_format((float)$simpananPokok->nominal, 2, '.', '');
            $partnerServiceId = str_pad($this->getBankCode($bank), 8, " ", STR_PAD_LEFT);
            $trxId = uniqid();
            $expiredDate = now()->addDays(1)->format('Y-m-d\TH:i:sP');

            $body = [
                'partnerServiceId' => $partnerServiceId,
                'customerNo' => $customerNumber,
                'virtualAccountNo' => $partnerServiceId . $customerNumber,
                "virtualAccountName" => auth()->user()->name,
                "virtualAccountEmail" => auth()->user()->email,
                "virtualAccountPhone" => auth()->user()->phone,
                'trxId' => $trxId,
                'virtualAccountTrxType' => 'C',
                "totalAmount" => [
                    "value" => $totalAmountValue,
                    "currency" => "IDR"
                ],
                'expiredDate' => $expiredDate,
                'additionalInfo' => [
                    'channel' => "VIRTUAL_ACCOUNT_" . $channelBank,
                ]
            ];

            $requestBody = json_encode($body, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            Log::info("Request Body to DOKU: " . json_encode($body, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            $hashedBody = hash('sha256', $requestBody);

            $stringToSign = $httpMethod . ":" . $endpointUrl . ":" . $accessToken . ":" . strtolower($hashedBody) . ":" . $timestamp;
            $clientSecret = config('app.doku_secret_key');
            $signature = base64_encode(hash_hmac('sha512', $stringToSign, $clientSecret, true));

            $headers = [
                'Authorization: Bearer ' . $accessToken,
                'X-TIMESTAMP: ' . $timestamp,
                'X-SIGNATURE: ' . $signature,
                'X-PARTNER-ID: ' . $partnerId,
                'X-EXTERNAL-ID: ' . $externalId,
                'CHANNEL-ID: ' . $channelId,
                'Content-Type: application/json',
            ];

            $ch = curl_init($fullUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $requestBody);

            $response = curl_exec($ch);
            curl_close($ch);

            $decodedResponse = json_decode($response, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error("Invalid JSON response from DOKU: " . $response);
                throw new \Exception("Invalid JSON response from DOKU");
            }

            Log::info("Create Virtual Account Response from DOKU: ", $decodedResponse);

            if (isset($decodedResponse['virtualAccountData']['virtualAccountNo'], $decodedResponse['virtualAccountData']['expiredDate'])) {
                $simpananPokok->update([
                    'virtual_account' => $decodedResponse['virtualAccountData']['virtualAccountNo'],
                    'expired' => $decodedResponse['virtualAccountData']['expiredDate'],

                ]);

                Log::info("Simpanan pokok berhasil diperbarui dengan virtual account.", [
                    'simpanan_id' => $simpananPokok->id,
                    'virtual_account' => $decodedResponse['virtualAccountData']['virtualAccountNo'],
                    'expired' => $decodedResponse['virtualAccountData']['expiredDate'],
                ]);

                return $decodedResponse['virtualAccountData']['virtualAccountNo'];
            }

            $errorMessage = $decodedResponse['responseMessage'] ?? 'Unknown error from DOKU';
            $errorCode = $decodedResponse['responseCode'] ?? 'No code provided';

            Log::error('Failed to receive valid virtual account data from DOKU', [
                'response_code' => $errorCode,
                'response_message' => $errorMessage,
            ]);

            throw new \Exception("DOKU Error: $errorMessage (Code: $errorCode)");
        } catch (\Exception $e) {
            Log::error("Failed to process virtual account: " . $e->getMessage());
            throw new \Exception("Failed to process virtual account: " . $e->getMessage());
        }
    }


    // Helper function to map bank to channel
    private function mapBankToChannel($bank)
    {
        $mapping = [
            'MANDIRI' => 'BANK_MANDIRI',
            'BRI' => 'BRI',
            'BNI' => 'BNI',
            'BCA' => 'BCA',
        ];

        return $mapping[$bank] ?? null;
    }





    protected $bankCodes = [
        'BNI' => '8492',
        'BRI' => '13925',
        'BCA' => '19008',
        'MANDIRI' => '88899',
        // Tambahkan mapping bank lainnya di sini
    ];



    protected $customerNumbers = [
        'BNI' => '3',
        'BRI' => '6',
        'BCA' => '0',
        'MANDIRI' => '4',
        // Tambahkan mapping bank lainnya di sini
    ];




    protected function getCustomerNumber($bankName)
    {
        $customerNumbers = $this->customerNumbers;

        // Ubah nama bank menjadi uppercase untuk konsistensi
        $bankName = strtoupper($bankName);

        // Kembalikan nomor customer atau default jika tidak ditemukan
        return $customerNumbers[$bankName] ?? null; // `null` jika bank tidak ditemukan
    }




    protected function getBankCode($bankName)
    {
        $bankCodes = $this->bankCodes;

        // Ubah nama bank menjadi uppercase untuk konsistensi
        $bankName = strtoupper($bankName);

        // Kembalikan kode bank atau default jika bank tidak ditemukan
        return $bankCodes[$bankName] ?? null; // `null` jika bank tidak ditemukan
    }
}
