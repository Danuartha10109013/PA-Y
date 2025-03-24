<?php

namespace App\Http\Controllers;

use App\Mail\ApprovalNotificationMail;
use App\Models\User;
use App\Models\Anggota;
use App\Mail\Mailkonfir;
use App\Mail\RejectNotification;
use App\Models\PenarikanBerjangka;
use App\Models\PenarikanSukarela;
use App\Models\Simpanan;
use App\Models\PengajuanPinjaman;
use App\Models\RekeningSimpananBerjangka;
use App\Models\RekeningSimpananSukarela;
use App\Models\SimpananBerjangka;
use App\Models\SimpananSukarela;
use App\Models\SimpananWajib;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class KetuaController extends Controller
{
    public function indexsimpanansukarela()
    {
        $simpananSukarelas = SimpananSukarela::whereHas('rekeningSimpananSukarela', function ($query) {
            $query->whereIn('approval_bendahara', ['approved', 'rejected']);
        })->with(['rekeningSimpananSukarela', 'user'])->get();
        Log::info('Data Simpanan Sukarela:', ['data' => $simpananSukarelas]);
        return view('pages.ketua.simpanan.index', [
            'title' => 'Data Pengajuan Simpanan Sukarela',
            'simpananSukarelas' => $simpananSukarelas,
        ]);
    }

        public function dataAnggota()
    {
        $anggota = Anggota::orderBy('created_at', 'desc')->get();

        return view('pages.ketua.data_anggota_ketua', [
            'title' => 'Data Approve Registrasi',
            'anggota' => $anggota

        ]);
    }


    public function indexsimpananberjangka()
    {
        $simpananBerjangkas = SimpananBerjangka::whereHas('rekeningSimpananBerjangka', function ($query) {
            $query->whereIn('approval_bendahara', ['approved', 'rejected']);
        })->with(['rekeningSimpananBerjangka', 'user'])->get();
        Log::info('Data Simpanan Berjangka:', ['data' => $simpananBerjangkas]);
        return view('pages.ketua.simpanan.index2', [
            'title' => 'Data Pengajuan Simpanan Berjangka',
            'simpananBerjangkas' => $simpananBerjangkas,
        ]);
    }


    


    public function diterima($id, $status)
    {
        try {
            // Temukan anggota berdasarkan ID
            $anggota = Anggota::findOrFail($id);

            // Update status anggota
            $anggota->status_ketua = $status;
            $anggota->status = $status;

            // Update status_pemabayaran dan tanggal_pembayaran di tabel anggota jika metode_pembayaran adalah 'Potong Gaji Otomatis'
            if ($anggota->metode_pembayaran === 'Potong Gaji Otomatis') {
                $anggota->status_pembayaran = 'Sukses';
                $anggota->tanggal_pembayaran = now(); // Tanggal pembayaran diatur ke hari ini
            }

            $anggota->save(); // Simpan pembaruan status anggota

            SimpananWajib::where('anggota_id', $anggota->id)
                ->update([
                    'status_pembayaran' => 'Sukses',
                    'tanggal_pembayaran' => now(),
                ]);


            // Jika status_ketua adalah 'Diterima', kirim email konfirmasi
            if ($anggota->status_ketua === 'Diterima') {
                $email = $anggota->email_kantor; // Ambil email dari objek anggota
                $user = User::where('email', $email)->first();

                if (!$user) {
                    // Buat password acak
                    $random_pass = rand(111111, 999999);

                    // Simpan user baru ke tabel `users`
                    $user = User::create([
                        'name' => $anggota->nama,
                        'email' => $email,
                        'password' => Hash::make($random_pass),
                        'role' => 'anggota', // Set 'role' sesuai kebutuhan
                        'anggota_id' => $anggota->id,
                    ]);

                    // Kirim email konfirmasi kepada anggota yang berisi username dan password baru
                    Mail::to($email)->send(new Mailkonfir($email, $random_pass)); // Kirim email dengan username dan password baru

                    return response()->json(['message' => 'Status berhasil diperbarui, akun berhasil dibuat, dan email berhasil dikirim!'], 200);
                } else {
                    return response()->json(['message' => 'Akun sudah ada!'], 400);
                }
            } else {
                return response()->json(['message' => 'Status bukan Diterima!'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal memperbarui status!', 'error' => $e->getMessage()], 500);
        }
    }

    public function ditolak($id, $status)
    {
        try {
            // Temukan anggota berdasarkan ID
            $anggota = Anggota::findOrFail($id);

            // Update status anggota
            $anggota->status_ketua = $status;
            $anggota->status = $status;
            $anggota->status_pembayaran = 'Gagal';
            $anggota->tanggal_pembayaran = now(); // Tanggal pembayaran diatur ke hari ini
            $anggota->save();

            return response()->json(['message' => 'Status updated successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update status!', 'error' => $e->getMessage()], 500);
        }
    }



    public function updateApprovalKetuaSimpananSukarela($id, $status)
    {
        try {
            // Validasi status
            if (!in_array($status, ['approved', 'rejected', 'pending'])) {
                return response()->json(['message' => 'Invalid status provided!'], 400);
            }

            // Temukan data berdasarkan ID
            $rekening = RekeningSimpananSukarela::findOrFail($id);

            // Update status approval ketua
            $rekening->approval_ketua = $status;
            $rekening->status = $status;
            $rekening->save();

            // Jika status disetujui oleh ketua
            if ($status === 'approved') {
                // Cek data Simpanan Sukarela terkait
                $simpanan = SimpananSukarela::where('rekening_simpanan_sukarela_id', $rekening->id)->first();



                if (!$simpanan) {
                    return response()->json([
                        'message' => 'Simpanan Sukarela not found for this rekening!',
                    ], 404);
                }





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
                openssl_sign($stringToSign, $signature, $privateKeyResource, OPENSSL_ALGO_SHA256);
                $xSignature = base64_encode($signature);

                $headers = [
                    'X-SIGNATURE: ' . $xSignature,
                    'X-TIMESTAMP: ' . $timestamp,
                    'X-CLIENT-KEY: ' . $clientId,
                    'Content-Type: application/json',
                ];

                Log::info("Access Token Response from DOKU: ", $headers);

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
                $fullUrll = 'https://api-sandbox.doku.com/virtual-accounts/bi-snap-va/v1.1/transfer-va/create-va';
                Log::info("Full URL: " . $fullUrll); // Log URL lengkap


                $bankCode = $this->getBankCode($simpanan->bank);
                $customerNumber = $this->getCustomerNumber($simpanan->bank);


                // Proses nama bank
                Log::info("Original Bank Name from Database: " . $simpanan->bank);

                $bank = strtoupper(trim($simpanan->bank)); // Ubah semua huruf menjadi huruf besar dan hilangkan spasi

                Log::info("Trimmed and Uppercase Bank Name: " . $bank);

                // Periksa jika bank adalah Mandiri
                if ($bank === 'MANDIRI') {
                    $channelBank = 'BANK_MANDIRI';
                } else {
                    $channelBank = $bank; // Bank lain tetap sama
                }

                Log::info("Channel Bank: " . $channelBank);

                // Validasi jika bank tidak ditemukan
                if (empty($channelBank)) {
                    throw new \Exception('Invalid bank name for channel format');
                }

                // Validasi nominal
                if ($simpanan->nominal <= 0) {
                    Log::error("Invalid nominal value: " . $simpanan->nominal);
                    throw new \Exception('Invalid nominal amount. Must be greater than zero.');
                }

                // Format nominal menjadi desimal dengan dua digit
                $totalAmountValue = number_format((float) $simpanan->nominal, 2, '.', '');

                Log::info("Formatted totalAmount.value: " . $totalAmountValue);

                $partnerServiceId = str_pad($bankCode, 8, " ", STR_PAD_LEFT);
                $trxId = uniqid();
                $expiredDate = now()->addDays(1)->format('Y-m-d\TH:i:sP');

                $body = [
                    'partnerServiceId' => $partnerServiceId,
                    'customerNo' => $customerNumber,
                    'virtualAccountNo' => $partnerServiceId . $customerNumber,
                    "virtualAccountName" => $simpanan->user->name,
                    "virtualAccountEmail" => $simpanan->user->email,
                    "virtualAccountPhone" => $simpanan->user->phone,
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
                $hashedBody = hash('sha256', $requestBody);
                // Log request body
                Log::info("Request Body to DOKU: " . json_encode($body, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

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

                $ch = curl_init($fullUrll);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $requestBody);

                $response = curl_exec($ch);
                curl_close($ch);

                $decodedResponse = json_decode($response, true);

                // Log Create Virtual Account Response
                Log::info("Create Virtual Account Response from DOKU: ", $decodedResponse);

                if (!isset($decodedResponse['virtualAccountData']['virtualAccountNo'], $decodedResponse['virtualAccountData']['expiredDate'])) {
                    Log::error("Invalid virtual account data: ", $decodedResponse);
                    throw new \Exception('Failed to receive valid virtual account data from DOKU');
                }
                // Ambil data virtual account
                $virtualAccountData = $decodedResponse['virtualAccountData'];

                Log::info("Virtual Account Data: ", $virtualAccountData);

                // Simpan data Virtual Account jika respon valid
                $simpanan->virtual_account = $virtualAccountData['virtualAccountNo'];
                $simpanan->expired_at = $virtualAccountData['expiredDate'];
                $simpanan->status_payment = 'Menunggu Pembayaran';
            }
            $simpanan->save();


            // Ambil email pengguna berdasarkan user_id
            $user = User::find($simpanan->user_id);

            if (!$user || !$user->email) {
                return response()->json(['message' => 'User or email not found for this simpanan!'], 404);
            }

            // Kirim email notifikasi
            Mail::to($user->email)->send(new ApprovalNotificationMail($user, $simpanan));

            Log::info('Email notifikasi persetujuan dikirim ke:', ['email' => $user->email]);




            return response()->json(['message' => 'Approval Ketua status updated and virtual account created successfully!'], 200);
        } catch (\Exception $e) {
            Log::error("Failed to process: " . $e->getMessage());
            return response()->json(['message' => 'Failed to update status or create virtual account!', 'error' => $e->getMessage()], 500);
        }
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






    public function updateApprovalKetuaSimpananBerjangka($id, $status)
    {
        try {
            // Validasi status
            if (!in_array($status, ['approved', 'rejected', 'pending'])) {
                return response()->json(['message' => 'Invalid status provided!'], 400);
            }

            // Temukan data berdasarkan ID
            $rekening = RekeningSimpananBerjangka::findOrFail($id);

            // Update status approval ketua
            $rekening->approval_ketua = $status;
            $rekening->status = $status;
            $rekening->save();

            // Jika status disetujui oleh ketua
            if ($status === 'approved') {
                // Cek data Simpanan Sukarela terkait
                $simpanan = SimpananBerjangka::where('rekening_simpanan_berjangka_id', $rekening->id)->first();



                if (!$simpanan) {
                    return response()->json([
                        'message' => 'Simpanan Sukarela not found for this rekening!',
                    ], 404);
                }





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

                Log::info("Access Token Response from DOKU: ", $headers);

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
                $fullUrll = 'https://api-sandbox.doku.com/virtual-accounts/bi-snap-va/v1.1/transfer-va/create-va';
                Log::info("Full URL: " . $fullUrll); // Log URL lengkap


                $bankCode = $this->getBankCode($simpanan->bank);
                $customerNumber = $this->getCustomerNumber($simpanan->bank);


                // Proses nama bank
                Log::info("Original Bank Name from Database: " . $simpanan->bank);

                $bank = strtoupper(trim($simpanan->bank)); // Ubah semua huruf menjadi huruf besar dan hilangkan spasi

                Log::info("Trimmed and Uppercase Bank Name: " . $bank);

                // Periksa jika bank adalah Mandiri
                if ($bank === 'BANK MANDIRI') {
                    $channelBank = 'BANK_MANDIRI';
                } else {
                    $channelBank = $bank; // Bank lain tetap sama
                }

                Log::info("Channel Bank: " . $channelBank);

                // Validasi jika bank tidak ditemukan
                if (empty($channelBank)) {
                    throw new \Exception('Invalid bank name for channel format');
                }

                // Validasi nominal
                if ($simpanan->nominal <= 0) {
                    Log::error("Invalid nominal value: " . $simpanan->nominal);
                    throw new \Exception('Invalid nominal amount. Must be greater than zero.');
                }

                // Format nominal menjadi desimal dengan dua digit
                $totalAmountValue = number_format((float) $simpanan->nominal, 2, '.', '');

                Log::info("Formatted totalAmount.value: " . $totalAmountValue);

                $partnerServiceId = str_pad($bankCode, 8, " ", STR_PAD_LEFT);
                $trxId = uniqid();
                $expiredDate = now()->addDays(1)->format('Y-m-d\TH:i:sP');

                $body = [
                    'partnerServiceId' => $partnerServiceId,
                    'customerNo' => $customerNumber,
                    'virtualAccountNo' => $partnerServiceId . $customerNumber,
                    "virtualAccountName" => $simpanan->user->name,
                    "virtualAccountEmail" => $simpanan->user->email,
                    "virtualAccountPhone" => $simpanan->user->phone,
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
                $hashedBody = hash('sha256', $requestBody);
                // Log request body
                Log::info("Request Body to DOKU: " . json_encode($body, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

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

                $ch = curl_init($fullUrll);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $requestBody);

                $response = curl_exec($ch);
                curl_close($ch);

                $decodedResponse = json_decode($response, true);

                // Log Create Virtual Account Response
                Log::info("Create Virtual Account Response from DOKU: ", $decodedResponse);

                if (!isset($decodedResponse['virtualAccountData']['virtualAccountNo'], $decodedResponse['virtualAccountData']['expiredDate'])) {
                    Log::error("Invalid virtual account data: ", $decodedResponse);
                    throw new \Exception('Failed to receive valid virtual account data from DOKU');
                }
                // Ambil data virtual account
                $virtualAccountData = $decodedResponse['virtualAccountData'];

                Log::info("Virtual Account Data: ", $virtualAccountData);

                // Simpan data Virtual Account jika respon valid
                $simpanan->virtual_account = $virtualAccountData['virtualAccountNo'];
                $simpanan->expired_at = $virtualAccountData['expiredDate'];
                $simpanan->status_payment = 'Menunggu Pembayaran';
            }
            $simpanan->save();


            // Ambil email pengguna berdasarkan user_id
            $user = User::find($simpanan->user_id);

            if (!$user || !$user->email) {
                return response()->json(['message' => 'User or email not found for this simpanan!'], 404);
            }

            // Kirim email notifikasi
            Mail::to($user->email)->send(new ApprovalNotificationMail($user, $simpanan));

            Log::info('Email notifikasi persetujuan dikirim ke:', ['email' => $user->email]);




            return response()->json(['message' => 'Approval Ketua status updated and virtual account created successfully!'], 200);
        } catch (\Exception $e) {
            Log::error("Failed to process: " . $e->getMessage());
            return response()->json(['message' => 'Failed to update status or create virtual account!', 'error' => $e->getMessage()], 500);
        }
    }




    protected $bankCodess = [
        'Bank BNI' => '8492',
        'Bank BRI' => '13925',
        'Bank BCA' => '19008',
        'Bank MANDIRI' => '88899',
        // Tambahkan mapping bank lainnya di sini
    ];



    protected $customerNumberss = [
        'Bank BNI' => '3',
        'Bank BRI' => '6',
        'Bank BCA' => '0',
        'Bank MANDIRI' => '4',
        // Tambahkan mapping bank lainnya di sini
    ];




    protected function getCustomerNumbers($bankName)
    {
        $customerNumberss = $this->customerNumberss;

        // Ubah nama bank menjadi uppercase untuk konsistensi
        $bankName = strtoupper($bankName);

        // Kembalikan nomor customer atau default jika tidak ditemukan
        return $customerNumberss[$bankName] ?? null; // `null` jika bank tidak ditemukan
    }




    protected function getBankCodes($bankName)
    {
        $bankCodess = $this->bankCodess;

        // Ubah nama bank menjadi uppercase untuk konsistensi
        $bankName = strtoupper($bankName);

        // Kembalikan kode bank atau default jika bank tidak ditemukan
        return $bankCodess[$bankName] ?? null; // `null` jika bank tidak ditemukan
    }
    public function updateStatusKetua($id, $status)
    {
        try {
            // Temukan anggota berdasarkan ID
            $anggota = Anggota::findOrFail($id);

            // Update status dari ketua
            $anggota->status_ketua = $status;
            $anggota->save();

            // Simpan perubahan ke database
            $anggota->save();
            // Cek status keseluruhan
            $this->FinalStatus($anggota);

            return response()->json(['message' => 'Status ketua updated successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update status!', 'error' => $e->getMessage()], 500);
        }
    }

    public function FinalStatus($anggota)
    {

        $anggota->status_ketua = 'Diterima';
        // Logika untuk menentukan status final berdasarkan status manager dan ketua
        if ($anggota) {
            $email = $anggota->email_kantor; // Retrieve the email
            $nama = $anggota->nama;
            Mail::to($email)->send(new Mailkonfir($email)); // Assuming the email class is named Mailkonfir
        } else {
            // Handle the case where no email is found
            return back()->with('error', 'Anggota not found or does not have an email address.');
        }

        // Simpan perubahan ke database
        $anggota->save();
    }

    // Fungsi lainnya tetap sama

    public function email($id)
    {
        $email = Anggota::where('id', $id)->first()->email_kantor;

        // Mail::to($email)->send(new information_registrasi($email));
    }
    public function homeketua()
    {
        return view('pages.ketua.home_ketua', [
            'title' => 'Dashboard Ketua',
        ]);
    }


    public function detail_regis()
    {
        return view('pages.admin.detail_laporanregis', [
            'title' => 'Data Anggota Registrasi',
            'anggota' => Anggota::all()
        ]);
    }


    public function updateStatusPinjamanKetua($id, $status)
    {
        try {
            // Temukan anggota berdasarkan ID
            $anggota = PengajuanPinjaman::findOrFail($id);
            // Update status anggota berdasarkan parameter $status
            $anggota->status_ketua = $status;
            $anggota->save();

            return response()->json(['message' => 'Status updated successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update status!', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateStatusSimpanan($type, $id, $status)
    {
        try {
            // Pilih model berdasarkan tipe simpanan
            $models = [
                'sukarela' => SimpananSukarela::class,
                'berjangka' => SimpananBerjangka::class,
            ];

            // Validasi tipe simpanan
            if (!array_key_exists($type, $models)) {
                return response()->json(['message' => 'Invalid savings type!'], 400);
            }

            $model = $models[$type];

            // Cari data simpanan berdasarkan ID
            $simpanan = $model::findOrFail($id);

            // Update status simpanan
            $simpanan->status = $status;
            $simpanan->save();

            return response()->json(['message' => 'Status simpanan updated successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update status!', 'error' => $e->getMessage()], 500);
        }
    }


    public function countData($status)
    {
        if ($status == 'all') {
            // Total semua data
            $count = Anggota::count();
        } elseif ($status == 'diterima') {
            // Data yang diterima oleh ketua atau manager
            $count = Anggota::where(function ($query) {
                $query->where('status_ketua', 'Diterima')
                    ->orWhere('status_manager', 'Diterima');
            })->count();
        } elseif ($status == 'pengajuan') {
            // Data yang masih dalam proses (belum diterima/ditolak oleh ketua atau manager)
            $count = Anggota::whereNull('status_ketua')
                ->orWhereNull('status_manager')
                ->where(function ($query) {
                    $query->where('status_ketua', '!=', 'Diterima')
                        ->where('status_ketua', '!=', 'Ditolak')
                        ->orWhere('status_manager', '!=', 'Diterima');
                })->count();
        } elseif ($status == 'ditolak') {
            // Data yang ditolak oleh ketua atau manager
            $count = Anggota::where(function ($query) {
                $query->where('status_ketua', 'Ditolak');
            })->count();
        }

        // Kembalikan hasil dalam format JSON
        return response()->json(['count' => $count]);
    }

    public function indexForApproval()
    {
        // Mengambil data simpanan yang statusnya 'Menunggu' untuk ditampilkan ke ketua
        $simpanans = Simpanan::where('status', 'Menunggu')->get();

        return view('ketua.simpanan.approve_simpanan', compact('simpanans'));
    }


    public function countDataRekeningSimpananSukarela($status)
    {
        if ($status == 'all') {
            // Total semua data
            $count = RekeningSimpananSukarela::count();
        } elseif ($status == 'diterima') {
            // Data yang diterima oleh ketua, manager, atau bendahara
            $count = RekeningSimpananSukarela::where(function ($query) {
                $query->where('approval_ketua', 'approved')
                    ->orWhere('approval_manager', 'approved')
                    ->orWhere('approval_bendahara', 'approved');
            })->count();
        } elseif ($status == 'pengajuan') {
            // Data yang masih dalam proses (belum diterima/ditolak oleh ketua, manager, atau bendahara)
            $count = RekeningSimpananSukarela::where('approval_ketua', 'pending')
                ->orWhere('approval_manager', 'pending')
                ->orWhere('approval_bendahara', 'pending')
                ->count();
        } elseif ($status == 'ditolak') {
            // Data yang ditolak oleh ketua, manager, atau bendahara
            $count = RekeningSimpananSukarela::where(function ($query) {
                $query->where('approval_ketua', 'rejected')
                    ->orWhere('approval_manager', 'rejected')
                    ->orWhere('approval_bendahara', 'rejected');
            })->count();
        }

        // Kembalikan hasil dalam format JSON
        return response()->json(['count' => $count]);
    }


    public function countDataRekeningSimpananBerjangka($status)
    {
        if ($status == 'all') {
            // Total semua data
            $count = RekeningSimpananBerjangka::count();
        } elseif ($status == 'diterima') {
            // Data yang diterima oleh ketua, manager, atau bendahara
            $count = RekeningSimpananBerjangka::where(function ($query) {
                $query->where('approval_ketua', 'approved')
                    ->orWhere('approval_manager', 'approved')
                    ->orWhere('approval_bendahara', 'approved');
            })->count();
        } elseif ($status == 'pengajuan') {
            // Data yang masih dalam proses (belum diterima/ditolak oleh ketua, manager, atau bendahara)
            $count = RekeningSimpananBerjangka::where('approval_ketua', 'pending')
                ->orWhere('approval_manager', 'pending')
                ->orWhere('approval_bendahara', 'pending')
                ->count();
        } elseif ($status == 'ditolak') {
            // Data yang ditolak oleh ketua, manager, atau bendahara
            $count = RekeningSimpananBerjangka::where(function ($query) {
                $query->where('approval_ketua', 'rejected')
                    ->orWhere('approval_manager', 'rejected')
                    ->orWhere('approval_bendahara', 'rejected');
            })->count();
        }

        // Kembalikan hasil dalam format JSON
        return response()->json(['count' => $count]);
    }
        public function filter(Request $request)
    {
        // Ambil nilai status dari permintaan
        $status = $request->input('status');

        // Jika status adalah "all", ambil semua data berdasarkan status_bendahara yang tidak null
        if ($status === 'all') {
            $anggota = Anggota::whereNotNull('status_bendahara') // Filter data dengan status_bendahara tidak null
                ->orderBy('created_at', 'desc') // Mengurutkan berdasarkan kolom created_at secara descending
                ->get();
        } else {
            // Filter berdasarkan status_bendahara
            $anggota = Anggota::where('status_bendahara', $status)
                ->orderBy('created_at', 'desc') // Mengurutkan berdasarkan kolom created_at secara descending
                ->get();
        }

        // Kembalikan view partial untuk memperbarui tabel
        return view('pages.bendahara.table_registrasi', compact('anggota'))->render();
    }



    public function delete($id)
{
    try {
        // Cari data berdasarkan ID
        $anggota = Anggota::findOrFail($id);

        // Hapus data di tabel users yang berelasi dengan anggota
        $usersDeleted = User::where('anggota_id', $id)->delete();

        // Hapus data anggota
        $anggota->delete();

        return response()->json([
            'message' => 'Data berhasil dihapus',
            'users_deleted' => $usersDeleted
        ], 200);
    } catch (ModelNotFoundException $e) {
        Log::error("Data dengan ID {$id} tidak ditemukan: " . $e->getMessage()); // Log jika data tidak ditemukan
        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    } catch (\Exception $e) {
        Log::error("Error saat menghapus data dengan ID {$id}: " . $e->getMessage()); // Log jika ada error lain
        return response()->json(['message' => 'Terjadi kesalahan'], 500);
    }
}




    public function search(Request $request)
    {
        $query = $request->input('query');

        // Cari berdasarkan nama, NIK, atau status_manager
        $anggota = Anggota::where('nama', 'LIKE', "%{$query}%")
            ->orWhere('nik', 'LIKE', "%{$query}%")
            ->orWhere('status_bendahara', 'LIKE', "%{$query}%")
            ->get();

        // Kembalikan partial view dengan hasil pencarian
        return view('pages.bendahara.table_registrasi', compact('anggota'))->render();
    }

    public function getDetail($id)
    {
        $anggota = Anggota::findOrFail($id); // Cari anggota berdasarkan ID
        return response()->json($anggota); // Kembalikan data sebagai JSON
    }

    public function update(Request $request, $id)
    {
        $anggota = Anggota::findOrFail($id);

        // Validasi data
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat_domisili' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'nik' => 'required|string|max:255',
            'email_kantor' => 'required|email|max:255',
            'no_handphone' => 'required|string|max:15',
        ]);

        // Perbarui data anggota
        $anggota->update($request->all());

        return response()->json(['message' => 'Data berhasil diperbarui'], 200);
    }


    public function reject(Request $request, $id)
{
    $anggota = Anggota::findOrFail($id);

    // Mengubah status anggota menjadi 'Ditolak'
    $anggota->status_manager = 'Ditolak';
    $anggota->status_bendahara = 'Ditolak';
    $anggota->status = 'Ditolak';
    $anggota->status_ketua = 'Ditolak';
    $anggota->alasan_ditolak = $request->input('alasan_ditolak'); // Tetap bisa menyimpan alasan jika dikirim
    $anggota->save();

    // Mengubah status_pembayaran di tabel simpanan_pokok menjadi 'Gagal'
    $anggota->simpananPokok()->update(['status_pembayaran' => 'Gagal']);

    // Mengubah status_pembayaran di tabel simpanan_wajib menjadi 'Gagal'
    $anggota->simpananWajib()->update(['status_pembayaran' => 'Gagal']);

    // Kirim email penolakan
    Mail::to($anggota->email_kantor)->send(new RejectNotification($anggota));

    return response()->json(['message' => 'Anggota berhasil ditolak, status pembayaran diperbarui menjadi Gagal, dan email pemberitahuan telah dikirim.']);
}

public function countDataRekeningPenarikanSukarela($status)
{
    if ($status == 'all') {
        // Total semua data
        $count = PenarikanSukarela::count();
    } elseif ($status == 'diterima') {
        // Data yang diterima oleh ketua, manager, atau bendahara
        $count = PenarikanSukarela::where(function ($query) {
            $query->where('status_ketua', 'diterima')
                ->orWhere('status_manager', 'diterima')
                ->orWhere('status_bendahara', 'diterima');
        })->count();
    } elseif ($status == 'pengajuan') {
        // Data yang masih dalam proses (belum diterima/ditolak oleh ketua, manager, atau bendahara)
        $count = PenarikanSukarela::where('status_ketua', 'pending')
            ->orWhere('status_manager', 'pending')
            ->orWhere('status_bendahara', 'pending')
            ->count();
    } elseif ($status == 'ditolak') {
        // Data yang ditolak oleh ketua, manager, atau bendahara
        $count = PenarikanSukarela::where(function ($query) {
            $query->where('status_ketua', 'ditolak')
                ->orWhere('status_manager', 'ditolak')
                ->orWhere('status_bendahara', 'ditolak');
        })->count();
    }

    // Kembalikan hasil dalam format JSON
    return response()->json(['count' => $count]);
}


public function countDataRekeningPenarikanBerjangka($status)
{
    if ($status == 'all') {
        // Total semua data
        $count = PenarikanBerjangka::count();
    } elseif ($status == 'diterima') {
        // Data yang diterima oleh ketua, manager, atau bendahara
        $count = PenarikanBerjangka::where(function ($query) {
            $query->where('status_ketua', 'diterima')
                ->orWhere('status_manager', 'diterima')
                ->orWhere('status_bendahara', 'diterima');
        })->count();
    } elseif ($status == 'pengajuan') {
        // Data yang masih dalam proses (belum diterima/ditolak oleh ketua, manager, atau bendahara)
        $count = PenarikanBerjangka::where('status_ketua', 'pending')
            ->orWhere('status_manager', 'pending')
            ->orWhere('status_bendahara', 'pending')
            ->count();
    } elseif ($status == 'ditolak') {
        // Data yang ditolak oleh ketua, manager, atau bendahara
        $count = PenarikanBerjangka::where(function ($query) {
            $query->where('status_ketua', 'ditolak')
                ->orWhere('status_manager', 'ditolak')
                ->orWhere('status_bendahara', 'ditolak');
        })->count();
    }

    // Kembalikan hasil dalam format JSON
    return response()->json(['count' => $count]);
}


public function penarikanSukarelaApproval() {
    // Ambil data dengan kondisi kolom 'bank' tidak sama dengan 'Menunggu OTP'
    // dan 'status_manager' bernilai 'diterima' atau 'ditolak'
    $data = PenarikanSukarela::where('bank', '!=', 'Menunggu OTP')
        ->whereIn('status_bendahara', ['diterima', 'ditolak'])
        ->orderBy('created_at', 'desc')
        ->get();

    // Log data yang akan ditampilkan
    Log::info('Data Approval Penarikan Sukarela:', ['data' => $data]);

    // Return ke view dengan data yang sudah difilter
    return view('pages.ketua.penarikan.penarikan_sukarela_approval', [
        'title' => 'Data Approval Penarikan Sukarela',
        'data' => $data,
    ]);
}



    public function penarikanBerjangkaApproval() {
        $data = PenarikanBerjangka::where('bank', '!=', 'Menunggu OTP')
        ->whereIn('status_bendahara', ['diterima', 'ditolak'])
        ->orderBy('created_at', 'desc')
        ->get();
        Log::info('Data Approval Simpanan Berjangka:', ['data' => $data]);
        return view('pages.ketua.penarikan.penarikan_berjangka_approval', [
            'title' => 'Data Approval Simpanan Berjangka',
            'data' => $data,
        ]);
    }

        public function updateApprovalManagerPenarikanSukarela($id, $status)
    {
        try {
            // Validasi status
            if (!in_array($status, ['diterima', 'ditolak', 'pending'])) {
                return response()->json(['message' => 'Invalid status provided!'], 400);
            }

            // Temukan data berdasarkan ID
            $rekening = PenarikanSukarela::findOrFail($id);

            // Update status approval manager
            $rekening->status_ketua = $status;

            if ($status === 'ditolak') {
                // Jika status ditolak oleh manager, maka status bendahara dan ketua juga menjadi Ditolak
                $rekening->status_manager = 'ditolak';
                $rekening->status_bendahara = 'ditolak';
            }


            $rekening->save();

            return response()->json(['message' => 'Approval Manager status updated successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update Approval Manager status!', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateApprovalManagerPenarikanBerjangka($id, $status)
    {
        try {
            // Validasi status
            if (!in_array($status, ['diterima', 'ditolak', 'pending'])) {
                return response()->json(['message' => 'Invalid status provided!'], 400);
            }

            // Temukan data berdasarkan ID
            $rekening = PenarikanBerjangka::findOrFail($id);

            // Update status approval manager
            $rekening->status_ketua = $status;

            if ($status === 'ditolak') {
            // Jika status ditolak oleh manager, maka status bendahara dan ketua juga menjadi Ditolak
            $rekening->status_manager = 'ditolak';
            $rekening->status_bendahara = 'ditolak';
        }


            $rekening->save();

            return response()->json(['message' => 'Approval Manager status updated successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update Approval Manager status!', 'error' => $e->getMessage()], 500);
        }
    }


}
