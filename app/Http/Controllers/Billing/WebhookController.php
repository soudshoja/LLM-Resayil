<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Services\MyFatoorahService;
use App\Services\BillingService;
use App\Models\TopupPurchase;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * The MyFatoorah service instance.
     */
    protected MyFatoorahService $myfatoorahService;

    /**
     * The billing service instance.
     */
    protected BillingService $billingService;

    /**
     * Create a new webhook controller instance.
     */
    public function __construct(MyFatoorahService $myfatoorahService, BillingService $billingService)
    {
        $this->myfatoorahService = $myfatoorahService;
        $this->billingService = $billingService;
    }

    /**
     * Handle MyFatoorah payment webhook notifications.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleWebhook(Request $request)
    {
        // Log webhook for debugging
        Log::info('MyFatoorah webhook received', $request->all());

        // Validate HMAC signature (if configured)
        if (!$this->validateSignature($request)) {
            Log::warning('MyFatoorah webhook: Invalid signature');
            return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 401);
        }

        // Get payment data from request
        $invoiceId = $request->get('InvoiceId') ?: $request->get('invoice_id');
        $paymentStatus = $request->get('PaymentStatus') ?: $request->get('payment_status');

        if (!$invoiceId) {
            Log::error('MyFatoorah webhook: Missing InvoiceId');
            return response()->json(['status' => 'error', 'message' => 'Missing InvoiceId'], 400);
        }

        // Check if transaction has already been processed
        if ($this->isTransactionProcessed($invoiceId)) {
            Log::info('MyFatoorah webhook: Transaction already processed', ['invoiceId' => $invoiceId]);
            return response()->json(['status' => 'success', 'message' => 'Already processed'], 200);
        }

        // Verify payment with MyFatoorah
        try {
            $paymentInfo = $this->myfatoorahService->verifyPayment($invoiceId);
        } catch (\Exception $e) {
            Log::error('MyFatoorah webhook: Payment verification failed', [
                'invoiceId' => $invoiceId,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['status' => 'error', 'message' => 'Verification failed'], 500);
        }

        // Process based on payment status
        switch ($paymentStatus ?? $paymentInfo['payment_status']) {
            case 'Paid':
            case 'PaidSuccessfully':
                $this->handlePaidInvoice($invoiceId, $paymentInfo);
                break;
            case 'Cancelled':
            case 'Expired':
                $this->handleFailedInvoice($invoiceId);
                break;
            default:
                Log::info('MyFatoorah webhook: Unhandled status', [
                    'invoiceId' => $invoiceId,
                    'status' => $paymentStatus ?? $paymentInfo['payment_status'],
                ]);
                break;
        }

        return response()->json(['status' => 'success', 'message' => 'Webhook processed'], 200);
    }

    /**
     * Validate HMAC signature from MyFatoorah.
     *
     * @param Request $request
     * @return bool
     */
    protected function validateSignature(Request $request): bool
    {
        // Check for HMAC signature in header
        $receivedHmac = $request->header('X-Myfatoorah-Hmac');

        if (empty($receivedHmac)) {
            Log::info('MyFatoorah webhook: No HMAC signature in header');
            // Allow webhook without HMAC for now (can be enabled later)
            return true;
        }

        // Generate expected HMAC using API key
        $payload = $request->getContent();
        $expectedHmac = hash_hmac('sha256', $payload, env('MYFATOORAH_API_KEY'));

        return hash_equals($expectedHmac, $receivedHmac);
    }

    /**
     * Check if transaction has already been processed.
     *
     * @param string $invoiceId
     * @return bool
     */
    protected function isTransactionProcessed(string $invoiceId): bool
    {
        // Check subscriptions
        $subscription = Subscription::where('MyFatoorah_invoice_id', $invoiceId)->first();
        if ($subscription) {
            return true;
        }

        // Check topup purchases
        $topup = TopupPurchase::where('transaction_id', $invoiceId)->first();
        if ($topup) {
            return true;
        }

        return false;
    }

    /**
     * Handle paid invoice - update subscription or add credits.
     *
     * @param string $invoiceId
     * @param array $paymentInfo
     * @return void
     */
    protected function handlePaidInvoice(string $invoiceId, array $paymentInfo): void
    {
        // Try to find pending subscription in session or database
        $subscription = Subscription::where('MyFatoorah_invoice_id', $invoiceId)->first();

        if ($subscription) {
            // Update subscription status
            $subscription->status = 'active';
            $subscription->save();

            Log::info('MyFatoorah webhook: Subscription activated', [
                'subscriptionId' => $subscription->id,
                'invoiceId' => $invoiceId,
            ]);

            return;
        }

        // Try to find pending topup purchase
        $topup = TopupPurchase::where('transaction_id', $invoiceId)->first();

        if ($topup && $topup->status === 'pending') {
            // Complete topup and add credits
            $topup->status = 'completed';
            $topup->paid_at = now();
            $topup->save();

            // Add credits to user
            $user = $topup->user;
            $user->increment('credits', $topup->credits);

            Log::info('MyFatoorah webhook: Topup completed', [
                'topupId' => $topup->id,
                'invoiceId' => $invoiceId,
                'credits' => $topup->credits,
            ]);

            return;
        }

        // Check for pending session data
        // (In production, you might want to use a database queue for pending transactions)

        Log::info('MyFatoorah webhook: No matching transaction found', [
            'invoiceId' => $invoiceId,
            'customerEmail' => $paymentInfo['customer_email'] ?? null,
            'amount' => $paymentInfo['amount'] ?? null,
        ]);
    }

    /**
     * Handle cancelled or expired invoice.
     *
     * @param string $invoiceId
     * @return void
     */
    protected function handleFailedInvoice(string $invoiceId): void
    {
        // Mark subscription as cancelled if exists
        $subscription = Subscription::where('MyFatoorah_invoice_id', $invoiceId)->first();
        if ($subscription) {
            $subscription->status = 'cancelled';
            $subscription->save();

            Log::info('MyFatoorah webhook: Subscription cancelled/expired', [
                'subscriptionId' => $subscription->id,
                'invoiceId' => $invoiceId,
            ]);
        }

        // Mark topup as failed if exists
        $topup = TopupPurchase::where('transaction_id', $invoiceId)->first();
        if ($topup) {
            $topup->status = 'failed';
            $topup->save();

            Log::info('MyFatoorah webhook: Topup failed/expired', [
                'topupId' => $topup->id,
                'invoiceId' => $invoiceId,
            ]);
        }
    }
}
