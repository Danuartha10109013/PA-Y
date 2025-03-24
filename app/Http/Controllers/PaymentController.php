<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\SimpananBerjangka;
use App\Models\SimpananSukarela;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function showPaymentPage()
    {
        return view('pages.paymentgateway.payment_page', [
            'title' => 'Payment Pembayaran',
        ]);
    }

    public function processPaymentSukarela(Request $request)
    {
        // dd($request->all());
        // Ambil amount dan path dari form
        $amount = $request->input('amount');
        $path = $request->input('path');
        $isApi = $request->input('isApi', false);
        // Hapus "Rp." dan titik (pemisah ribuan) dari input amount
        $amount = str_replace(['Rp. ', '.'], '', $amount);
        $amount = (int) $amount;

        // Generate invoice number
        $invoiceNumber = 'INV-' . date('Ymd') . '-' . Str::random(6);
        $merchantUniqueReference = substr(Str::random(12), 0, 12);

        $users = Auth::user();
        // Simpan transaksi baru ke dalam database
        $transaction = SimpananSukarela::create([
            'user_id' => $users->id,
            'invoice_number' => $invoiceNumber,
            'amount' => $amount,
            'status' => 'pending' // Set status awal menjadi pending
        ]);

        // Panggil sendRequest untuk mendapatkan Virtual Account
        $virtualAccountResponse = $this->sendRequest($amount, $invoiceNumber, $path, $merchantUniqueReference);

        // Cek apakah ada error atau tidak
        if (isset($virtualAccountResponse['error'])) {
            $transaction->update(['status' => 'failed']);
            $virtualAccount = $virtualAccountResponse['message'];
        } elseif (isset($virtualAccountResponse['virtual_account_info']['virtual_account_number'])) {
            $transaction->update([
                'virtual_account_number' => $virtualAccountResponse['virtual_account_info']['virtual_account_number'],
                'status' => 'pending'
            ]);

            $virtualAccount = $virtualAccountResponse['virtual_account_info']['virtual_account_number'];
        } else {
            $virtualAccount = 'Unexpected response format from API';
            $transaction->update(['status' => 'failed']);
        }

        if ($isApi) {
            return response()->json([
                'virtualAccount' => $virtualAccount,
                'amount' => $amount,
                'invoice_number' => $invoiceNumber
            ]);
        }

        return redirect()->route('payment.result', ['virtualAccount' => $virtualAccount, 'amount' => $amount]);
    }

    public function sendRequest($amount, $invoiceNumber, $path, $merchantUniqueReference)
    {
        $clientId = "BRN-0202-1728580392112";
        $requestId = (string) Str::uuid();
        $requestDate = now()->setTimezone('UTC')->format('Y-m-d\TH:i:s\Z');
        $secretKey = "SK-RIvFqc5KUswVVfduSTJb";

        $requestBody = [
            'order' => [
                'amount' => $amount,
                'invoice_number' => $invoiceNumber,
            ],
            'virtual_account_info' => [
                'expired_time' => 60,
                'reusable_status' => false,
                'info1' => 'Merchant Demo Store',
            ],
            'customer' => [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
        ];

        if ($path === '/bni-virtual-account/v2/payment-code') {
            $requestBody['virtual_account_info']['merchant_unique_reference'] = $merchantUniqueReference;
        }

        $digestValue = base64_encode(hash('sha256', json_encode($requestBody), true));
        $componentSignature = "Client-Id:" . $clientId . "\n" .
            "Request-Id:" . $requestId . "\n" .
            "Request-Timestamp:" . $requestDate . "\n" .
            "Request-Target:" . $path . "\n" .
            "Digest:" . $digestValue;

        $signature = base64_encode(hash_hmac('sha256', $componentSignature, $secretKey, true));
        $headers = [
            'Client-Id' => $clientId,
            'Request-Id' => $requestId,
            'Request-Timestamp' => $requestDate,
            'Signature' => 'HMACSHA256=' . $signature,
            'Content-Type' => 'application/json',
        ];

        $response = Http::withHeaders($headers)->post('https://api-sandbox.doku.com' . $path, $requestBody);
        // dd($response->json());

        if ($response->successful()) {
            return $response->json();
        } else {
            Log::error('Error fetching Virtual Account: ', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return [
                'error' => 'Error fetching Virtual Account',
                'message' => $response->body(),
            ];
        }
    }

    public function handlePaymentStatus(Request $request)
    {
        Log::info('DOKU Webhook received', ['data' => $request->all()]);

        $invoiceNumber = $request->input('order.invoice_number');
        $status = $request->input('transaction.status');

        Log::info('Invoice and Status received from DOKU', [
            'invoice_number' => $invoiceNumber,
            'status' => $status,
        ]);

        $transaction = SimpananSukarela::where('invoice_number', $invoiceNumber)->first();

        if ($transaction) {
            Log::info('Transaction found in database', ['transaction' => $transaction]);

            $transaction->update(['status' => $status]);
            Log::info('Transaction status updated', ['status' => $status]);
        } else {
            Log::error('Transaction not found for invoice_number', ['invoice_number' => $invoiceNumber]);
        }

        return response()->json(['message' => 'Status updated']);
    }

    public function apiShowPaymentResult($invoice)
    {
        $transaction = SimpananSukarela::where('invoice_number', $invoice)->first();

        if ($transaction && $transaction->status === 'pending' && $transaction->expired_at < now()) {
            $transaction->update(['status' => 'expired']);
        }

        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        return response()->json([
            'invoice_number' => $transaction->invoice_number,
            'amount' => $transaction->amount,
            'virtual_account' => $transaction->virtual_account_number,
            'status' => $transaction->status,
        ]);
    }


    // Tambahan untuk simpanan berjangka

    // Membuat simpanan berjangka baru dan mencatat transaksi
    public function createSimpananBerjangka(Request $request)
    {
        // Ambil data dari request
        $amount = $request->input('amount');
        $jangkaWaktu = $request->input('jangka_waktu');
        $jumlahJasa = $request->input('jumlah_jasa');
        $path = $request->input('bank'); // `bank` diisi dengan `path` langsung
        $isApi = $request->input('isApi', false);

        $expiredTime = now()->addMinutes(5); // Set expiry time to 60 minutes

        // Generate invoice number
        $invoiceNumber = 'INV-' . date('Ymd') . '-' . Str::random(6);
        $merchantUniqueReference = substr(Str::random(12), 0, 12);

        // Simpan data baru ke dalam tabel SimpananBerjangka
        $simpananBerjangka = SimpananBerjangka::create([
            'invoice_number' => $invoiceNumber,
            'amount' => $amount,
            'jangka_waktu' => $jangkaWaktu,
            'jumlah_jasa' => $jumlahJasa,
            'bank' => $path, // Simpan path sebagai bank jika ini yang dimaksud
            'status' => 'pending', // Set status awal menjadi pending
            'expired_at' => $expiredTime,

        ]);

        // Panggil sendRequest untuk mendapatkan Virtual Account
        $virtualAccountResponse = $this->sendRequest($amount, $invoiceNumber, $path, $merchantUniqueReference);

        // Cek apakah ada error atau tidak
        if (isset($virtualAccountResponse['error'])) {
            $simpananBerjangka->update(['status' => 'failed']);
            $virtualAccount = $virtualAccountResponse['message'];
        } elseif (isset($virtualAccountResponse['virtual_account_info']['virtual_account_number'])) {
            $simpananBerjangka->update([
                'virtual_account_number' => $virtualAccountResponse['virtual_account_info']['virtual_account_number'],
                'status' => 'pending'
            ]);

            $virtualAccount = $virtualAccountResponse['virtual_account_info']['virtual_account_number'];
        } else {
            $virtualAccount = 'Unexpected response format from API';
            $simpananBerjangka->update(['status' => 'failed']);
        }

        if ($isApi) {
            return response()->json([
                'virtualAccount' => $virtualAccount,
                'amount' => $amount,
                'invoice_number' => $invoiceNumber,
            ]);
        }

        return response()->json([
            'message' => 'Simpanan berjangka berhasil dibuat',
            'simpanan_berjangka' => $simpananBerjangka,
            'virtualAccount' => $virtualAccount
        ], 201);
    }


    // Mengambil data simpanan berjangka berdasarkan nomor invoice
    public function getSimpananBerjangka($invoice)
    {
        Log::info("Attempting to fetch Simpanan Berjangka with invoice: $invoice");

        $simpananBerjangka = SimpananBerjangka::where('invoice_number', $invoice)->first();

        if (!$simpananBerjangka) {
            Log::error("Simpanan Berjangka not found for invoice: $invoice");
            return response()->json(['error' => 'Simpanan berjangka atau transaksi tidak ditemukan'], 404);
        }

        // Cek jika simpanan sudah expired dan update status
        if ($simpananBerjangka->status === 'pending' && $simpananBerjangka->expired_at < now()) {
            Log::info("Expired time passed for invoice: {$simpananBerjangka->invoice_number}. Updating status to expired.");

            $simpananBerjangka->status = 'expired';
            if ($simpananBerjangka->save()) {
                Log::info("Status updated to expired for invoice: {$simpananBerjangka->invoice_number}");
            } else {
                Log::error("Failed to update status to expired for invoice: {$simpananBerjangka->invoice_number}");
            }
        }

        // Kembalikan data simpanan berjangka dalam bentuk JSON
        return response()->json([
            'simpanan_berjangka' => [
                'invoice_number' => $simpananBerjangka->invoice_number,
                'amount' => $simpananBerjangka->amount,
                'status' => $simpananBerjangka->status,
                'expired_at' => $simpananBerjangka->expired_at,
                'created_at' => $simpananBerjangka->created_at,
                'updated_at' => $simpananBerjangka->updated_at,
            ],
        ]);
    }
    public function showPaymentResult(Request $request)
    {
        // Ambil data dari query string
        $virtualAccount = $request->query('virtualAccount', '1234567890123456'); // Default jika tidak ada query
        $amount = $request->query('amount', '100000'); // Default jika tidak ada query

        return view('pages.paymentgateway.payment_page_result', [
            'virtualAccount' => $virtualAccount,
            'amount' => $amount,
        ]);
    }
}
