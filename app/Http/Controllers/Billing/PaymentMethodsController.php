<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\MyFatoorahRecurringService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentMethodsController extends Controller
{
    /**
     * The MyFatoorah recurring service instance.
     */
    protected MyFatoorahRecurringService $myfatoorahRecurringService;

    /**
     * Create a new payment methods controller instance.
     */
    public function __construct(MyFatoorahRecurringService $myfatoorahRecurringService)
    {
        $this->myfatoorahRecurringService = $myfatoorahRecurringService;
    }

    /**
     * Display payment methods page.
     */
    public function index()
    {
        $user = Auth::user();
        $customerReference = $user->id;

        try {
            // Get saved payment methods
            $paymentMethods = $this->myfatoorahRecurringService->getPaymentMethods($customerReference);
        } catch (\Exception $e) {
            $paymentMethods = [];
            // Log error but don't fail - user can still add new methods
        }

        return view('billing.payment-methods', compact('paymentMethods'));
    }

    /**
     * Add a new payment method via MyFatoorah payment profile.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
        ]);

        try {
            // Create payment profile
            $paymentProfile = $this->myfatoorahRecurringService->createPaymentProfile([
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_reference' => $user->id,
            ]);

            return redirect()
                ->route('billing.payment-methods')
                ->with('success', 'Payment method added successfully. You can now set up recurring payments.');
        } catch (\Exception $e) {
            return redirect()
                ->route('billing.payment-methods')
                ->with('error', 'Failed to add payment method: ' . $e->getMessage());
        }
    }

    /**
     * Remove a payment method.
     */
    public function destroy(Request $request, $id)
    {
        $user = Auth::user();

        try {
            // For now, we can't actually delete from MyFatoorah without their API
            // In production, use: $this->myfatoorahRecurringService->deletePaymentMethod($id);

            return redirect()
                ->route('billing.payment-methods')
                ->with('info', 'Payment method deletion requires MyFatoorah API enhancement.');
        } catch (\Exception $e) {
            return redirect()
                ->route('billing.payment-methods')
                ->with('error', 'Failed to remove payment method: ' . $e->getMessage());
        }
    }
}
