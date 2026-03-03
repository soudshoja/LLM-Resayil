@extends('layouts.app')

@section('title', __('profile.title'))

@section('content')
<main style="max-width:720px">
    <div style="margin-bottom:2rem">
        <h1 style="font-size:1.5rem;font-weight:700">{{ __('profile.title') }}</h1>
        <p class="text-secondary text-sm">{{ __('profile.subtitle') }}</p>
    </div>

    {{-- Profile Info --}}
    <div class="card mb-6">
        <h2 style="font-size:1rem;font-weight:600;margin-bottom:1.5rem">{{ __('profile.profile_info') }}</h2>

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
            <div style="display:flex;align-items:center;gap:1rem;margin-top:0.5rem">
                <button type="submit" class="btn btn-gold">{{ __('profile.save_changes') }}</button>
                <span class="text-xs text-muted">{{ __('profile.plan') }}: <span style="color:var(--gold);font-weight:600;text-transform:capitalize">{{ $user->subscription_tier ?? 'free' }}</span></span>
            </div>
        </form>
    </div>

    {{-- Phone Number --}}
    <div class="card mb-6">
        <h2 style="font-size:1rem;font-weight:600;margin-bottom:0.5rem">{{ __('profile.phone_number') }}</h2>
        <p class="text-secondary text-sm" style="margin-bottom:1.25rem">
            {{ __('profile.phone_subtitle') }}
        </p>

        <div id="phone-current" style="margin-bottom:1rem">
            <span style="font-size:0.875rem;color:var(--text-muted)">{{ __('profile.current') }}: </span>
            <span style="font-weight:600">{{ auth()->user()->phone ?? '—' }}</span>
            @if(auth()->user()->phone_verified_at)
                <span class="badge badge-green" style="margin-left:0.5rem;font-size:0.7rem">{{ __('profile.verified') }}</span>
            @else
                <span class="badge" style="margin-left:0.5rem;font-size:0.7rem;background:rgba(255,80,80,0.15);color:#ff5050">{{ __('profile.unverified') }}</span>
            @endif
        </div>

        <div id="phone-alert"></div>

        <div id="phone-step-enter">
            <form id="phoneForm" style="display:flex;gap:0.75rem;align-items:flex-end">
                <div class="form-group" style="flex:1;margin-bottom:0">
                    <label class="form-label">{{ __('profile.new_phone') }}</label>
                    <input type="tel" id="new-phone-input" name="phone" class="form-input" placeholder="96550000000" required>
                </div>
                <button type="submit" id="btn-send-phone-otp" class="btn btn-outline" style="padding:0.6rem 1.25rem;white-space:nowrap">
                    {{ __('profile.send_code') }}
                </button>
            </form>
        </div>

        <div id="phone-step-verify" style="display:none">
            <p class="text-sm text-secondary" style="margin-bottom:1rem" id="phone-verify-hint">
                {{ __('profile.enter_code_hint') }}
            </p>
            <form id="phoneVerifyForm" style="display:flex;gap:0.75rem;align-items:flex-end">
                <div class="form-group" style="flex:1;margin-bottom:0">
                    <label class="form-label">{{ __('profile.verification_code') }}</label>
                    <input type="text" id="phone-otp-input" name="otp_code" class="form-input"
                           maxlength="6" inputmode="numeric" pattern="[0-9]{6}" placeholder="------" required>
                </div>
                <button type="submit" id="btn-verify-phone" class="btn btn-gold" style="padding:0.6rem 1.25rem;white-space:nowrap">
                    {{ __('profile.confirm') }}
                </button>
            </form>
            <div style="margin-top:0.75rem;font-size:0.8rem;color:var(--text-muted)">
                <a href="#" id="phone-back-link" style="color:var(--gold)">{{ __('profile.change_number') }}</a>
                &nbsp;&middot;&nbsp;
                <a href="#" id="phone-resend-link" style="color:var(--gold)">{{ __('profile.resend_code') }}</a>
            </div>
        </div>
    </div>

    {{-- Change Password --}}
    <div class="card">
        <h2 style="font-size:1rem;font-weight:600;margin-bottom:1.5rem">{{ __('profile.change_password') }}</h2>

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
let pendingPhone = '';

document.getElementById('phoneForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-send-phone-otp');
    const alertEl = document.getElementById('phone-alert');
    alertEl.innerHTML = '';
    pendingPhone = document.getElementById('new-phone-input').value;
    btn.textContent = 'Sending...';
    btn.disabled = true;

    try {
        const res = await fetch('/profile/phone/otp', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': PHONE_CSRF },
            body: JSON.stringify({ phone: pendingPhone })
        });
        const json = await res.json();
        if (res.ok && json.step === 'verify') {
            document.getElementById('phone-verify-hint').textContent =
                'Enter the 6-digit code sent to ' + pendingPhone + '.';
            document.getElementById('phone-step-enter').style.display = 'none';
            document.getElementById('phone-step-verify').style.display = 'block';
            document.getElementById('phone-otp-input').focus();
        } else {
            alertEl.innerHTML = '<div class="alert alert-error">' + (json.message || 'Failed to send code.') + '</div>';
            btn.textContent = 'Send Code';
            btn.disabled = false;
        }
    } catch (err) {
        alertEl.innerHTML = '<div class="alert alert-error">An error occurred.</div>';
        btn.textContent = 'Send Code';
        btn.disabled = false;
    }
});

document.getElementById('phoneVerifyForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-verify-phone');
    const alertEl = document.getElementById('phone-alert');
    alertEl.innerHTML = '';
    btn.textContent = 'Verifying...';
    btn.disabled = true;

    try {
        const res = await fetch('/profile/phone/verify', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': PHONE_CSRF },
            body: JSON.stringify({ phone: pendingPhone, otp_code: document.getElementById('phone-otp-input').value })
        });
        const json = await res.json();
        if (res.ok) {
            alertEl.innerHTML = '<div class="alert alert-success">Phone number updated successfully.</div>';
            document.getElementById('phone-step-verify').style.display = 'none';
            document.getElementById('phone-step-enter').style.display = 'block';
            document.querySelector('#phone-current span:nth-child(2)').textContent = pendingPhone;
            btn.textContent = 'Confirm';
            btn.disabled = false;
        } else {
            alertEl.innerHTML = '<div class="alert alert-error">' + (json.message || 'Verification failed.') + '</div>';
            btn.textContent = 'Confirm';
            btn.disabled = false;
        }
    } catch (err) {
        alertEl.innerHTML = '<div class="alert alert-error">An error occurred.</div>';
        btn.textContent = 'Confirm';
        btn.disabled = false;
    }
});

document.getElementById('phone-back-link').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('phone-step-verify').style.display = 'none';
    document.getElementById('phone-step-enter').style.display = 'block';
    document.getElementById('btn-send-phone-otp').textContent = 'Send Code';
    document.getElementById('btn-send-phone-otp').disabled = false;
});

document.getElementById('phone-resend-link').addEventListener('click', async function(e) {
    e.preventDefault();
    const alertEl = document.getElementById('phone-alert');
    alertEl.innerHTML = '';
    this.textContent = 'Sending...';

    try {
        const res = await fetch('/profile/phone/otp', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': PHONE_CSRF },
            body: JSON.stringify({ phone: pendingPhone })
        });
        const json = await res.json();
        if (res.ok) {
            alertEl.innerHTML = '<div class="alert alert-success">A new code was sent.</div>';
            document.getElementById('phone-otp-input').value = '';
        } else {
            alertEl.innerHTML = '<div class="alert alert-error">' + (json.message || 'Failed to resend.') + '</div>';
        }
    } catch (err) {
        alertEl.innerHTML = '<div class="alert alert-error">An error occurred.</div>';
    }
    this.textContent = 'Resend code';
});
</script>
@endpush
