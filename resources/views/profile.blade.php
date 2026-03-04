@extends('layouts.app')

@section('title', __('profile.title'))

@push('styles')
<style>
.badge-red { background: rgba(255,80,80,0.15); color: #ff5050; border: 1px solid rgba(255,80,80,0.3); }
.profile-wrapper { max-width: 720px; }
.profile-header { margin-bottom: 2rem; }
.profile-header h1 { font-size: 1.5rem; font-weight: 700; }
.section-title { font-size: 1rem; font-weight: 600; margin-bottom: 1.5rem; }
.section-title-sm { font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem; }
.phone-current { margin-bottom: 1rem; }
.plan-row { display: flex; align-items: center; gap: 1rem; margin-top: 0.5rem; }
.plan-tier { color: var(--gold); font-weight: 600; text-transform: capitalize; }
.inline-row { display: flex; gap: 0.75rem; align-items: flex-end; }
.inline-row .form-group { flex: 1; margin-bottom: 0; }
.inline-row .btn { white-space: nowrap; padding: 0.6rem 1.25rem; }
.phone-links { margin-top: 0.75rem; font-size: 0.8rem; color: var(--text-muted); }
.phone-links a { color: var(--gold); text-decoration: none; }
</style>
@endpush

@section('content')
<main class="profile-wrapper">
    <div class="profile-header">
        <h1>{{ __('profile.title') }}</h1>
        <p class="text-secondary text-sm">{{ __('profile.subtitle') }}</p>
    </div>

    {{-- Profile Info --}}
    <div class="card mb-6">
        <h2 class="section-title">{{ __('profile.profile_info') }}</h2>

        @if(session('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
        @endif

        @if($errors->has('name') || $errors->has('email'))
        <div class="alert alert-error mb-4">
            @foreach($errors->only(['name','email']) as $e)<div>{{ $e }}</div>@endforeach
        </div>
        @endif

        <form method="POST" action="/profile">
            @csrf
            <div class="form-group">
                <label class="form-label">{{ __('profile.name') }}</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">{{ __('profile.email') }}</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input" required>
            </div>
            <div class="plan-row">
                <button type="submit" class="btn btn-gold">{{ __('profile.save_changes') }}</button>
                <span class="text-xs text-muted">{{ __('profile.plan') }}: <span class="plan-tier">{{ $user->subscription_tier ?? 'free' }}</span></span>
            </div>
        </form>
    </div>

    {{-- Phone Number --}}
    <div class="card mb-6">
        <h2 class="section-title-sm">{{ __('profile.phone_number') }}</h2>
        <p class="text-secondary text-sm mb-4">{{ __('profile.phone_subtitle') }}</p>

        <div id="phone-current" class="phone-current">
            <span class="text-muted text-sm">{{ __('profile.current') }}: </span>
            <span id="phone-display" style="font-weight:600">{{ auth()->user()->phone ?? '—' }}</span>
            @if(auth()->user()->phone_verified_at)
                <span id="phone-badge" class="badge badge-green" style="margin-left:0.5rem">{{ __('profile.verified') }}</span>
            @else
                <span id="phone-badge" class="badge badge-red" style="margin-left:0.5rem">{{ __('profile.unverified') }}</span>
            @endif
        </div>

        <div id="phone-alert"></div>

        <div id="phone-step-enter">
            <form id="phoneForm">
                <div class="inline-row">
                    <div class="form-group">
                        <label class="form-label">{{ __('profile.new_phone') }}</label>
                        <input type="tel" id="new-phone-input" name="phone" class="form-input"
                               placeholder="96550000000" required>
                    </div>
                    <button type="submit" id="btn-send-phone-otp" class="btn btn-outline">
                        {{ __('profile.send_code') }}
                    </button>
                </div>
            </form>
        </div>

        <div id="phone-step-verify" style="display:none">
            <p class="text-sm text-secondary mb-4" id="phone-verify-hint">
                {{ __('profile.enter_code_hint') }}
            </p>
            <form id="phoneVerifyForm">
                <div class="inline-row">
                    <div class="form-group">
                        <label class="form-label">{{ __('profile.verification_code') }}</label>
                        <input type="text" id="phone-otp-input" name="otp_code" class="form-input"
                               maxlength="6" inputmode="numeric" pattern="[0-9]{6}" placeholder="------" required>
                    </div>
                    <button type="submit" id="btn-verify-phone" class="btn btn-gold">
                        {{ __('profile.confirm') }}
                    </button>
                </div>
            </form>
            <div class="phone-links">
                <a href="#" id="phone-back-link">{{ __('profile.change_number') }}</a>
                &nbsp;&middot;&nbsp;
                <a href="#" id="phone-resend-link">{{ __('profile.resend_code') }}</a>
            </div>
        </div>
    </div>

    {{-- Change Password --}}
    <div class="card">
        <h2 class="section-title">{{ __('profile.change_password') }}</h2>

        @if(session('password_success'))
        <div class="alert alert-success mb-4">{{ session('password_success') }}</div>
        @endif

        @if($errors->has('current_password') || $errors->has('password'))
        <div class="alert alert-error mb-4">
            @foreach($errors->only(['current_password','password']) as $e)<div>{{ $e }}</div>@endforeach
        </div>
        @endif

        <form method="POST" action="/profile/password">
            @csrf
            <div class="form-group">
                <label class="form-label">{{ __('profile.current_password') }}</label>
                <input type="password" name="current_password" class="form-input" required autocomplete="current-password">
            </div>
            <div class="form-group">
                <label class="form-label">{{ __('profile.new_password') }}</label>
                <input type="password" name="password" class="form-input" required autocomplete="new-password">
            </div>
            <div class="form-group">
                <label class="form-label">{{ __('profile.confirm_new_password') }}</label>
                <input type="password" name="password_confirmation" class="form-input" required autocomplete="new-password">
            </div>
            <button type="submit" class="btn btn-outline" style="margin-top:0.5rem">{{ __('profile.update_password') }}</button>
        </form>
    </div>
</main>
@endsection

@push('scripts')
<script>
const PHONE_CSRF = '{{ csrf_token() }}';
// UI strings loaded from Laravel lang so they work in Arabic locale too
const LANG = {
    sending:             @json(__('profile.sending')),
    send_code:           @json(__('profile.send_code')),
    verifying:           @json(__('profile.verifying')),
    confirm:             @json(__('profile.confirm')),
    phone_updated:       @json(__('profile.phone_updated')),
    verification_failed: @json(__('profile.verification_failed')),
    failed_send:         @json(__('profile.failed_send')),
    new_code_sent:       @json(__('profile.new_code_sent')),
    error_occurred:      @json(__('profile.error_occurred')),
    resend_code:         @json(__('profile.resend_code')),
    verified:            @json(__('profile.verified')),
};
let pendingPhone = '';

// Helper: render alert HTML using a safe template — content is from server lang strings or
// server-returned error messages (not user-supplied unescaped HTML).
function alertHtml(type, msg) {
    const div = document.createElement('div');
    div.className = 'alert alert-' + type;
    div.textContent = msg;
    return div;
}

document.getElementById('phoneForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-send-phone-otp');
    const alertEl = document.getElementById('phone-alert');
    alertEl.replaceChildren();
    pendingPhone = document.getElementById('new-phone-input').value;
    btn.textContent = LANG.sending;
    btn.disabled = true;

    try {
        const res = await fetch('/profile/phone/otp', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': PHONE_CSRF },
            body: JSON.stringify({ phone: pendingPhone })
        });
        const json = await res.json();
        if (res.ok && json.step === 'verify') {
            const hint = @json(__('profile.enter_code_sent_to', ['phone' => '__PHONE__']));
            document.getElementById('phone-verify-hint').textContent = hint.replace('__PHONE__', pendingPhone);
            document.getElementById('phone-step-enter').style.display = 'none';
            document.getElementById('phone-step-verify').style.display = 'block';
            document.getElementById('phone-otp-input').focus();
        } else {
            alertEl.appendChild(alertHtml('error', json.message || LANG.failed_send));
            btn.textContent = LANG.send_code;
            btn.disabled = false;
        }
    } catch (err) {
        alertEl.appendChild(alertHtml('error', LANG.error_occurred));
        btn.textContent = LANG.send_code;
        btn.disabled = false;
    }
});

document.getElementById('phoneVerifyForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-verify-phone');
    const alertEl = document.getElementById('phone-alert');
    alertEl.replaceChildren();
    btn.textContent = LANG.verifying;
    btn.disabled = true;

    try {
        const res = await fetch('/profile/phone/verify', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': PHONE_CSRF },
            body: JSON.stringify({ phone: pendingPhone, otp_code: document.getElementById('phone-otp-input').value })
        });
        const json = await res.json();
        if (res.ok) {
            // BUG FIX: update phone display text AND swap badge class + text
            document.getElementById('phone-display').textContent = pendingPhone;
            const badge = document.getElementById('phone-badge');
            badge.textContent = LANG.verified;
            badge.className = 'badge badge-green';
            badge.style.marginLeft = '0.5rem';

            alertEl.appendChild(alertHtml('success', LANG.phone_updated));
            document.getElementById('phone-step-verify').style.display = 'none';
            document.getElementById('phone-step-enter').style.display = 'block';
            document.getElementById('new-phone-input').value = '';
            btn.textContent = LANG.confirm;
            btn.disabled = false;
        } else {
            alertEl.appendChild(alertHtml('error', json.message || LANG.verification_failed));
            btn.textContent = LANG.confirm;
            btn.disabled = false;
        }
    } catch (err) {
        alertEl.appendChild(alertHtml('error', LANG.error_occurred));
        btn.textContent = LANG.confirm;
        btn.disabled = false;
    }
});

document.getElementById('phone-back-link').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('phone-step-verify').style.display = 'none';
    document.getElementById('phone-step-enter').style.display = 'block';
    document.getElementById('btn-send-phone-otp').textContent = LANG.send_code;
    document.getElementById('btn-send-phone-otp').disabled = false;
});

document.getElementById('phone-resend-link').addEventListener('click', async function(e) {
    e.preventDefault();
    const alertEl = document.getElementById('phone-alert');
    alertEl.replaceChildren();
    this.textContent = LANG.sending;

    try {
        const res = await fetch('/profile/phone/otp', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': PHONE_CSRF },
            body: JSON.stringify({ phone: pendingPhone })
        });
        const json = await res.json();
        if (res.ok) {
            alertEl.appendChild(alertHtml('success', LANG.new_code_sent));
            document.getElementById('phone-otp-input').value = '';
        } else {
            alertEl.appendChild(alertHtml('error', json.message || LANG.failed_send));
        }
    } catch (err) {
        alertEl.appendChild(alertHtml('error', LANG.error_occurred));
    }
    this.textContent = LANG.resend_code;
});
</script>
@endpush
