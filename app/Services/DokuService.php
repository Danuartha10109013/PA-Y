<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class DokuService
{
    private $clientId;
    private $secretKey;

    public function __construct()
    {
        $this->clientId = config('services.doku.client_id');
        $this->secretKey = config('services.doku.secret_key');
    }

    public function generateVirtualAccount($amount, $invoiceNumber, $path, $merchantUniqueReference = null)
    {
        $requestId = (string) Str::uuid();
        $requestDate = now()->setTimezone('UTC')->format('Y-m-d\TH:i:s\Z');

        $requestBody = (object)[
            'order' => (object)[
                'amount' => $amount,
                'invoice_number' => $invoiceNumber,
            ],
            'virtual_account_info' => (object)[
                'expired_time' => 60,
                'reusable_status' => false,
                'info1' => 'Merchant Demo Store',
            ],
            'customer' => (object)[
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
        ];

        if ($path === '/bni-virtual-account/v2/payment-code') {
            $requestBody['virtual_account_info']['merchant_unique_reference'] = $merchantUniqueReference;
        }

        $digestValue = base64_encode(hash('sha256', json_encode($requestBody), true));
        $componentSignature = "Client-Id:" . $this->clientId . "\n" .
            "Request-Id:" . $requestId . "\n" .
            "Request-Timestamp:" . $requestDate . "\n" .
            "Request-Target:" . $path . "\n" .
            "Digest:" . $digestValue;

        $signature = base64_encode(hash_hmac('sha256', $componentSignature, $this->secretKey, true));
        $headers = [
            'Client-Id' => $this->clientId,
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

    public function checkStatus($invoiceNumber)
    {
        $requestId = (string) Str::uuid();
        $requestDate = now()->setTimezone('UTC')->format('Y-m-d\TH:i:s\Z');
        $path = '/orders/v1/status/' . $invoiceNumber; // Gunakan invoiceNumber sebagai path

        // $digestValue = base64_encode(hash('sha256', json_encode($requestBody), true));
        $componentSignature = "Client-Id:" . $this->clientId . "\n" .
            "Request-Id:" . $requestId . "\n" .
            "Request-Timestamp:" . $requestDate . "\n" .
            "Request-Target:" . $path;

        $signature = base64_encode(hash_hmac('sha256', $componentSignature, $this->secretKey, true));
        $headers = [
            'Client-Id' => $this->clientId,
            'Request-Id' => $requestId,
            'Request-Timestamp' => $requestDate,
            'Signature' => 'HMACSHA256=' . $signature
            // 'Content-Type' => 'application/json',
        ];

        $response = Http::withHeaders($headers)->get('https://api-sandbox.doku.com' . $path);

        if ($response->successful()) {
            return $response->json();
        } else {
            Log::error('Error checking Virtual Account status: ', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return [
                'error' => 'Error checking Virtual Account status',
                'message' => $response->body(),
            ];
        }
    }

    public function handlingNotification($request)
    {
        $payload = $request->all();

        if($payload['transactionStatusDesc'] == "Success") {

            // rubah databese

        } elseif($payload['transactionStatusDesc'] == "Failed") {

            // rubah


        }
    }
}
