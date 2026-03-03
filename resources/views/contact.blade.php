@extends('layouts.app')

@section('title', __('contact.title'))

@push('styles')
<style>
    body { background: var(--bg-secondary); }
    .contact-page { padding: 4rem 2rem; max-width: 1200px; margin: 0 auto; }
    .contact-header { text-align: center; margin-bottom: 3rem; }
    .contact-header h1 { font-size: 2.5rem; font-weight: 700; margin-bottom: 1rem; }
    .contact-header p { color: var(--text-secondary); font-size: 1.1rem; }
    .contact-container { display: grid; grid-template-columns: 1.2fr 1fr; gap: 3rem; }
    .contact-info h2 { font-size: 2rem; font-weight: 700; margin-bottom: 1.25rem; }
    .contact-info p { color: var(--text-secondary); font-size: 1rem; line-height: 1.7; margin-bottom: 2rem; }
    .contact-info-item { display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1.5rem; }
    .contact-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0; }
    .contact-icon.email { background: rgba(212,175,55,0.1); color: var(--gold); }
    .contact-icon.phone { background: rgba(5,150,105,0.1); color: #6ee7b7; }
    .contact-icon.message { background: rgba(59,130,246,0.1); color: #60a5fa; }
    .contact-info-item strong { color: var(--text-primary); font-weight: 600; }
    .contact-info-item span { color: var(--text-secondary); font-size: 0.925rem; }
    .contact-form-wrapper { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 2.5rem; }
    .contact-form-wrapper h3 { font-size: 1.25rem; font-weight: 700; margin-bottom: 1.75rem; }
    .form-group { margin-bottom: 1.25rem; }
    .form-label { display: block; font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem; }
    .form-input { width: 100%; background: var(--bg-primary); border: 1px solid var(--border); border-radius: 10px; padding: 0.75rem 1rem; color: var(--text-primary); font-size: 0.925rem; transition: border-color 0.2s; }
    .form-input:focus { outline: none; border-color: var(--gold-muted); }
    .form-input::placeholder { color: var(--text-muted); }
    .form-textarea { width: 100%; background: var(--bg-primary); border: 1px solid var(--border); border-radius: 10px; padding: 0.75rem 1rem; color: var(--text-primary); font-size: 0.925rem; min-height: 140px; resize: vertical; font-family: 'Inter', sans-serif; }
    .form-textarea:focus { outline: none; border-color: var(--gold-muted); }
    .btn-submit { display: block; width: 100%; padding: 0.9rem 1.5rem; border-radius: 10px; font-weight: 700; font-size: 0.95rem; text-align: center; text-decoration: none; cursor: pointer; border: none; transition: all 0.2s; background: linear-gradient(135deg, var(--gold), var(--gold-light)); color: #0a0d14; }
    .btn-submit:hover { opacity: 0.9; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(212,175,55,0.35); color: #0a0d14; }
    .form-success { display: none; padding: 1.25rem; background: rgba(5,150,105,0.1); border: 1px solid rgba(5,150,105,0.3); border-radius: 12px; color: #6ee7b7; text-align: center; margin-bottom: 1.5rem; }
    @media(max-width: 900px) { .contact-container { grid-template-columns: 1fr; } }
    @media(max-width: 768px) { .contact-page { padding: 2rem 1rem; } .contact-header h1 { font-size: 1.75rem; } .contact-form-wrapper { padding: 1.75rem; } }
</style>
@endpush

@section('content')

<div class="contact-page">
    <div class="contact-header">
        <h1>{{ __('contact.need_help_title') }}</h1>
        <p>{{ __('contact.need_help_desc') }}</p>
    </div>

    <div class="contact-container">
        <div class="contact-info">
            <h2>{{ __('contact.get_in_touch') }}</h2>
            <p>{{ __('contact.contact_form_response', ['email' => 'support@resayil.io']) }}</p>

            <div class="contact-info-item">
                <div class="contact-icon email">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <strong>{{ __('contact.email') }}</strong>
                    <br>
                    <span>{{ __('contact.use_contact_form') }}</span>
                </div>
            </div>

            <div class="contact-info-item">
                <div class="contact-icon phone">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                </div>
                <div>
                    <strong>{{ __('contact.phone') }}</strong>
                    <br>
                    <span>{{ __('contact.available_on_request') }}</span>
                </div>
            </div>

            <div class="contact-info-item">
                <div class="contact-icon message">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                </div>
                <div>
                    <strong>{{ __('contact.support') }}</strong>
                    <br>
                    <span>{{ __('contact.respond_24h') }}</span>
                </div>
            </div>
        </div>

        <div class="contact-form-wrapper">
            <h3>{{ __('contact.contact_form') }}</h3>
            @if(session('success'))
            <div class="form-success" style="display:block;">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display:block;margin:0 auto 0.75rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <strong>{{ __('contact.message_sent') }}</strong> {{ __('contact.message_success', ['email' => session('success')]) }}
            </div>
            @endif
            <form method="POST" action="{{ route('contact.submit') }}">
                @csrf
                <div class="form-group">
                    <label for="full_name" class="form-label">{{ __('contact.full_name') }}</label>
                    <input type="text" id="full_name" name="full_name" class="form-input" placeholder="{{ __('contact.placeholder_full_name') }}" value="{{ old('full_name') }}" required>
                    @error('full_name')
                    <span style="color:#ef4444;font-size:0.8rem;margin-top:0.25rem;display:block">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email" class="form-label">{{ __('contact.email_address') }}</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="{{ __('contact.placeholder_email') }}" value="{{ old('email') }}" required>
                    @error('email')
                    <span style="color:#ef4444;font-size:0.8rem;margin-top:0.25rem;display:block">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="mobile" class="form-label">{{ __('contact.mobile_number') }}</label>
                    <input type="tel" id="mobile" name="mobile" class="form-input" placeholder="{{ __('contact.placeholder_mobile') }}" value="{{ old('mobile') }}" required>
                    @error('mobile')
                    <span style="color:#ef4444;font-size:0.8rem;margin-top:0.25rem;display:block">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="message" class="form-label">{{ __('contact.message') }}</label>
                    <textarea id="message" name="message" class="form-textarea" placeholder="{{ __('contact.placeholder_message') }}" required>{{ old('message') }}</textarea>
                    @error('message')
                    <span style="color:#ef4444;font-size:0.8rem;margin-top:0.25rem;display:block">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn-submit">{{ __('contact.send_message') }}</button>
            </form>
        </div>
    </div>
</div>

@endsection
