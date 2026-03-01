@extends('layouts.app')

@section('title', 'Login')

@push('styles')
<style>
    .auth-container { min-height: calc(100vh - 64px); display: flex; align-items: center; justify-content: center; padding: 2rem; }
    .auth-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 2.5rem; width: 100%; max-width: 420px; }
    .auth-title { font-size: 1.5rem; font-weight: 700; margin-bottom: 0.4rem; }
    .auth-subtitle { color: var(--text-muted); font-size: 0.875rem; margin-bottom: 1.75rem; }
    .auth-footer { text-align: center; margin-top: 1.25rem; font-size: 0.875rem; color: var(--text-muted); }
    .auth-footer a { color: var(--gold); text-decoration: none; }
</style>
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-title">Welcome back</div>
        <div class="auth-subtitle">Sign in to your LLM Resayil account</div>

        @if($errors->any())
        <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="/login" id="loginForm">
            @csrf
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-input" placeholder="you@example.com" value="{{ old('email') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-input" placeholder="••••••••" required>
            </div>
            <div class="form-group" style="display:flex;align-items:center;gap:0.5rem">
                <input type="checkbox" name="remember" id="remember" style="accent-color:var(--gold)">
                <label for="remember" style="font-size:0.875rem;color:var(--text-secondary);cursor:pointer">Remember me</label>
            </div>
            <button type="submit" class="btn btn-gold" style="width:100%;justify-content:center;padding:0.75rem;font-size:0.95rem">Sign In</button>
        </form>

        <div class="auth-footer">
            Don't have an account? <a href="/register">Register here</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = e.target;
    const btn = form.querySelector('button[type="submit"]');
    btn.textContent = 'Signing in...';
    btn.disabled = true;

    const data = {
        email: form.email.value,
        password: form.password.value,
        remember: form.remember.checked ? 1 : 0,
        _token: form._token.value
    };

    try {
        const res = await fetch('/login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': data._token },
            body: JSON.stringify(data)
        });
        const json = await res.json();
        if (res.ok) {
            window.location.href = '/dashboard';
        } else {
            document.querySelector('.auth-card').insertAdjacentHTML('afterbegin',
                `<div class="alert alert-error">${json.message || 'Login failed.'}</div>`);
            btn.textContent = 'Sign In';
            btn.disabled = false;
        }
    } catch(err) {
        btn.textContent = 'Sign In';
        btn.disabled = false;
    }
});
</script>
@endpush
