<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class DokuPaymentService
{
    protected $client;
    protected $clientId;
    protected $secretKey;
    protected $url;
    protected $path;

    // Method untuk menyiapkan data request
    public function __construct()
    {
        $this->client = new Client([
            'verify' => false, // Disable SSL verification (hanya untuk development)
        ]);

        $this->clientId = config('doku.client_id');
        $this->secretKey = config('doku.secret_key');
        $this->url = config('doku.sandbox_url'); // ubah ketika menggunakan production
        $this->path = '/checkout/v1/payment'; // url dari doku
    }

    protected function generateSignature(string $requestId, string $timestamp, string $digest): string
    {
        $signatureComponents = [
            "Client-Id:{$this->clientId}",
            "Request-Id:{$requestId}",
            "Request-Timestamp:{$timestamp}",
            "Request-Target:{$this->path}",
            "Digest:{$digest}",
        ];

        $rawSignature = implode("\n", $signatureComponents);
        return base64_encode(hash_hmac('sha256', $rawSignature, $this->secretKey, true));
    }


    protected function calculateDigest(array $data): string
    {
        $jsonData = json_encode($data);
        return base64_encode(hash('sha256', $jsonData, true));
    }
    public function createPayment(array $data)
    {
        $requestId = uniqid('', true);
        $requestTimestamp = gmdate('Y-m-d\TH:i:s\Z');
        $digest = $this->calculateDigest($data);
        $signature = $this->generateSignature($requestId, $requestTimestamp, $digest);
        $headers = [
            'Content-Type' => 'application/json',
            'Client-Id' => $this->clientId,
            'Request-Id' => $requestId,
            'Request-Timestamp' => $requestTimestamp,
            'Signature' => 'HMACSHA256=' . $signature
        ];

        try {
            $response = $this->client->post($this->url . $this->path, [
                'json' => $data,
                'headers' => $headers,
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
