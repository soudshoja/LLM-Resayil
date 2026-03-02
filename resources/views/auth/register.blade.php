@extends('layouts.app')

@section('title', 'Register')

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
            <div class="auth-title">Create Account</div>
            <div class="auth-subtitle">Start accessing powerful LLMs today</div>

            <div id="alert-area-register"></div>

            <form id="registerForm">
                @csrf
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-input" placeholder="Ahmad Al-Rashidi">
                </div>
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-input" placeholder="you@example.com">
                </div>
                <div class="form-group">
                    <label class="form-label">Phone Number <span style="color:var(--error,#ff5050)">*</span></label>
                    <input type="tel" name="phone" class="form-input" placeholder="96550000000" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password <span style="color:var(--error,#ff5050)">*</span></label>
                    <input type="password" name="password" class="form-input" placeholder="Min. 8 characters" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Confirm Password <span style="color:var(--error,#ff5050)">*</span></label>
                    <input type="password" name="password_confirmation" class="form-input" placeholder="Repeat password" required>
                </div>
                <button type="submit" id="btn-register" class="btn btn-gold" style="width:100%;justify-content:center;padding:0.75rem;font-size:0.95rem">
                    Send Verification Code
                </button>
            </form>

            <div class="auth-footer">
                Already have an account? <a href="/login">Sign in</a>
            </div>
        </div>

        <div id="step-verify">
            <div class="auth-title">Verify Phone</div>
            <div class="auth-subtitle" id="verify-subtitle">Enter the 6-digit code sent to your phone.</div>

            <div id="alert-area-verify"></div>

            <form id="verifyForm">
                <div class="form-group" style="margin-bottom:1.5rem">
                    <label class="form-label">Verification Code</label>
                    <input type="text" name="otp_code" id="otp-input" class="form-input otp-input"
                           maxlength="6" inputmode="numeric" pattern="[0-9]{6}" placeholder="------" required>
                </div>
                <button type="submit" id="btn-verify" class="btn btn-gold" style="width:100%;justify-content:center;padding:0.75rem;font-size:0.95rem">
                    Create Account
                </button>
            </form>

            <div class="auth-footer" style="margin-top:1rem">
                Wrong number? <a href="#" id="btn-back">Go back</a>
                &nbsp;&middot;&nbsp;
                <a href="#" id="btn-resend">Resend code</a>
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
    btn.textContent = 'Sending code...';
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
                'Enter the 6-digit code sent to ' + formData.phone + '.';
            document.getElementById('step-register').style.display = 'none';
            document.getElementById('step-verify').style.display = 'block';
            document.getElementById('otp-input').focus();
        } else {
            const msgs = json.errors ? Object.values(json.errors).flat().join('<br>') : (json.message || 'Failed to send code.');
            alertArea.innerHTML = '<div class="alert alert-error">' + msgs + '</div>';
            btn.textContent = 'Send Verification Code';
            btn.disabled = false;
        }
    } catch (err) {
        alertArea.innerHTML = '<div class="alert alert-error">An error occurred. Please try again.</div>';
        btn.textContent = 'Send Verification Code';
        btn.disabled = false;
    }
});

document.getElementById('verifyForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-verify');
    const alertArea = document.getElementById('alert-area-verify');
    alertArea.innerHTML = '';
    btn.textContent = 'Verifying...';
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
            const msgs = json.errors ? Object.values(json.errors).flat().join('<br>') : (json.message || 'Verification failed.');
            alertArea.innerHTML = '<div class="alert alert-error">' + msgs + '</div>';
            btn.textContent = 'Create Account';
            btn.disabled = false;
        }
    } catch (err) {
        alertArea.innerHTML = '<div class="alert alert-error">An error occurred. Please try again.</div>';
        btn.textContent = 'Create Account';
        btn.disabled = false;
    }
});

document.getElementById('btn-back').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('step-verify').style.display = 'none';
    document.getElementById('step-register').style.display = 'block';
    document.getElementById('btn-register').textContent = 'Send Verification Code';
    document.getElementById('btn-register').disabled = false;
});

document.getElementById('btn-resend').addEventListener('click', async function(e) {
    e.preventDefault();
    const alertArea = document.getElementById('alert-area-verify');
    alertArea.innerHTML = '';
    this.textContent = 'Sending...';

    try {
        const res = await fetch('/register/otp', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify(formData)
        });
        const json = await res.json();
        if (res.ok && json.step === 'verify') {
            alertArea.innerHTML = '<div class="alert alert-success">A new code has been sent.</div>';
            document.getElementById('otp-input').value = '';
        } else {
            alertArea.innerHTML = '<div class="alert alert-error">' + (json.message || 'Failed to resend.') + '</div>';
        }
    } catch (err) {
        alertArea.innerHTML = '<div class="alert alert-error">An error occurred.</div>';
    }
    this.textContent = 'Resend code';
});
</script>
@endpush
