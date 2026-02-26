<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Services\MyFatoorahService;
use App\Services\BillingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PaymentController extends Controller
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
     * Create a new payment controller instance.
     */
    public function __construct(MyFatoorahService $myfatoorahService, BillingService $billingService)
    {
        $this->myfatoorahService = $myfatoorahService;
        $this->billingService = $billingService;
    }

    /**
     * Display subscription plans page.
     */
    public function index()
    {
        $tiers = $this->billingService->getSubscriptionTiers();
        $topupPacks = $this->billingService->getCreditPacks();

        return view('billing.plans', compact('tiers', 'topupPacks'));
    }

    /**
     * Initiate subscription payment via MyFatoorah.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function initiateSubscriptionPayment(Request $request)
    {
        $validated = $request->validate([
            'tier' => 'required|in:basic,pro,enterprise',
        ]);

        $user = Auth::user();

        try {
            // Calculate callback URLs
            $callbackUrl = route('billing.webhook');
            $errorCallbackUrl = route('billing.plans') . '?error=payment_failed';

            // Create MyFatoorah invoice
            $invoiceData = [
                'user_id' => $user->id,
                'amount' => $this->billingService->getTierPrice($validated['tier']),
                'currency_code' => 'KWD',
                'invoice_expiry' => now()->addDays(7)->toDateTimeString(),
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'callback_url' => $callbackUrl,
                'error_callback_url' => $errorCallbackUrl,
            ];

            $invoice = $this->myfatoorahService->createInvoice($invoiceData);

            // Store pending subscription in session
            Session::put('pending_subscription', [
                'user_id' => $user->id,
                'tier' => $validated['tier'],
                'invoice_id' => $invoice['invoice_id'],
                'created_at' => now()->toDateTimeString(),
            ]);

            // Redirect to MyFatoorah invoice URL
            return redirect()->away($invoice['invoice_url']);

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to create payment invoice: ' . $e->getMessage());
        }
    }

    /**
     * Initiate top-up payment via MyFatoorah.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function initiateTopupPayment(Request $request)
    {
        $validated = $request->validate([
            'credits' => 'required|in:5000,15000,50000',
        ]);

        $user = Auth::user();

        try {
            $credits = (int) $validated['credits'];
            $price = $this->billingService->getCreditPackPrice($credits);

            // Calculate callback URLs
            $callbackUrl = route('billing.webhook');
            $errorCallbackUrl = route('billing.plans') . '?error=payment_failed';

            // Create MyFatoorah invoice
            $invoiceData = [
                'user_id' => $user->id,
                'amount' => $price,
                'currency_code' => 'KWD',
                'invoice_expiry' => now()->addDays(7)->toDateTimeString(),
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'callback_url' => $callbackUrl,
                'error_callback_url' => $errorCallbackUrl,
            ];

            $invoice = $this->myfatoorahService->createInvoice($invoiceData);

            // Store pending top-up in session
            Session::put('pending_topup', [
                'user_id' => $user->id,
                'credits' => $credits,
                'price' => $price,
                'invoice_id' => $invoice['invoice_id'],
                'created_at' => now()->toDateTimeString(),
            ]);

            // Redirect to MyFatoorah invoice URL
            return redirect()->away($invoice['invoice_url']);

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to create payment invoice: ' . $e->getMessage());
        }
    }

    /**
     * Handle payment return after MyFatoorah payment.
     */
    public function handleReturn(Request $request)
    {
        $status = $request->get('status');
        $invoiceId = $request->get('invoiceId');

        if ($status === 'Success') {
            return redirect()
                ->route('billing.plans')
                ->with('success', 'Payment completed successfully!');
        }

        return redirect()
            ->route('billing.plans')
            ->with('error', 'Payment was not successful.');
    }
}
