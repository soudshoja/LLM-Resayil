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
    protected string $apiKey;

    /**
     * Create a new MyFatoorah service instance.
     */
    public function __construct()
    {
        $this->baseUrl = env('MYFATOORAH_BASE_URL', 'https://apitest.myfatoorah.com');
        $this->apiKey = env('MYFATOORAH_API_KEY');
    }

    /**
     * Create an invoice in MyFatoorah.
     *
     * @param array $data Invoice data including:
     *   - user_id: User identifier
     *   - amount: Payment amount in KWD
     *   - currency_code: Currency code (KWD)
     *   - invoice_expiry: Expiry date for the invoice
     *   - customer_name: Customer name
     *   - customer_email: Customer email
     *   - callback_url: URL to redirect after payment
     *   - error_callback_url: URL to redirect on payment error
     * @return array Invoice response with invoice_id, invoice_url, status
     */
    public function createInvoice(array $data): array
    {
        $this->validateApiKey();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/v2/createInvoice', [
            'CustomerName' => $data['customer_name'] ?? 'Customer',
            'InvoiceValue' => $data['amount'],
            'CustomerEmail' => $data['customer_email'] ?? '',
            'CountryCode' => 'KWD',
            'DisplayCurrencyIso' => 'KWD',
            'CallbackUrl' => $data['callback_url'] ?? route('billing.webhook'),
            'ErrorUrl' => $data['error_callback_url'] ?? route('billing.webhook'),
            'ExpiryDate' => $data['invoice_expiry'] ?? now()->addDays(7)->toDateTimeString(),
            'Language' => 'en',
            'CustomerReference' => $data['user_id'] ?? '',
            'CustomerCivilId' => '',
        ]);

        if ($response->failed()) {
            Log::error('MyFatoorah createInvoice failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'data' => $data,
            ]);

            throw new \Exception('Failed to create invoice: ' . $response->status() . ' ' . $response->body());
        }

        $result = $response->json();

        return [
            'invoice_id' => $result['InvoiceId'] ?? null,
            'invoice_url' => $result['InvoiceUrl'] ?? null,
            'status' => $result['PaymentStatus'] ?? 'pending',
        ];
    }

    /**
     * Verify payment status via invoice ID.
     *
     * @param string $invoiceId MyFatoorah invoice ID
     * @return array Payment verification result with payment_status, transaction_id, amount, customer_name
     */
    public function verifyPayment(string $invoiceId): array
    {
        $this->validateApiKey();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->get($this->baseUrl . '/v2/getPaymentStatus', [
            'invoiceId' => $invoiceId,
        ]);

        if ($response->failed()) {
            Log::error('MyFatoorah verifyPayment failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'invoiceId' => $invoiceId,
            ]);

            throw new \Exception('Failed to verify payment: ' . $response->status());
        }

        $result = $response->json();

        return [
            'payment_status' => $result['PaymentStatus'] ?? 'unknown',
            'transaction_id' => $result['TransactionId'] ?? null,
            'amount' => $result['InvoiceValue'] ?? 0,
            'customer_name' => $result['CustomerName'] ?? '',
            'customer_email' => $result['CustomerEmail'] ?? '',
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
