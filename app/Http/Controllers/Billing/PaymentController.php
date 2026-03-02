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
        $paymentMethods = $this->myfatoorahService->getAvailablePaymentMethods();

        return view('billing.plans', compact('tiers', 'topupPacks', 'paymentMethods'));
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
            'tier' => 'required|in:starter,basic,pro,enterprise',
            'payment_method_id' => 'required|integer',
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
                'payment_method_id' => $validated['payment_method_id'],
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
            'credits' => 'required|in:500,1100,3000',
            'payment_method_id' => 'required|integer',
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
                'payment_method_id' => $validated['payment_method_id'],
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

    /**
     * Start free trial — charge 0.100 KWD to capture card, then activate trial on callback.
     */
    public function initiateTrialPayment(Request $request)
    {
        $validated = $request->validate([
            'payment_method_id' => 'required|integer',
        ]);

        $user = Auth::user();

        if ($user->trial_started_at) {
            return redirect()->route('billing.plans')->with('error', 'Trial already activated.');
        }

        try {
            $invoice = $this->myfatoorahService->createInvoice([
                'user_id'            => $user->id,
                'amount'             => 0.100,
                'customer_name'      => $user->name,
                'customer_email'     => $user->email,
                'item_name'          => 'LLM Resayil — Card Verification',
                'type'               => 'trial',
                'tier'               => 'starter',
                'callback_url'       => route('billing.trial.callback'),
                'error_callback_url' => url('/billing/plans?error=payment_failed'),
                'payment_method_id'  => $validated['payment_method_id'],
            ]);

            Session::put('pending_trial', [
                'user_id'    => $user->id,
                'invoice_id' => $invoice['invoice_id'],
                'created_at' => now()->toDateTimeString(),
            ]);

            return redirect()->away($invoice['invoice_url']);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to initiate trial: ' . $e->getMessage());
        }
    }

    /**
     * Trial callback — MyFatoorah redirects here after card entry.
     * Activate trial: set trial_started_at, grant 1000 credits, queue Day-1 WhatsApp.
     */
    public function handleTrialCallback(Request $request)
    {
        $paymentId = $request->get('paymentId');

        if (!$paymentId) {
            return redirect()->route('billing.plans')->with('error', 'Invalid callback. No payment ID.');
        }

        try {
            $status = $this->myfatoorahService->verifyPayment($paymentId);

            if ($status['payment_status'] !== 'Paid') {
                return redirect()->route('billing.plans')->with('error', 'Card verification failed. Please try again.');
            }

            $user = Auth::user();
            $pending = Session::get('pending_trial');

            if (!$pending || $pending['user_id'] !== $user->id) {
                return redirect()->route('billing.plans')->with('error', 'Session mismatch. Try again.');
            }

            Session::forget('pending_trial');

            // Activate trial
            $user->trial_started_at = now();
            $user->subscription_tier = 'starter';
            $user->subscription_expiry = now()->addDays(7);
            $user->myfatoorah_payment_profile_id = $status['transaction_id'];
            $user->save();

            // Grant 1000 trial credits
            $this->billingService->grantTrialCredits($user->id);

            // Queue Day-1 WhatsApp welcome (1 min delay for queue processing)
            dispatch(new \App\Jobs\SendTrialWelcome($user->id))->delay(now()->addMinutes(1));

            return redirect()->route('billing.plans')
                ->with('success', 'Free trial activated! 1,000 credits added. Welcome to LLM Resayil.');

        } catch (\Exception $e) {
            return redirect()->route('billing.plans')->with('error', 'Trial activation failed: ' . $e->getMessage());
        }
    }
}
