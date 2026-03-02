<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MyFatoorahRecurringService
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
     * Create a new MyFatoorah recurring service instance.
     */
    public function __construct()
    {
        $this->baseUrl = env('MYFATOORAH_BASE_URL', 'https://apitest.myfatoorah.com');
        $this->apiKey = env('MYFATOORAH_API_KEY') ?: null;
    }

    /**
     * Create a payment profile for recurring payments.
     *
     * @param array $data Payment profile data:
     *   - customer_name: Customer name
     *   - customer_email: Customer email
     *   - customer_reference: User ID or reference
     * @return array Payment profile response
     */
    public function createPaymentProfile(array $data): array
    {
        $this->validateApiKey();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/v2/createPaymentProfile', [
            'CustomerName' => $data['customer_name'] ?? 'Customer',
            'CustomerEmail' => $data['customer_email'] ?? '',
            'CustomerReference' => $data['customer_reference'] ?? '',
            'CountryCode' => 'KWD',
            'DisplayCurrencyIso' => 'KWD',
        ]);

        if ($response->failed()) {
            Log::error('MyFatoorah createPaymentProfile failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'data' => $data,
            ]);

            throw new \Exception('Failed to create payment profile: ' . $response->status());
        }

        $result = $response->json();

        return [
            'payment_profile_id' => $result['PaymentProfileId'] ?? null,
            'customer_id' => $result['CustomerId'] ?? null,
            'payment_method_id' => $result['PaymentMethodId'] ?? null,
            'status' => $result['Status'] ?? 'pending',
        ];
    }

    /**
     * Create a recurring subscription.
     *
     * @param array $data Subscription data:
     *   - payment_profile_id: Payment profile ID from createPaymentProfile
     *   - amount: Subscription amount in KWD
     *   - interval: Subscription interval (month, year)
     *   - interval_count: Number of intervals between charges
     *   - start_date: When to start charging
     *   - end_date: When to stop charging (optional)
     *   - customer_reference: User ID
     * @return array Subscription response
     */
    public function createRecurringSubscription(array $data): array
    {
        $this->validateApiKey();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/v2/createRecurringSubscription', [
            'PaymentProfileId' => $data['payment_profile_id'],
            'InvoiceValue' => $data['amount'],
            'Interval' => $data['interval'] ?? 'month',
            'IntervalCount' => $data['interval_count'] ?? 1,
            'StartDate' => $data['start_date'] ?? now()->toDateTimeString(),
            'CustomerReference' => $data['customer_reference'] ?? '',
            'DisplayCurrencyIso' => 'KWD',
        ]);

        if ($response->failed()) {
            Log::error('MyFatoorah createRecurringSubscription failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'data' => $data,
            ]);

            throw new \Exception('Failed to create recurring subscription: ' . $response->status());
        }

        $result = $response->json();

        return [
            'subscription_id' => $result['SubscriptionId'] ?? null,
            'subscription_code' => $result['SubscriptionCode'] ?? null,
            'status' => $result['Status'] ?? 'pending',
        ];
    }

    /**
     * Suspend a recurring subscription.
     *
     * @param string $subscriptionId Subscription ID
     * @return array Suspend result
     */
    public function suspendSubscription(string $subscriptionId): array
    {
        $this->validateApiKey();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/v2/suspendRecurringSubscription', [
            'SubscriptionId' => $subscriptionId,
        ]);

        if ($response->failed()) {
            Log::error('MyFatoorah suspendSubscription failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'subscriptionId' => $subscriptionId,
            ]);

            throw new \Exception('Failed to suspend subscription: ' . $response->status());
        }

        $result = $response->json();

        return [
            'success' => $result['IsSuccess'] ?? false,
            'status' => $result['Status'] ?? 'unknown',
        ];
    }

    /**
     * Resume a suspended recurring subscription.
     *
     * @param string $subscriptionId Subscription ID
     * @return array Resume result
     */
    public function resumeSubscription(string $subscriptionId): array
    {
        $this->validateApiKey();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/v2/resumeRecurringSubscription', [
            'SubscriptionId' => $subscriptionId,
        ]);

        if ($response->failed()) {
            Log::error('MyFatoorah resumeSubscription failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'subscriptionId' => $subscriptionId,
            ]);

            throw new \Exception('Failed to resume subscription: ' . $response->status());
        }

        $result = $response->json();

        return [
            'success' => $result['IsSuccess'] ?? false,
            'status' => $result['Status'] ?? 'unknown',
        ];
    }

    /**
     * Get all subscriptions for a customer.
     *
     * @param string $customerReference Customer reference (user ID)
     * @return array List of subscriptions
     */
    public function getSubscriptions(string $customerReference): array
    {
        $this->validateApiKey();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/v2/getSubscriptions', [
            'CustomerReference' => $customerReference,
        ]);

        if ($response->failed()) {
            Log::error('MyFatoorah getSubscriptions failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'customerReference' => $customerReference,
            ]);

            throw new \Exception('Failed to get subscriptions: ' . $response->status());
        }

        $result = $response->json();

        return $result['Subscriptions'] ?? [];
    }

    /**
     * Get subscription details by ID.
     *
     * @param string $subscriptionId Subscription ID
     * @return array Subscription details
     */
    public function getSubscription(string $subscriptionId): array
    {
        $this->validateApiKey();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->get($this->baseUrl . '/v2/getRecurringSubscription', [
            'subscriptionId' => $subscriptionId,
        ]);

        if ($response->failed()) {
            Log::error('MyFatoorah getSubscription failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'subscriptionId' => $subscriptionId,
            ]);

            throw new \Exception('Failed to get subscription: ' . $response->status());
        }

        $result = $response->json();

        return $result['RecurringSubscription'] ?? [];
    }

    /**
     * Get payment methods for a customer.
     *
     * @param string $customerReference Customer reference (user ID)
     * @return array List of payment methods
     */
    public function getPaymentMethods(string $customerReference): array
    {
        $this->validateApiKey();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/v2/getCustomerPaymentMethods', [
            'CustomerReference' => $customerReference,
        ]);

        if ($response->failed()) {
            Log::error('MyFatoorah getPaymentMethods failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'customerReference' => $customerReference,
            ]);

            throw new \Exception('Failed to get payment methods: ' . $response->status());
        }

        $result = $response->json();

        return $result['PaymentMethods'] ?? [];
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
