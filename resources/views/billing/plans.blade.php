@extends('layouts.app')

@section('title', __('billing.subscription_plans'))

@push('styles')
<style>
    .plans-header { margin-bottom: 2rem; text-align: center; }
    .plans-header h1 { font-size: 1.75rem; font-weight: 700; margin-bottom: 0.5rem; }
    .plans-header p { color: var(--text-secondary); font-size: 0.95rem; }
    .trial-section { margin-bottom: 2.5rem; padding: 2rem; background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; }
    .trial-badge { display: inline-block; background: linear-gradient(135deg, #28a745, #20c997); color: white; padding: 0.35rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1rem; }
    .trial-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-bottom: 1.5rem; }
    .trial-card { background: var(--bg-secondary); border: 2px dashed var(--gold-muted); border-radius: 12px; padding: 1.5rem; }
    .trial-icon { font-size: 3rem; margin-bottom: 1rem; }
    .trial-features { list-style: none; padding-left: 0; }
    .trial-features li { display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; }
    .trial-features li svg { flex-shrink: 0; color: #28a745; }
    .plans-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 2.5rem; }
    .plan-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; padding: 2rem; display: flex; flex-direction: column; position: relative; transition: border-color 0.2s, transform 0.2s; }
    .plan-card:hover { border-color: var(--gold-muted); transform: translateY(-2px); }
    .plan-card.featured { border-color: var(--gold-muted); box-shadow: 0 0 0 1px rgba(212,175,55,0.2), 0 8px 32px rgba(212,175,55,0.08); }
    .plan-badge { position: absolute; top: -13px; left: 50%; transform: translateX(-50%); background: linear-gradient(135deg, var(--gold), var(--gold-light)); color: #0a0d14; font-size: 0.7rem; font-weight: 700; padding: 0.25rem 0.85rem; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap; }
    .plan-name { font-size: 1rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-secondary); margin-bottom: 0.75rem; }
    .plan-price { font-size: 2.5rem; font-weight: 700; color: var(--gold); line-height: 1; margin-bottom: 0.25rem; }
    .plan-price span { font-size: 1rem; font-weight: 500; color: var(--text-secondary); }
    .plan-billing { font-size: 0.8rem; color: var(--text-muted); margin-bottom: 1.5rem; }
    .plan-billing small { font-size: 0.7rem; color: var(--text-muted); }
    .plan-divider { border: none; border-top: 1px solid var(--border); margin-bottom: 1.25rem; }
    .plan-features { list-style: none; flex: 1; margin-bottom: 1.75rem; }
    .plan-features li { display: flex; align-items: center; gap: 0.6rem; font-size: 0.875rem; color: var(--text-secondary); padding: 0.35rem 0; }
    .plan-features li svg { flex-shrink: 0; color: var(--gold); }
    .plan-cta { width: 100%; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; font-size: 0.9rem; cursor: pointer; border: none; transition: all 0.2s; text-align: center; }
    .plan-cta-gold { background: linear-gradient(135deg, var(--gold), var(--gold-light)); color: #0a0d14; }
    .plan-cta-gold:hover { opacity: 0.9; transform: translateY(-1px); box-shadow: 0 4px 15px rgba(212,175,55,0.3); }
    .plan-cta-outline { background: transparent; border: 1px solid var(--gold-muted); color: var(--gold); }
    .plan-cta-outline:hover { background: rgba(212,175,55,0.1); }
    .topup-section { margin-bottom: 2rem; }
    .topup-section h2 { font-size: 1.1rem; font-weight: 600; margin-bottom: 1rem; }
    .topup-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; }
    .topup-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 10px; padding: 1.25rem; text-align: center; cursor: pointer; transition: all 0.2s; }
    .topup-card:hover { border-color: var(--gold-muted); }
    .topup-credits { font-size: 1.35rem; font-weight: 700; color: var(--gold); }
    .topup-price { font-size: 0.875rem; color: var(--text-secondary); margin-top: 0.2rem; }
    .topup-bonus { display: inline-block; margin-top: 0.25rem; font-size: 0.75rem; color: #28a745; }
    .topup-buy { display: inline-block; margin-top: 0.75rem; font-size: 0.78rem; font-weight: 600; color: var(--gold); border: 1px solid var(--gold-muted); padding: 0.25rem 0.75rem; border-radius: 6px; background: transparent; cursor: pointer; }
    @media(max-width: 900px) { .trial-grid { grid-template-columns: 1fr; } .plans-grid { grid-template-columns: 1fr; } .topup-grid { grid-template-columns: 1fr; } }

    /* Extra API Key section */
    .extra-key-section { margin-bottom: 2rem; }
    .extra-key-section h2 { font-size: 1.1rem; font-weight: 600; margin-bottom: 0.35rem; }
    .extra-key-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 10px; padding: 1.5rem; display: flex; align-items: center; justify-content: space-between; gap: 1.5rem; flex-wrap: wrap; }
    .extra-key-card:hover { border-color: var(--gold-muted); }
    .extra-key-info { flex: 1; }
    .extra-key-info p { font-size: 0.875rem; color: var(--text-secondary); margin: 0.25rem 0 0; }
    .extra-key-price { font-size: 1.35rem; font-weight: 700; color: var(--gold); }
    .extra-key-buy { padding: 0.6rem 1.25rem; border-radius: 8px; font-size: 0.875rem; font-weight: 600; color: var(--gold); border: 1px solid var(--gold-muted); background: transparent; cursor: pointer; white-space: nowrap; transition: all 0.2s; }
    .extra-key-buy:hover { background: rgba(212,175,55,0.1); }
    .extra-key-maxed { display: inline-block; font-size: 0.8rem; color: var(--text-muted); border: 1px solid var(--border); border-radius: 6px; padding: 0.35rem 0.75rem; }

    /* Payment Method Modal */
    .pm-modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); backdrop-filter: blur(4px); z-index: 100; align-items: center; justify-content: center; }
    .pm-modal-overlay.active { display: flex; }
    .pm-modal { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 2rem; width: 100%; max-width: 480px; box-shadow: 0 24px 64px rgba(0,0,0,0.5); }
    .pm-modal h3 { font-size: 1.1rem; font-weight: 600; margin-bottom: 0.35rem; }
    .pm-modal p { font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1.5rem; }
    .pm-methods { display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 1rem; }
    .pm-method { border: 2px solid var(--border); border-radius: 10px; padding: 1.25rem 1rem; text-align: center; cursor: pointer; transition: all 0.2s; background: var(--bg-secondary); }
    .pm-method:hover { border-color: var(--gold); background: rgba(212,175,55,0.05); transform: translateY(-2px); }
    .pm-method img { height: 36px; object-fit: contain; margin-bottom: 0.6rem; display: block; margin-left: auto; margin-right: auto; }
    .pm-method span { font-size: 0.8rem; font-weight: 600; color: var(--text-primary); display: block; }
    .pm-method small { font-size: 0.7rem; color: var(--text-muted); }
    .pm-cancel { display: block; text-align: center; margin-top: 1.25rem; font-size: 0.85rem; color: var(--text-muted); cursor: pointer; }
    .pm-cancel:hover { color: var(--text-primary); }
</style>
@endpush

@section('content')
@if(auth()->user()->trial_started_at && !auth()->user()->myfatoorah_subscription_id)
    @php $trialExpiry = auth()->user()->trial_started_at->addDays(7); @endphp
    <div style="background: rgba(5,150,105,0.1); border: 1px solid rgba(5,150,105,0.3); border-radius: 8px; padding: 0.75rem 1.25rem; max-width: 1200px; margin: 1rem auto; font-size: 0.875rem; color: #6ee7b7; display: flex; align-items: center; gap: 0.5rem;">
        ✅ <strong>{{ __('billing.trial_active') }}</strong> — {{ __('billing.trial_expires') }} {{ $trialExpiry->format('d M Y') }}
        ({{ $trialExpiry->diffForHumans() }})
    </div>
@elseif(auth()->user()->subscription_expiry)
    <div style="background: rgba(212,175,55,0.1); border: 1px solid rgba(212,175,55,0.3); border-radius: 8px; padding: 0.75rem 1.25rem; max-width: 1200px; margin: 1rem auto; font-size: 0.875rem; color: var(--gold); display: flex; align-items: center; gap: 0.5rem;">
        ⚡ <strong>{{ __('billing.current_plan') }}</strong>: {{ ucfirst(auth()->user()->subscription_tier) }}
        — {{ __('billing.renews') }} {{ auth()->user()->subscription_expiry->format('d M Y') }}
    </div>
@endif
<main>
    @if(session('error'))
    <div class="alert alert-error mb-4">{{ session('error') }}</div>
    @endif
    @if(request('error') === 'payment_failed')
    <div class="alert alert-error mb-4">{{ __('billing.payment_not_successful') }}</div>
    @endif

    <div class="plans-header">
        <h1>{{ __('billing.choose_your_plan') }} <span class="text-gold">{{ __('billing.plan') }}</span></h1>
        <p>{{ __('billing.unlock_llm_power') }}</p>
    </div>

    {{-- Free Trial Card --}}
    <div class="trial-section">
        <div class="trial-badge">{{ __('billing.free_trial') }}</div>
        <h2 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1.5rem;">{{ __('billing.try_before_buy') }}</h2>
        <div class="trial-grid">
            <div class="trial-card">
                <div class="trial-icon">⚡</div>
                <h3 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 1rem;">{{ __('billing.7_day_trial') }}</h3>
                <ul class="trial-features">
                    <li>
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ __('billing.full_starter_features') }}
                    </li>
                    <li>
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ __('billing.1000_credits') }}
                    </li>
                    <li>
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ __('billing.small_models_only') }}
                    </li>
                    <li>
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ __('billing.1_free_api_key') }}
                    </li>
                </ul>
                <p style="font-size: 0.8rem; color: var(--text-secondary); margin-top: 1rem; text-align: center;">
                    <a href="/credits" style="color: var(--gold); font-weight: 600; text-decoration: underline; text-decoration-style: dashed; text-underline-offset: 4px;">{{ __('billing.how_credits_work') }}</a>
                </p>
            </div>
            <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center;">
                <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1rem;">{{ __('billing.after_trial') }}</div>
                <div style="font-size: 2rem; font-weight: 700; color: var(--gold); margin-bottom: 1.5rem;">{{ __('billing.auto_bill_to_starter') }}</div>
                <div style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1.5rem; text-align: left;">
                    <p style="margin: 0.25rem 0;">{{ __('billing.card_required') }}</p>
                    <p style="margin: 0.25rem 0;">{{ __('billing.cancel_anytime') }}</p>
                </div>
                <button type="button" class="plan-cta plan-cta-gold" style="width: 100%;" onclick="openPaymentModal('trial')">{{ __('billing.start_free_trial') }}</button>
            </div>
        </div>
        <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 1rem; text-align: center;">
            {{ __('billing.payments_secure') }}
        </p>
    </div>

    <div class="plans-grid">
        {{-- Starter Tier --}}
        <div class="plan-card">
            <div class="plan-badge">{{ __('billing.most_popular') }}</div>
            <div class="plan-name">{{ __('billing.starter') }}</div>
            <div class="plan-price">15 <span>KWD</span></div>
            <div class="plan-billing">{{ __('billing.per_month') }} &nbsp;&middot;&nbsp; {{ __('billing.billed_monthly') }}</div>
            <hr class="plan-divider">
            <ul class="plan-features">
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('billing.1000_credits_month') }}
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('billing.10_requests_minute') }}
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('billing.1_free_api_key') }}
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('billing.small_models_only') }}
                </li>
            </ul>
            <button type="button" class="plan-cta plan-cta-outline" onclick="openPaymentModal('subscription', 'starter')">{{ __('billing.start_monthly_plan') }}</button>
        </div>

        {{-- Basic Tier (featured) --}}
        <div class="plan-card featured">
            <div class="plan-badge">{{ __('billing.best_value') }}</div>
            <div class="plan-name">{{ __('billing.basic') }}</div>
            <div class="plan-price">25 <span>KWD</span></div>
            <div class="plan-billing">{{ __('billing.per_month') }} &nbsp;&middot;&nbsp; {{ __('billing.billed_monthly') }}</div>
            <hr class="plan-divider">
            <ul class="plan-features">
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('billing.3000_credits_month') }}
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('billing.30_requests_minute') }}
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('billing.1_free_api_key') }}
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('billing.all_model_sizes') }}
                </li>
            </ul>
            <button type="button" class="plan-cta plan-cta-gold" onclick="openPaymentModal('subscription', 'basic')">{{ __('billing.start_monthly_plan') }}</button>
        </div>

        {{-- Pro Tier --}}
        <div class="plan-card">
            <div class="plan-name">{{ __('billing.pro') }}</div>
            <div class="plan-price">45 <span>KWD</span></div>
            <div class="plan-billing">{{ __('billing.per_month') }} &nbsp;&middot;&nbsp; {{ __('billing.billed_monthly') }}</div>
            <hr class="plan-divider">
            <ul class="plan-features">
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('billing.10000_credits_month') }}
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('billing.60_requests_minute') }}
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('billing.2_free_api_keys') }}
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('billing.priority_cloud_failover') }}
                </li>
            </ul>
            <button type="button" class="plan-cta plan-cta-outline" onclick="openPaymentModal('subscription', 'pro')">{{ __('billing.start_monthly_plan') }}</button>
        </div>
    </div>

    {{-- Credit Top-Up Packs --}}
    <div class="card topup-section">
        <h2>{{ __('billing.top_up_credits') }}</h2>
        <p class="text-secondary text-sm mb-4">{{ __('billing.need_extra_credits') }}</p>
        <div class="topup-grid">
            <div class="topup-card">
                <div class="topup-credits">500</div>
                <div class="topup-price">{{ __('billing.credits') }}</div>
                <div class="topup-bonus">{{ __('billing.topup_no_bonus') }}</div>
                <button type="button" class="topup-buy" onclick="openPaymentModal('topup', '500')">5 KWD</button>
            </div>
            <div class="topup-card">
                <div class="topup-credits">1,100</div>
                <div class="topup-price">{{ __('billing.credits') }}</div>
                <div class="topup-bonus">{{ __('billing.topup_bonus') }}</div>
                <button type="button" class="topup-buy" onclick="openPaymentModal('topup', '1100')">10 KWD</button>
            </div>
            <div class="topup-card">
                <div class="topup-credits">3,000</div>
                <div class="topup-price">{{ __('billing.credits') }}</div>
                <div class="topup-bonus">{{ __('billing.topup_bonus_20') }}</div>
                <button type="button" class="topup-buy" onclick="openPaymentModal('topup', '3000')">25 KWD</button>
            </div>
        </div>
        <p class="text-xs text-muted mt-4">{{ __('billing.payments_secure') }}</p>
    </div>

    {{-- Additional API Keys --}}
    @php
        $user = auth()->user();
        $userTier = $user->subscription_tier ?? 'starter';
        $currentKeyCount = \App\Models\ApiKeys::where('user_id', $user->id)->where('status', 'active')->count();
        $nextKeyNumber = $currentKeyCount + 1;
        $nextKeyCost = app(\App\Services\BillingService::class)->getAdditionalApiKeyCost($userTier, $nextKeyNumber);
        $tierMaxKeys = ['starter' => 3, 'basic' => 3, 'pro' => 4];
        $maxKeys = $tierMaxKeys[$userTier] ?? 3;
        $isAdmin = auth()->user()->email === 'admin@llm.resayil.io';
    @endphp
    <div class="card extra-key-section">
        <h2>{{ __('billing.additional_api_keys') }}</h2>
        <p class="text-secondary text-sm mb-4">{{ __('billing.need_more_keys') }} {{ ucfirst($userTier) }}</p>
        <div class="extra-key-card">
            <div class="extra-key-info">
                <strong>{{ __('billing.your_keys') }}</strong>
                @if($isAdmin)
                <p>{{ __('billing.unlimited_keys') }}</p>
                @else
                <p>{{ $currentKeyCount }} {{ __('billing.keys_used') }} {{ $maxKeys }} {{ __('billing.on_your_plan') }}</p>
                @endif
            </div>
            @if($nextKeyCost !== null)
                <div style="text-align: right;">
                    @if($nextKeyCost == 0)
                    <div class="extra-key-price">{{ __('billing.free') }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.2rem;">{{ __('billing.included_with_plan') }}</div>
                    @else
                    <div class="extra-key-price">{{ number_format($nextKeyCost, 3) }} KWD</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.2rem;">{{ __('billing.one_time_purchase') }}</div>
                    @endif
                </div>
                <button type="button" class="extra-key-buy" onclick="openPaymentModal('extra-key')">
                    @if($nextKeyCost == 0)
                    {{ __('billing.create_free_api_key') }}
                    @else
                    {{ __('billing.buy_extra_api_key') }} {{ number_format($nextKeyCost, 3) }} KWD
                    @endif
                </button>
            @else
                <span class="extra-key-maxed">{{ __('billing.max_keys_reached') }}</span>
            @endif
        </div>
        <p class="text-xs text-muted mt-4">{{ __('billing.payments_secure') }}</p>
    </div>

    {{-- Payment Method Modal --}}
    <div class="pm-modal-overlay" id="pmModal">
        <div class="pm-modal">
            <h3>{{ __('billing.choose_payment_method') }}</h3>
            <p id="pmModalDesc">{{ __('billing.select_payment_method') }}</p>
            <div class="pm-methods" id="pmMethods">
                @forelse($paymentMethods as $method)
                <div class="pm-method" onclick="selectPaymentMethod({{ $method['PaymentMethodId'] }})">
                    <img src="{{ $method['ImageUrl'] }}" alt="{{ $method['PaymentMethodEn'] }}" onerror="this.style.display='none'">
                    <span>{{ $method['PaymentMethodEn'] }}</span>
                </div>
                @empty
                <p style="color:var(--text-muted); font-size:0.85rem;">{{ __('billing.loading_payment_methods') }}</p>
                @endforelse
            </div>
            <span class="pm-cancel" onclick="closePaymentModal()">{{ __('billing.cancel') }}</span>
        </div>
    </div>

    {{-- Hidden forms submitted by JS --}}
    <form id="formTrial" method="POST" action="{{ route('billing.trial.start') }}" style="display:none">
        @csrf
        <input type="hidden" name="payment_method_id" id="trialMethodId">
    </form>
    <form id="formSubscription" method="POST" action="/billing/payment/subscription" style="display:none">
        @csrf
        <input type="hidden" name="tier" id="subTier">
        <input type="hidden" name="payment_method_id" id="subMethodId">
    </form>
    <form id="formTopup" method="POST" action="/billing/payment/topup" style="display:none">
        @csrf
        <input type="hidden" name="credits" id="topupCredits">
        <input type="hidden" name="payment_method_id" id="topupMethodId">
    </form>
    <form id="formExtraKey" method="POST" action="{{ route('billing.extra-key.pay') }}" style="display:none">
        @csrf
        <input type="hidden" name="payment_method_id" id="extraKeyMethodId">
    </form>
</main>

@push('scripts')
<script>
let pendingType = null;
let pendingValue = null;

function openPaymentModal(type, value) {
    pendingType = type;
    pendingValue = value || null;

    const descs = {
        trial: '{{ __('billing.trial_starting') }}',
        subscription: '{{ __('billing.subscription_starting') }}',
        topup: '{{ __('billing.topup_purchasing') }}',
        'extra-key': '{{ __('billing.extra_key_purchasing') }}',
    };
    document.getElementById('pmModalDesc').textContent = descs[type] || '';
    document.getElementById('pmModal').classList.add('active');
}

function closePaymentModal() {
    document.getElementById('pmModal').classList.remove('active');
    pendingType = null;
    pendingValue = null;
}

function selectPaymentMethod(methodId) {
    if (pendingType === 'trial') {
        document.getElementById('trialMethodId').value = methodId;
        document.getElementById('formTrial').submit();
    } else if (pendingType === 'subscription') {
        document.getElementById('subTier').value = pendingValue;
        document.getElementById('subMethodId').value = methodId;
        document.getElementById('formSubscription').submit();
    } else if (pendingType === 'topup') {
        document.getElementById('topupCredits').value = pendingValue;
        document.getElementById('topupMethodId').value = methodId;
        document.getElementById('formTopup').submit();
    } else if (pendingType === 'extra-key') {
        document.getElementById('extraKeyMethodId').value = methodId;
        document.getElementById('formExtraKey').submit();
    }
    closePaymentModal();
}

// Close modal on overlay click
document.getElementById('pmModal').addEventListener('click', function(e) {
    if (e.target === this) closePaymentModal();
});
</script>
@endpush
@endsection
