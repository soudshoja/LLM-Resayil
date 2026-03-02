@extends('layouts.app')

@section('title', 'Subscription Plans')

@push('styles')
<style>
    .plans-header { margin-bottom: 2rem; text-align: center; }
    .plans-header h1 { font-size: 1.75rem; font-weight: 700; margin-bottom: 0.5rem; }
    .plans-header p { color: var(--text-secondary); font-size: 0.95rem; }
    .plans-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 2.5rem; }
    .plan-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; padding: 2rem; display: flex; flex-direction: column; position: relative; transition: border-color 0.2s, transform 0.2s; }
    .plan-card:hover { border-color: var(--gold-muted); transform: translateY(-2px); }
    .plan-card.featured { border-color: var(--gold-muted); box-shadow: 0 0 0 1px rgba(212,175,55,0.2), 0 8px 32px rgba(212,175,55,0.08); }
    .plan-badge { position: absolute; top: -13px; left: 50%; transform: translateX(-50%); background: linear-gradient(135deg, var(--gold), var(--gold-light)); color: #0a0d14; font-size: 0.7rem; font-weight: 700; padding: 0.25rem 0.85rem; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap; }
    .plan-name { font-size: 1rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-secondary); margin-bottom: 0.75rem; }
    .plan-price { font-size: 2.5rem; font-weight: 700; color: var(--gold); line-height: 1; margin-bottom: 0.25rem; }
    .plan-price span { font-size: 1rem; font-weight: 500; color: var(--text-secondary); }
    .plan-billing { font-size: 0.8rem; color: var(--text-muted); margin-bottom: 1.5rem; }
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
    .topup-buy { display: inline-block; margin-top: 0.75rem; font-size: 0.78rem; font-weight: 600; color: var(--gold); border: 1px solid var(--gold-muted); padding: 0.25rem 0.75rem; border-radius: 6px; }
    @media(max-width: 900px) { .plans-grid { grid-template-columns: 1fr; } .topup-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<main>
    @if(session('error'))
    <div class="alert alert-error mb-4">{{ session('error') }}</div>
    @endif
    @if(request('error') === 'payment_failed')
    <div class="alert alert-error mb-4">Payment was not successful. Please try again.</div>
    @endif

    <div class="plans-header">
        <h1>Choose Your <span class="text-gold">Plan</span></h1>
        <p>Unlock the full power of LLM Resayil. Upgrade or downgrade at any time.</p>
    </div>

    <div class="plans-grid">
        {{-- Basic --}}
        <div class="plan-card">
            <div class="plan-name">Basic</div>
            <div class="plan-price">99 <span>KWD</span></div>
            <div class="plan-billing">per year &nbsp;&middot;&nbsp; billed annually</div>
            <hr class="plan-divider">
            <ul class="plan-features">
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    10,000 credits / month
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    10 requests / minute
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Local models only
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    API key access
                </li>
            </ul>
            <form method="POST" action="/billing/payment/subscription">
                @csrf
                <input type="hidden" name="tier" value="basic">
                <button type="submit" class="plan-cta plan-cta-outline">Subscribe to Basic</button>
            </form>
        </div>

        {{-- Pro (featured) --}}
        <div class="plan-card featured">
            <div class="plan-badge">Most Popular</div>
            <div class="plan-name">Pro</div>
            <div class="plan-price">299 <span>KWD</span></div>
            <div class="plan-billing">per year &nbsp;&middot;&nbsp; billed annually</div>
            <hr class="plan-divider">
            <ul class="plan-features">
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    50,000 credits / month
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    30 requests / minute
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Local + priority models
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    API key access
                </li>
            </ul>
            <form method="POST" action="/billing/payment/subscription">
                @csrf
                <input type="hidden" name="tier" value="pro">
                <button type="submit" class="plan-cta plan-cta-gold">Subscribe to Pro</button>
            </form>
        </div>

        {{-- Enterprise --}}
        <div class="plan-card">
            <div class="plan-name">Enterprise</div>
            <div class="plan-price">999 <span>KWD</span></div>
            <div class="plan-billing">per year &nbsp;&middot;&nbsp; billed annually</div>
            <hr class="plan-divider">
            <ul class="plan-features">
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Unlimited credits
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    60 requests / minute
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    All models
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Team management
                </li>
            </ul>
            <form method="POST" action="/billing/payment/subscription">
                @csrf
                <input type="hidden" name="tier" value="enterprise">
                <button type="submit" class="plan-cta plan-cta-outline">Subscribe to Enterprise</button>
            </form>
        </div>
    </div>

    {{-- Credit Top-Up Packs --}}
    <div class="card topup-section">
        <h2>Top Up Credits</h2>
        <p class="text-secondary text-sm mb-4">Need extra credits? Purchase a one-time credit pack.</p>
        <div class="topup-grid">
            @foreach($topupPacks as $credits => $price)
            <div class="topup-card">
                <div class="topup-credits">{{ number_format($credits) }}</div>
                <div class="topup-price">credits</div>
                <form method="POST" action="/billing/payment/topup" style="display:inline">
                    @csrf
                    <input type="hidden" name="credits" value="{{ $credits }}">
                    <button type="submit" class="topup-buy">{{ $price }} KWD</button>
                </form>
            </div>
            @endforeach
        </div>
        <p class="text-xs text-muted mt-4">Payments processed securely via MyFatoorah (KNET / credit card)</p>
    </div>
</main>
@endsection
