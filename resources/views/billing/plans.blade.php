@extends('layouts.app')

@section('title', 'Subscription Plans')

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
    .topup-buy { display: inline-block; margin-top: 0.75rem; font-size: 0.78rem; font-weight: 600; color: var(--gold); border: 1px solid var(--gold-muted); padding: 0.25rem 0.75rem; border-radius: 6px; }
    @media(max-width: 900px) { .trial-grid { grid-template-columns: 1fr; } .plans-grid { grid-template-columns: 1fr; } .topup-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
@if(auth()->user()->trial_started_at && !auth()->user()->myfatoorah_subscription_id)
    @php $trialExpiry = auth()->user()->trial_started_at->addDays(7); @endphp
    <div style="background: rgba(5,150,105,0.1); border: 1px solid rgba(5,150,105,0.3); border-radius: 8px; padding: 0.75rem 1.25rem; max-width: 1200px; margin: 1rem auto; font-size: 0.875rem; color: #6ee7b7; display: flex; align-items: center; gap: 0.5rem;">
        ✅ <strong>Free trial active</strong> — expires {{ $trialExpiry->format('d M Y') }}
        ({{ $trialExpiry->diffForHumans() }})
    </div>
@elseif(auth()->user()->subscription_expiry)
    <div style="background: rgba(212,175,55,0.1); border: 1px solid rgba(212,175,55,0.3); border-radius: 8px; padding: 0.75rem 1.25rem; max-width: 1200px; margin: 1rem auto; font-size: 0.875rem; color: var(--gold); display: flex; align-items: center; gap: 0.5rem;">
        ⚡ <strong>Current plan:</strong> {{ ucfirst(auth()->user()->subscription_tier) }}
        — renews {{ auth()->user()->subscription_expiry->format('d M Y') }}
    </div>
@endif
<main>
    @if(session('error'))
    <div class="alert alert-error mb-4">{{ session('error') }}</div>
    @endif
    @if(request('error') === 'payment_failed')
    <div class="alert alert-error mb-4">Payment was not successful. Please try again.</div>
    @endif

    <div class="plans-header">
        <h1>Choose Your <span class="text-gold">Plan</span></h1>
        <p>Unlock the full power of LLM Resayil. Start with a free 7-day trial, no commitment.</p>
    </div>

    {{-- Free Trial Card --}}
    <div class="trial-section">
        <div class="trial-badge">Free Trial</div>
        <h2 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1.5rem;">Try Before You Buy</h2>
        <div class="trial-grid">
            <div class="trial-card">
                <div class="trial-icon">⚡</div>
                <h3 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 1rem;">7-Day Free Trial</h3>
                <ul class="trial-features">
                    <li>
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Full Starter tier features
                    </li>
                    <li>
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        1,000 credits
                    </li>
                    <li>
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Small models only (3-14B)
                    </li>
                    <li>
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        1 free API key
                    </li>
                </ul>
            </div>
            <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center;">
                <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1rem;">After Trial</div>
                <div style="font-size: 2rem; font-weight: 700; color: var(--gold); margin-bottom: 1.5rem;">Auto-bill to Starter</div>
                <div style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1.5rem; text-align: left;">
                    <p style="margin: 0.25rem 0;">Credit card signup required</p>
                    <p style="margin: 0.25rem 0;">Cancel anytime during trial</p>
                </div>
                <form method="POST" action="{{ route('billing.trial.start') }}" style="width: 100%;">
                    @csrf
                    <button type="submit" class="plan-cta plan-cta-gold" style="width: 100%;">Start Free Trial — Card Required</button>
                </form>
            </div>
        </div>
        <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 1rem; text-align: center;">
            ⚠️ Payments processed securely via KNET / credit card. Cancel anytime during the trial period.
        </p>
    </div>

    <div class="plans-grid">
        {{-- Starter Tier --}}
        <div class="plan-card">
            <div class="plan-badge">Most Popular</div>
            <div class="plan-name">Starter</div>
            <div class="plan-price">15 <span>KWD</span></div>
            <div class="plan-billing">per month &nbsp;&middot;&nbsp; billed monthly</div>
            <hr class="plan-divider">
            <ul class="plan-features">
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    1,000 credits / month
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    10 requests / minute
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    1 free API key
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Small models only
                </li>
            </ul>
            <form method="POST" action="/billing/payment/subscription">
                @csrf
                <input type="hidden" name="tier" value="starter">
                <button type="submit" class="plan-cta plan-cta-outline">Start Monthly Plan</button>
            </form>
        </div>

        {{-- Basic Tier (featured) --}}
        <div class="plan-card featured">
            <div class="plan-badge">Best Value</div>
            <div class="plan-name">Basic</div>
            <div class="plan-price">25 <span>KWD</span></div>
            <div class="plan-billing">per month &nbsp;&middot;&nbsp; billed monthly</div>
            <hr class="plan-divider">
            <ul class="plan-features">
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    3,000 credits / month
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    30 requests / minute
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    1 free API key
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    All model sizes
                </li>
            </ul>
            <form method="POST" action="/billing/payment/subscription">
                @csrf
                <input type="hidden" name="tier" value="basic">
                <button type="submit" class="plan-cta plan-cta-gold">Start Monthly Plan</button>
            </form>
        </div>

        {{-- Pro Tier --}}
        <div class="plan-card">
            <div class="plan-name">Pro</div>
            <div class="plan-price">45 <span>KWD</span></div>
            <div class="plan-billing">per month &nbsp;&middot;&nbsp; billed monthly</div>
            <hr class="plan-divider">
            <ul class="plan-features">
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    10,000 credits / month
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    60 requests / minute
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    2 free API keys
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Priority cloud failover
                </li>
            </ul>
            <form method="POST" action="/billing/payment/subscription">
                @csrf
                <input type="hidden" name="tier" value="pro">
                <button type="submit" class="plan-cta plan-cta-outline">Start Monthly Plan</button>
            </form>
        </div>
    </div>

    {{-- Credit Top-Up Packs --}}
    <div class="card topup-section">
        <h2>Top Up Credits</h2>
        <p class="text-secondary text-sm mb-4">Need extra credits? Purchase a one-time credit pack.</p>
        <div class="topup-grid">
            <div class="topup-card">
                <div class="topup-credits">500</div>
                <div class="topup-price">credits</div>
                <div class="topup-bonus">No bonus</div>
                <form method="POST" action="/billing/payment/topup" style="display:inline">
                    @csrf
                    <input type="hidden" name="credits" value="500">
                    <button type="submit" class="topup-buy">5 KWD</button>
                </form>
            </div>
            <div class="topup-card">
                <div class="topup-credits">1,100</div>
                <div class="topup-price">credits</div>
                <div class="topup-bonus">+10% bonus</div>
                <form method="POST" action="/billing/payment/topup" style="display:inline">
                    @csrf
                    <input type="hidden" name="credits" value="1100">
                    <button type="submit" class="topup-buy">10 KWD</button>
                </form>
            </div>
            <div class="topup-card">
                <div class="topup-credits">3,000</div>
                <div class="topup-price">credits</div>
                <div class="topup-bonus">+20% bonus</div>
                <form method="POST" action="/billing/payment/topup" style="display:inline">
                    @csrf
                    <input type="hidden" name="credits" value="3000">
                    <button type="submit" class="topup-buy">25 KWD</button>
                </form>
            </div>
        </div>
        <p class="text-xs text-muted mt-4">Payments processed securely via KNET / credit card</p>
    </div>
</main>
@endsection
