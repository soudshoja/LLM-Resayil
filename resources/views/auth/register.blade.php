@extends('layouts.app')

@section('title', __('auth.register_title'))

@push('styles')
<style>
    .auth-container { min-height: calc(100vh - 64px); display: flex; align-items: center; justify-content: center; padding: 2rem; }
    .auth-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 2.5rem; width: 100%; max-width: 440px; }
    .auth-title { font-size: 1.5rem; font-weight: 700; margin-bottom: 0.4rem; }
    .auth-subtitle { color: var(--text-muted); font-size: 0.875rem; margin-bottom: 1.75rem; }
    .auth-footer { text-align: center; margin-top: 1.25rem; font-size: 0.875rem; color: var(--text-muted); }
    .auth-footer a { color: var(--gold); text-decoration: none; }
    .otp-input { font-size: 2rem; text-align: center; letter-spacing: 0.5rem; font-weight: 700; }
    #step-verify { display: none; }
</style>
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div id="step-register">
            <div class="auth-title">{{ __('auth.create_account') }}</div>
            <div class="auth-subtitle">{{ __('auth.register_subtitle') }}</div>

            <div id="alert-area-register"></div>

            <form id="registerForm">
                @csrf
                <div class="form-group">
                    <label class="form-label">{{ __('auth.full_name') }}</label>
                    <input type="text" name="name" class="form-input" placeholder="{{ __('auth.placeholder_name') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('auth.email_address') }}</label>
                    <input type="email" name="email" class="form-input" placeholder="{{ __('auth.placeholder_email') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('auth.phone_number') }} <span style="color:var(--error,#ff5050)">*</span></label>
                    <input type="tel" name="phone" class="form-input" placeholder="{{ __('auth.placeholder_phone') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('auth.password') }} <span style="color:var(--error,#ff5050)">*</span></label>
                    <input type="password" name="password" class="form-input" placeholder="{{ __('auth.placeholder_password') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('auth.confirm_password') }} <span style="color:var(--error,#ff5050)">*</span></label>
                    <input type="password" name="password_confirmation" class="form-input" placeholder="{{ __('auth.placeholder_password') }}" required>
                </div>
                <button type="submit" id="btn-register" class="btn btn-gold" style="width:100%;justify-content:center;padding:0.75rem;font-size:0.95rem">
                    {{ __('auth.send_verification_code') }}
                </button>
            </form>

            <div class="auth-footer">
                {{ __('auth.already_have_account') }} <a href="/login">{{ __('auth.sign_in_here') }}</a>
            </div>
        </div>

        <div id="step-verify">
            <div class="auth-title">{{ __('auth.verify_phone') }}</div>
            <div class="auth-subtitle" id="verify-subtitle">{{ __('auth.verify_subtitle') }}</div>

            <div id="alert-area-verify"></div>

            <form id="verifyForm">
                <div class="form-group" style="margin-bottom:1.5rem">
                    <label class="form-label">{{ __('auth.verification_code') }}</label>
                    <input type="text" name="otp_code" id="otp-input" class="form-input otp-input"
                           maxlength="6" inputmode="numeric" pattern="[0-9]{6}" placeholder="------" required>
                </div>
                <button type="submit" id="btn-verify" class="btn btn-gold" style="width:100%;justify-content:center;padding:0.75rem;font-size:0.95rem">
                    {{ __('auth.create_account_button') }}
                </button>
            </form>

            <div class="auth-footer" style="margin-top:1rem">
                {{ __('auth.wrong_number') }} <a href="#" id="btn-back">{{ __('auth.go_back') }}</a>
                &nbsp;&middot;&nbsp;
                <a href="#" id="btn-resend">{{ __('auth.resend_code') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const CSRF = '{{ csrf_token() }}';
let formData = {};

document.getElementById('registerForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = e.target;
    const btn = document.getElementById('btn-register');
    const alertArea = document.getElementById('alert-area-register');
    alertArea.innerHTML = '';
    btn.textContent = '{{ __('auth.sending_code') }}';
    btn.disabled = true;

    formData = {
        name: form.name.value,
        email: form.email.value,
        phone: form.phone.value,
        password: form.password.value,
        password_confirmation: form.password_confirmation.value,
    };

    try {
        const res = await fetch('/register/otp', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify(formData)
        });
        const json = await res.json();
        if (res.ok && json.step === 'verify') {
            document.getElementById('verify-subtitle').textContent =
                '{{ __('auth.enter_verification_code') }} ' + formData.phone + '.';
            document.getElementById('step-register').style.display = 'none';
            document.getElementById('step-verify').style.display = 'block';
            document.getElementById('otp-input').focus();
        } else {
            const msgs = json.errors ? Object.values(json.errors).flat().join('<br>') : (json.message || '{{ __('auth.failed_to_send') }}');
            alertArea.innerHTML = '<div class="alert alert-error">' + msgs + '</div>';
            btn.textContent = '{{ __('auth.send_verification_code') }}';
            btn.disabled = false;
        }
    } catch (err) {
        alertArea.innerHTML = '<div class="alert alert-error">{{ __('auth.an_error_occurred') }}</div>';
        btn.textContent = '{{ __('auth.send_verification_code') }}';
        btn.disabled = false;
    }
});

document.getElementById('verifyForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-verify');
    const alertArea = document.getElementById('alert-area-verify');
    alertArea.innerHTML = '';
    btn.textContent = '{{ __('auth.creating_account') }}';
    btn.disabled = true;

    const payload = Object.assign({}, formData, { otp_code: document.getElementById('otp-input').value });

    try {
        const res = await fetch('/register', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify(payload)
        });
        const json = await res.json();
        if (res.ok || res.status === 201) {
            window.location.href = '/dashboard';
        } else {
            const msgs = json.errors ? Object.values(json.errors).flat().join('<br>') : (json.message || '{{ __('auth.verification_failed') }}');
            alertArea.innerHTML = '<div class="alert alert-error">' + msgs + '</div>';
            btn.textContent = '{{ __('auth.create_account_button') }}';
            btn.disabled = false;
        }
    } catch (err) {
        alertArea.innerHTML = '<div class="alert alert-error">{{ __('auth.an_error_occurred') }}</div>';
        btn.textContent = '{{ __('auth.create_account_button') }}';
        btn.disabled = false;
    }
});

document.getElementById('btn-back').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('step-verify').style.display = 'none';
    document.getElementById('step-register').style.display = 'block';
    document.getElementById('btn-register').textContent = '{{ __('auth.send_verification_code') }}';
    document.getElementById('btn-register').disabled = false;
});

document.getElementById('btn-resend').addEventListener('click', async function(e) {
    e.preventDefault();
    const alertArea = document.getElementById('alert-area-verify');
    alertArea.innerHTML = '';
    this.textContent = '{{ __('auth.resending') }}';

    try {
        const res = await fetch('/register/otp', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify(formData)
        });
        const json = await res.json();
        if (res.ok && json.step === 'verify') {
            alertArea.innerHTML = '<div class="alert alert-success">{{ __('auth.code_sent') }}</div>';
            document.getElementById('otp-input').value = '';
        } else {
            alertArea.innerHTML = '<div class="alert alert-error">' + (json.message || '{{ __('auth.failed_to_send') }}') + '</div>';
        }
    } catch (err) {
        alertArea.innerHTML = '<div class="alert alert-error">{{ __('auth.an_error_occurred') }}</div>';
    }
    this.textContent = '{{ __('auth.resend_code') }}';
});
</script>
@endpush
