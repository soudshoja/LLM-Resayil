<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Services\BillingService;
use App\Models\TopupPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillingController extends Controller
{
    /**
     * The billing service instance.
     */
    protected BillingService $billingService;

    /**
     * Create a new billing controller instance.
     */
    public function __construct(BillingService $billingService)
    {
        $this->billingService = $billingService;
    }

    /**
     * Get the current subscription status for the authenticated user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSubscription(Request $request)
    {
        $user = $request->user();
        $subscription = $this->billingService->getActiveSubscription($user->id);

        return response()->json([
            'status' => 'success',
            'data' => [
                'tier' => $subscription?->tier ?? 'basic',
                'status' => $subscription?->status ?? 'inactive',
                'expires_at' => $subscription?->ends_at ?? null,
                'credits' => $user->credits,
            ],
        ]);
    }

    /**
     * Get available top-up packs with pricing.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTopupPacks()
    {
        $packs = $this->billingService->getCreditPacks();

        return response()->json([
            'status' => 'success',
            'data' => [
                'packs' => collect($packs)->map(function ($price, $credits) {
                    return [
                        'credits' => (int) $credits,
                        'price' => $price,
                    ];
                })->values()->toArray(),
            ],
        ]);
    }

    /**
     * Get the authenticated user's top-up purchase history.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTopupHistory(Request $request)
    {
        $user = $request->user();

        $history = TopupPurchase::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'status' => 'success',
            'data' => [
                'total' => $history->total(),
                'per_page' => $history->perPage(),
                'current_page' => $history->currentPage(),
                'packs' => $history->map(function ($topup) {
                    return [
                        'id' => $topup->id,
                        'credits' => $topup->credits,
                        'price' => $topup->price,
                        'status' => $topup->status,
                        'transaction_id' => $topup->transaction_id,
                        'paid_at' => $topup->paid_at?->toIso8601String(),
                        'created_at' => $topup->created_at->toIso8601String(),
                    ];
                })->toArray(),
            ],
        ]);
    }
}
