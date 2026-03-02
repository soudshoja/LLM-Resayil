<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MyFatoorahService
{
    /**
     * MyFatoorah API base URL.
     */
    protected string $baseUrl;

    /**
     * MyFatoorah API key.
     */
    protected ?string $apiKey = null;

    /**
     * Create a new MyFatoorah service instance.
     */
    public function __construct()
    {
        $this->baseUrl = env('MYFATOORAH_BASE_URL', 'https://apitest.myfatoorah.com');
        $this->apiKey = env('MYFATOORAH_API_KEY') ?: null;
    }

    /**
     * Call InitiatePayment to get valid PaymentMethodId for the given amount.
     * Returns the first non-direct payment method (i.e. the checkout page).
     */
    public function getPaymentMethodId(float $amount): int
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type'  => 'application/json',
        ])->post($this->baseUrl . '/v2/InitiatePayment', [
            'InvoiceAmount' => $amount,
            'CurrencyIso'   => 'KWD',
        ]);

        if ($response->failed()) {
            Log::warning('MyFatoorah InitiatePayment failed, falling back to method 0', [
                'status' => $response->status(),
            ]);
            return 0;
        }

        $methods = $response->json('Data.PaymentMethods', []);

        // Prefer non-direct (checkout page) — first available
        foreach ($methods as $method) {
            if (!($method['IsDirectPayment'] ?? true)) {
                return (int) $method['PaymentMethodId'];
            }
        }

        // Fallback: first method
        return (int) ($methods[0]['PaymentMethodId'] ?? 0);
    }

    /**
     * Get all available payment methods for a given amount (cached 1 hour).
     */
    public function getAvailablePaymentMethods(float $amount = 1.000): array
    {
        $cacheKey = 'myfatoorah_payment_methods_' . $amount;

        return \Illuminate\Support\Facades\Cache::remember($cacheKey, 3600, function () use ($amount) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type'  => 'application/json',
            ])->post($this->baseUrl . '/v2/InitiatePayment', [
                'InvoiceAmount' => $amount,
                'CurrencyIso'   => 'KWD',
            ]);

            if ($response->failed()) {
                return [];
            }

            return $response->json('Data.PaymentMethods', []);
        });
    }

    /**
     * Create payment via ExecutePayment and return invoice URL.
     */
    public function createInvoice(array $data): array
    {
        $this->validateApiKey();

        $amount = $data['amount'];
        $paymentMethodId = $data['payment_method_id'] ?? $this->getPaymentMethodId((float) $amount);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type'  => 'application/json',
        ])->post($this->baseUrl . '/v2/ExecutePayment', [
            'PaymentMethodId'    => $paymentMethodId,
            'InvoiceValue'       => $amount,
            'DisplayCurrencyIso' => 'KWD',
            'CallBackUrl'        => $data['callback_url'],
            'ErrorUrl'           => $data['error_callback_url'],
            'Language'           => 'en',
            'CustomerName'       => $data['customer_name'] ?? 'Customer',
            'CustomerEmail'      => $data['customer_email'] ?? '',
            'CustomerReference'  => $data['user_id'] ?? '',
            'InvoiceItems'       => [[
                'ItemName'  => $data['item_name'] ?? 'LLM Resayil Subscription',
                'Quantity'  => 1,
                'UnitPrice' => $data['amount'],
            ]],
            'UserDefinedField'   => json_encode([
                'user_id' => $data['user_id'] ?? '',
                'type'    => $data['type'] ?? 'subscription',
                'tier'    => $data['tier'] ?? '',
            ]),
        ]);

        if ($response->failed()) {
            Log::error('MyFatoorah ExecutePayment failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            throw new \Exception('Failed to create payment: ' . $response->status());
        }

        $result = $response->json();

        if (!($result['IsSuccess'] ?? false)) {
            throw new \Exception('MyFatoorah error: ' . ($result['Message'] ?? 'Unknown error'));
        }

        return [
            'invoice_id'  => $result['Data']['InvoiceId'] ?? null,
            'invoice_url' => $result['Data']['PaymentURL'] ?? null,
            'status'      => 'pending',
        ];
    }

    /**
     * Verify payment status using PaymentId (from callback querystring ?paymentId=xxx).
     */
    public function verifyPayment(string $paymentId): array
    {
        $this->validateApiKey();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type'  => 'application/json',
        ])->post($this->baseUrl . '/v2/getPaymentStatus', [
            'Key'     => $paymentId,
            'KeyType' => 'PaymentId',
        ]);

        if ($response->failed()) {
            Log::error('MyFatoorah verifyPayment failed', [
                'status'    => $response->status(),
                'body'      => $response->body(),
                'paymentId' => $paymentId,
            ]);
            throw new \Exception('Failed to verify payment: ' . $response->status());
        }

        $result = $response->json();

        return [
            'payment_status' => $result['Data']['InvoiceStatus'] ?? 'unknown',
            'transaction_id' => $result['Data']['InvoiceTransactions'][0]['PaymentId'] ?? null,
            'amount'         => $result['Data']['InvoiceValue'] ?? 0,
            'customer_name'  => $result['Data']['CustomerName'] ?? '',
            'customer_email' => $result['Data']['CustomerEmail'] ?? '',
        ];
    }

    /**
     * Get full payment details by payment ID.
     *
     * @param string $paymentId MyFatoorah payment ID
     * @return array Full payment details
     */
    public function getPaymentStatus(string $paymentId): array
    {
        $this->validateApiKey();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->get($this->baseUrl . '/v2/getPayment', [
            'paymentId' => $paymentId,
        ]);

        if ($response->failed()) {
            Log::error('MyFatoorah getPaymentStatus failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'paymentId' => $paymentId,
            ]);

            throw new \Exception('Failed to get payment status: ' . $response->status());
        }

        return $response->json();
    }

    /**
     * Validate that API key is set.
     */
    protected function validateApiKey(): void
    {
        if (empty($this->apiKey)) {
            throw new \Exception('MYFATOORAH_API_KEY environment variable is not set');
        }
    }

    /**
     * Get the base URL for testing purposes.
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }
}
