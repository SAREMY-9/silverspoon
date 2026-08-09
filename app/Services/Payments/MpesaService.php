<?php

namespace App\Services\Payments;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class MpesaService
{
    protected string $environment;

    public function __construct()
    {
        $this->environment = config(
            'services.mpesa.environment',
            'sandbox'
        );
    }

    /**
     * Get the Daraja OAuth access token.
     */
    public function getAccessToken(): string
    {
        $consumerKey = config('services.mpesa.consumer_key');
        $consumerSecret = config('services.mpesa.consumer_secret');

        if (!$consumerKey || !$consumerSecret) {
            throw new RuntimeException(
                'M-Pesa consumer credentials are not configured.'
            );
        }

        $url = $this->baseUrl()
            . '/oauth/v1/generate?grant_type=client_credentials';

        $response = Http::withBasicAuth(
            $consumerKey,
            $consumerSecret
        )->get($url);

        if ($response->failed()) {
            throw new RuntimeException(
                'Failed to obtain M-Pesa access token: '
                . $response->body()
            );
        }

        $token = $response->json('access_token');

        if (!$token) {
            throw new RuntimeException(
                'M-Pesa did not return an access token.'
            );
        }

        return $token;
    }

    /**
     * Initiate an M-Pesa STK Push.
     */
    public function stkPush(
        string $phone,
        float $amount,
        string $accountReference,
        string $transactionDescription
    ): array {
        $token = $this->getAccessToken();

        $timestamp = now()->format('YmdHis');

        $shortcode = config('services.mpesa.shortcode');
        $passkey = config('services.mpesa.passkey');

        if (!$shortcode || !$passkey) {
            throw new RuntimeException(
                'M-Pesa shortcode or passkey is not configured.'
            );
        }

        $formattedPhone = $this->formatPhone($phone);

        $password = base64_encode(
            $shortcode . $passkey . $timestamp
        );

        $response = Http::withToken($token)
            ->post(
                $this->baseUrl()
                    . '/mpesa/stkpush/v1/processrequest',
                [
                    'BusinessShortCode' => $shortcode,
                    'Password' => $password,
                    'Timestamp' => $timestamp,
                    'TransactionType' =>
                        'CustomerPayBillOnline',
                    'Amount' => (int) round($amount),
                    'PartyA' => $formattedPhone,
                    'PartyB' => $shortcode,
                    'PhoneNumber' => $formattedPhone,
                    'CallBackURL' => config(
                        'services.mpesa.callback_url'
                    ),
                    'AccountReference' =>
                        $accountReference,
                    'TransactionDesc' =>
                        $transactionDescription,
                ]
            );

        if ($response->failed()) {
            throw new RuntimeException(
                'M-Pesa STK Push request failed: '
                . $response->body()
            );
        }

        $data = $response->json();

        if (($data['ResponseCode'] ?? null) !== '0') {
            throw new RuntimeException(
                $data['ResponseDescription']
                    ?? 'M-Pesa rejected the STK Push request.'
            );
        }

        return $data;
    }

    /**
     * Convert Kenyan phone numbers to 2547XXXXXXXX format.
     */
    protected function formatPhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);

        if (str_starts_with($phone, '07')) {
            return '254' . substr($phone, 1);
        }

        if (str_starts_with($phone, '01')) {
            return '254' . substr($phone, 1);
        }

        if (str_starts_with($phone, '7')) {
            return '254' . $phone;
        }

        if (str_starts_with($phone, '1')) {
            return '254' . $phone;
        }

        if (str_starts_with($phone, '254')) {
            return $phone;
        }

        throw new RuntimeException(
            'Invalid Kenyan phone number.'
        );
    }

    protected function baseUrl(): string
    {
        return $this->environment === 'production'
            ? 'https://api.safaricom.co.ke'
            : 'https://sandbox.safaricom.co.ke';
    }
}