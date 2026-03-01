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
</style>
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-title">Create Account</div>
        <div class="auth-subtitle">Start accessing powerful LLMs today</div>

        <div id="alert-area"></div>

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
                <label class="form-label">Phone Number <span style="color:var(--error)">*</span></label>
                <input type="tel" name="phone" class="form-input" placeholder="96550000000" required>
            </div>
            <div class="form-group">
                <label class="form-label">Password <span style="color:var(--error)">*</span></label>
                <input type="password" name="password" class="form-input" placeholder="Min. 8 characters" required>
            </div>
            <div class="form-group">
                <label class="form-label">Confirm Password <span style="color:var(--error)">*</span></label>
                <input type="password" name="password_confirmation" class="form-input" placeholder="Repeat password" required>
            </div>
            <button type="submit" class="btn btn-gold" style="width:100%;justify-content:center;padding:0.75rem;font-size:0.95rem">Create Account</button>
        </form>

        <div class="auth-footer">
            Already have an account? <a href="/login">Sign in</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('registerForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = e.target;
    const btn = form.querySelector('button[type="submit"]');
    const alertArea = document.getElementById('alert-area');
    alertArea.innerHTML = '';
    btn.textContent = 'Creating account...';
    btn.disabled = true;

    const data = {
        name: form.name.value,
        email: form.email.value,
        phone: form.phone.value,
        password: form.password.value,
        password_confirmation: form.password_confirmation.value,
    };

    try {
        const res = await fetch('/register', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify(data)
        });
        const json = await res.json();
        if (res.ok || res.status === 201) {
            window.location.href = '/dashboard';
        } else {
            const msgs = json.errors ? Object.values(json.errors).flat().join('<br>') : (json.message || 'Registration failed.');
            alertArea.innerHTML = `<div class="alert alert-error">${msgs}</div>`;
            btn.textContent = 'Create Account';
            btn.disabled = false;
        }
    } catch(err) {
        alertArea.innerHTML = '<div class="alert alert-error">An error occurred. Please try again.</div>';
        btn.textContent = 'Create Account';
        btn.disabled = false;
    }
});
</script>
@endpush
