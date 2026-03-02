@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<main style="max-width:720px">
    <div style="margin-bottom:2rem">
        <h1 style="font-size:1.5rem;font-weight:700">My Profile</h1>
        <p class="text-secondary text-sm">Update your name, email, and password</p>
    </div>

    {{-- Profile Info --}}
    <div class="card mb-6">
        <h2 style="font-size:1rem;font-weight:600;margin-bottom:1.5rem">Profile Information</h2>

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
                <label class="form-label">Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input" required>
            </div>
            <div style="display:flex;align-items:center;gap:1rem;margin-top:0.5rem">
                <button type="submit" class="btn btn-gold">Save Changes</button>
                <span class="text-xs text-muted">Plan: <span style="color:var(--gold);font-weight:600;text-transform:capitalize">{{ $user->subscription_tier ?? 'free' }}</span></span>
            </div>
        </form>
    </div>

    {{-- Change Password --}}
    <div class="card">
        <h2 style="font-size:1rem;font-weight:600;margin-bottom:1.5rem">Change Password</h2>

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
                <label class="form-label">Current Password</label>
                <input type="password" name="current_password" class="form-input" required autocomplete="current-password">
            </div>
            <div class="form-group">
                <label class="form-label">New Password</label>
                <input type="password" name="password" class="form-input" required autocomplete="new-password">
            </div>
            <div class="form-group">
                <label class="form-label">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="form-input" required autocomplete="new-password">
            </div>
            <button type="submit" class="btn btn-outline" style="margin-top:0.5rem">Update Password</button>
        </form>
    </div>
</main>
@endsection
