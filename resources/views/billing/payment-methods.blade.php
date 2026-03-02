@extends('layouts.app')

@section('title', 'Payment Methods')

@push('styles')
<style>
    .payment-methods-header { margin-bottom: 2rem; }
    .payment-methods-header h1 { font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem; }
    .payment-methods-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1rem; }
    .payment-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 1.5rem; position: relative; transition: all 0.2s; }
    .payment-card:hover { border-color: var(--gold-muted); }
    .card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
    .card-type { font-size: 2rem; font-weight: 700; }
    .card-type.visa { color: #1a1f71; }
    .card-type.mastercard { color: #eb001b; }
    .card-type.knet { color: #0055a5; }
    .card-expiry { font-size: 0.85rem; color: var(--text-secondary); }
    .card-footer { display: flex; justify-content: space-between; align-items: center; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border); }
    .card-default { font-size: 0.75rem; padding: 0.125rem 0.5rem; background: rgba(212,175,55,0.1); color: var(--gold); border-radius: 4px; }
    .btn-delete { background: #dc3545; color: white; padding: 0.375rem 0.75rem; border-radius: 6px; border: none; font-size: 0.75rem; cursor: pointer; }
    .btn-delete:hover { background: #bb2d3b; }
    .add-method-section { margin-top: 2rem; padding: 1.5rem; background: var(--bg-card); border-radius: 12px; border: 1px solid var(--border); }
    .add-method-title { font-size: 1rem; font-weight: 600; margin-bottom: 1rem; }
    .form-group { margin-bottom: 1rem; }
    .form-label { display: block; font-size: 0.85rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.375rem; }
    .form-input { width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--border); border-radius: 8px; background: var(--bg-secondary); color: var(--text-primary); font-size: 0.9rem; }
    .form-input:focus { outline: 2px solid var(--gold-muted); border-color: transparent; }
    .btn-add { width: 100%; padding: 0.625rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.9rem; border: none; cursor: pointer; }
    .btn-add-primary { background: linear-gradient(135deg, var(--gold), var(--gold-light)); color: #0a0d14; }
    .btn-add-primary:hover { opacity: 0.9; }
    .card-icons { display: flex; gap: 0.5rem; align-items: center; margin-top: 1rem; }
    .card-icon { font-size: 1.25rem; }
    .card-icon.visa { color: #1a1f71; }
    .card-icon.mastercard { color: #eb001b; }
    .card-icon.knet { color: #0055a5; }
    @media(max-width: 600px) { .payment-methods-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<main>
    <div class="payment-methods-header">
        <h1>Payment Methods</h1>
        <p class="text-secondary text-sm">Manage your saved payment methods for subscriptions</p>
    </div>

    @if(session('success'))
    <div class="alert alert-success mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-error mb-4">{{ session('error') }}</div>
    @endif
    @if(session('info'))
    <div class="alert alert-info mb-4">{{ session('info') }}</div>
    @endif

    @if(count($paymentMethods) > 0)
    <div class="payment-methods-grid">
        @foreach($paymentMethods as $method)
        <div class="payment-card">
            <div class="card-header">
                <div>
                    <div class="card-type {{ strtolower($method['CardType'] ?? 'visa') }}">
                        {{ strtoupper(substr($method['CardType'] ?? 'V', 0, 4)) }}
                    </div>
                    <div class="card-expiry">
                        •••• •••• •••• {{ $method['Last4'] ?? '****' }}
                        <br>
                        Exp: {{ $method['ExpiryDate'] ?? 'N/A' }}
                    </div>
                </div>
                @if($method['IsDefault'] ?? false)
                <span class="card-default">Default</span>
                @endif
            </div>
            <div class="card-icons">
                @if(str_contains($method['CardType'] ?? '', 'Visa'))
                <span class="card-icon visa">V</span>
                @endif
                @if(str_contains($method['CardType'] ?? '', 'Master'))
                <span class="card-icon mastercard">M</span>
                @endif
                @if(str_contains($method['CardType'] ?? '', 'KNET'))
                <span class="card-icon knet">K</span>
                @endif
            </div>
            <div class="card-footer">
                <button type="button" class="btn-delete" onclick="confirmDelete('{{ $method['PaymentMethodId'] ?? '' }}')">
                    Delete
                </button>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="card" style="text-align: center; padding: 3rem;">
        <div style="display: flex; gap: 1rem; justify-content: center; margin-bottom: 1.5rem; flex-wrap: wrap;">
            <div style="background: #0055a5; color: white; font-weight: 700; padding: 0.5rem 1.25rem; border-radius: 8px; font-size: 0.9rem; letter-spacing: 0.05em;">KNET</div>
            <div style="background: #1a1f71; color: white; font-weight: 700; padding: 0.5rem 1.25rem; border-radius: 8px; font-size: 0.9rem; font-style: italic;">VISA</div>
            <div style="background: #eb001b; color: white; font-weight: 700; padding: 0.5rem 1.25rem; border-radius: 8px; font-size: 0.9rem;">Mastercard</div>
        </div>
        <h2 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem;">No payment methods saved</h2>
        <p class="text-secondary text-sm" style="margin-bottom: 1.5rem;">
            Add a card below to enable recurring subscriptions and auto-billing.
        </p>
    </div>
    @endif

    <div class="add-method-section">
        <h2 class="add-method-title">Add Payment Method</h2>
        <p class="text-secondary text-sm" style="margin-bottom: 1.5rem;">
            You'll be redirected to our secure payment page. We accept:
        </p>
        <div style="display: flex; gap: 1rem; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap;">
            <div style="background: #0055a5; color: white; font-weight: 700; padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.9rem; letter-spacing: 0.05em;">KNET</div>
            <div style="background: #1a1f71; color: white; font-weight: 700; padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.9rem; font-style: italic;">VISA</div>
            <div style="background: #eb001b; color: white; font-weight: 700; padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.9rem;">Mastercard</div>
        </div>
        <form method="POST" action="{{ route('billing.payment-methods.store') }}">
            @csrf
            <input type="hidden" name="customer_name" value="{{ auth()->user()->name }}">
            <input type="hidden" name="customer_email" value="{{ auth()->user()->email }}">
            <button type="submit" class="btn-add btn-add-primary">
                Add Card via Secure Checkout →
            </button>
        </form>
        <p class="text-xs text-muted" style="margin-top: 0.75rem;">
            A 0.100 KWD card verification charge is used to tokenize your card. It will be credited back.
        </p>
    </div>

    <div style="margin-top: 2rem; padding: 1.5rem; background: var(--bg-card); border-radius: 12px; border: 1px solid var(--border);">
        <h3 style="font-size: 0.9rem; font-weight: 600; margin-bottom: 0.75rem;">Payment Security</h3>
        <p class="text-secondary text-sm" style="margin: 0;">
            Your payment information is securely processed through MyFatoorah. We do not store your full card details. All transactions are encrypted and PCI-DSS compliant.
        </p>
    </div>
</main>

<script>
function confirmDelete(paymentMethodId) {
    if (confirm('Are you sure you want to delete this payment method?')) {
        // For now, we need a delete route
        // In production, this would call the delete endpoint
        alert('Payment method deletion requires MyFatoorah API enhancement. Please contact support.');
    }
}
</script>
@endsection
