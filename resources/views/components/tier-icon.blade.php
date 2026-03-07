{{--
    Tier Icon Component
    Usage: <x-tier-icon tier="free|starter|basic|pro|enterprise" size="24" />
    Matches the dark luxury design system: bg #0f1115, gold #d4af37
--}}
@php
    $size  = $size  ?? 24;
    $tier  = strtolower($tier ?? 'free');
@endphp

@if($tier === 'free')
{{-- Seedling / open sprout — muted gray --}}
<svg class="tier-svg-icon" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="Free tier" role="img">
    <circle cx="16" cy="16" r="13" stroke="#6b7280" stroke-width="1.5" stroke-dasharray="3 2"/>
    <path d="M16 23v-8" stroke="#6b7280" stroke-width="1.5" stroke-linecap="round"/>
    <path d="M16 15c0 0-3-3-3-6 0-1.5 1.5-2.5 3-2.5s3 1 3 2.5c0 3-3 6-3 6z" fill="#6b7280" fill-opacity="0.25" stroke="#6b7280" stroke-width="1.5" stroke-linejoin="round"/>
    <path d="M16 18c0 0-3.5 1-5 3" stroke="#6b7280" stroke-width="1.2" stroke-linecap="round"/>
</svg>

@elseif($tier === 'starter')
{{-- Lightning bolt — blue --}}
<svg class="tier-svg-icon" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="Starter tier" role="img">
    <circle cx="16" cy="16" r="13" fill="#60a5fa" fill-opacity="0.08" stroke="#60a5fa" stroke-width="1.5"/>
    <path d="M18 7l-7 10h6l-3 8 8-11h-6l2-7z" fill="#60a5fa" fill-opacity="0.3" stroke="#60a5fa" stroke-width="1.4" stroke-linejoin="round" stroke-linecap="round"/>
</svg>

@elseif($tier === 'basic')
{{-- Shield with inner star — purple --}}
<svg class="tier-svg-icon" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="Basic tier" role="img">
    <circle cx="16" cy="16" r="13" fill="#a78bfa" fill-opacity="0.08" stroke="#a78bfa" stroke-width="1.5"/>
    <path d="M16 7l5.5 2.5v5c0 3.5-2.5 6-5.5 7.5C13 20.5 10.5 18 10.5 14.5v-5L16 7z" fill="#a78bfa" fill-opacity="0.2" stroke="#a78bfa" stroke-width="1.4" stroke-linejoin="round"/>
    <path d="M16 12l.9 2h2l-1.6 1.2.6 2L16 16.1l-1.9 1.1.6-2L13.1 14h2L16 12z" fill="#a78bfa" stroke="none"/>
</svg>

@elseif($tier === 'pro')
{{-- Crown — gold --}}
<svg class="tier-svg-icon" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="Pro tier" role="img">
    <circle cx="16" cy="16" r="13" fill="#d4af37" fill-opacity="0.1" stroke="#d4af37" stroke-width="1.5"/>
    <path d="M8 21h16" stroke="#d4af37" stroke-width="1.5" stroke-linecap="round"/>
    <path d="M8 21l2-8 4.5 4L16 11l1.5 6L22 13l2 8H8z" fill="#d4af37" fill-opacity="0.25" stroke="#d4af37" stroke-width="1.4" stroke-linejoin="round" stroke-linecap="round"/>
    <circle cx="8"  cy="13" r="1.2" fill="#d4af37"/>
    <circle cx="16" cy="11" r="1.2" fill="#d4af37"/>
    <circle cx="24" cy="13" r="1.2" fill="#d4af37"/>
</svg>

@elseif($tier === 'enterprise')
{{-- Fortress / double-crown building — red/premium --}}
<svg class="tier-svg-icon" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="Enterprise tier" role="img">
    <circle cx="16" cy="16" r="13" fill="#ef4444" fill-opacity="0.08" stroke="#ef4444" stroke-width="1.5"/>
    <rect x="9"  y="14" width="14" height="9" rx="1" fill="#ef4444" fill-opacity="0.2" stroke="#ef4444" stroke-width="1.3"/>
    <path d="M9 14V11l2.5 2 2.5-3 2 3 2.5-3 2.5 2v3" fill="#ef4444" fill-opacity="0.3" stroke="#ef4444" stroke-width="1.3" stroke-linejoin="round"/>
    <rect x="13.5" y="18" width="5" height="5" rx="0.5" fill="#ef4444" fill-opacity="0.4" stroke="#ef4444" stroke-width="1.1"/>
    <line x1="16" y1="14" x2="16" y2="23" stroke="#ef4444" stroke-width="0.8" stroke-opacity="0.5"/>
</svg>

@else
{{-- Fallback: generic circle --}}
<svg class="tier-svg-icon" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="{{ ucfirst($tier) }} tier" role="img">
    <circle cx="16" cy="16" r="13" stroke="#6b7280" stroke-width="1.5"/>
</svg>
@endif
