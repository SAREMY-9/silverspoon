<?php

namespace App\Services\Payments;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class PaystackService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(
            config('services.paystack.base_url'),
            '/'
        );
    }

    /**
     * Initialize a Paystack transaction.
     */
    public function initializeTransaction(
        string $email,
        float $amount,
        string $reference,
        ?string $callbackUrl = null
    ): array {
        $secretKey = config('services.paystack.secret_key');

        if (!$secretKey) {
            throw new RuntimeException(
                'Paystack secret key is not configured.'
            );
        }

        $payload = [
            'email' => $email,

            // Paystack expects the amount in the smallest
            // currency unit. KES 2,500 = 250000.
            'amount' => (int) round($amount * 100),

            'currency' => 'KES',

            'reference' => $reference,
        ];

        if ($callbackUrl) {
            $payload['callback_url'] = $callbackUrl;
        }

        $response = Http::withToken($secretKey)
            ->acceptJson()
            ->post(
                $this->baseUrl . '/transaction/initialize',
                $payload
            );

        if ($response->failed()) {
            throw new RuntimeException(
                'Paystack transaction initialization failed: '
                . $response->body()
            );
        }

        $data = $response->json();

        if (($data['status'] ?? false) !== true) {
            throw new RuntimeException(
                $data['message']
                    ?? 'Paystack rejected the transaction.'
            );
        }

        if (!isset($data['data'])) {
            throw new RuntimeException(
                'Paystack returned an invalid initialization response.'
            );
        }

        return $data['data'];
    }

    /**
     * Verify a Paystack transaction by reference.
     */
    public function verifyTransaction(
        string $reference
    ): array {
        $secretKey = config('services.paystack.secret_key');

        if (!$secretKey) {
            throw new RuntimeException(
                'Paystack secret key is not configured.'
            );
        }

        $response = Http::withToken($secretKey)
            ->acceptJson()
            ->get(
                $this->baseUrl
                    . '/transaction/verify/'
                    . urlencode($reference)
            );

        if ($response->failed()) {
            throw new RuntimeException(
                'Paystack transaction verification failed: '
                . $response->body()
            );
        }

        $data = $response->json();

        if (($data['status'] ?? false) !== true) {
            throw new RuntimeException(
                $data['message']
                    ?? 'Paystack could not verify the transaction.'
            );
        }

        if (!isset($data['data'])) {
            throw new RuntimeException(
                'Paystack returned an invalid verification response.'
            );
        }

        return $data['data'];
    }
}