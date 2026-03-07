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
{{-- Open ring with centered dot — muted gray --}}
<svg class="tier-svg-icon" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="Free tier" role="img">
    <circle cx="16" cy="16" r="12" stroke="#6b7280" stroke-width="1.5" fill="#6b7280" fill-opacity="0.07"/>
    <circle cx="16" cy="16" r="3.5" stroke="#6b7280" stroke-width="1.5" fill="#6b7280" fill-opacity="0.15"/>
    <circle cx="16" cy="16" r="1.2" fill="#6b7280"/>
</svg>

@elseif($tier === 'starter')
{{-- Single upward lightning bolt, clean geometric — blue --}}
<svg class="tier-svg-icon" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="Starter tier" role="img">
    <circle cx="16" cy="16" r="12" stroke="#60a5fa" stroke-width="1.5" fill="#60a5fa" fill-opacity="0.08"/>
    <path d="M18.5 7.5L12 16.5h5.5L13.5 24.5l8.5-11H16.5L18.5 7.5z" fill="#60a5fa" fill-opacity="0.2" stroke="#60a5fa" stroke-width="1.5" stroke-linejoin="round" stroke-linecap="round"/>
</svg>

@elseif($tier === 'basic')
{{-- Hexagon outline with small star inside — purple --}}
<svg class="tier-svg-icon" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="Basic tier" role="img">
    <circle cx="16" cy="16" r="12" stroke="#a78bfa" stroke-width="1.5" fill="#a78bfa" fill-opacity="0.07"/>
    <path d="M16 7.5l4.33 2.5v5L16 17.5l-4.33-2.5v-5L16 7.5z" fill="#a78bfa" fill-opacity="0.15" stroke="#a78bfa" stroke-width="1.5" stroke-linejoin="round"/>
    <path d="M16 11.5l.9 1.8 2 .3-1.45 1.4.34 2L16 16.05l-1.79.95.34-2L13.1 13.6l2-.3L16 11.5z" fill="#a78bfa" fill-opacity="0.9" stroke="none"/>
</svg>

@elseif($tier === 'pro')
{{-- Clean 5-point crown, elegant proportions — gold --}}
<svg class="tier-svg-icon" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="Pro tier" role="img">
    <circle cx="16" cy="16" r="12" stroke="#d4af37" stroke-width="1.5" fill="#d4af37" fill-opacity="0.1"/>
    <path d="M8.5 22h15" stroke="#d4af37" stroke-width="1.5" stroke-linecap="round"/>
    <path d="M8.5 22l2.5-9 4 4L16 11l1 6 4-4 2.5 9H8.5z" fill="#d4af37" fill-opacity="0.2" stroke="#d4af37" stroke-width="1.5" stroke-linejoin="round" stroke-linecap="round"/>
    <circle cx="8.5"  cy="13" r="1.3" fill="#d4af37"/>
    <circle cx="16"   cy="11" r="1.3" fill="#d4af37"/>
    <circle cx="23.5" cy="13" r="1.3" fill="#d4af37"/>
</svg>

@elseif($tier === 'enterprise')
{{-- Double-ring with diamond center — red/premium --}}
<svg class="tier-svg-icon" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="Enterprise tier" role="img">
    <circle cx="16" cy="16" r="12" stroke="#ef4444" stroke-width="1.5" fill="#ef4444" fill-opacity="0.08"/>
    <circle cx="16" cy="16" r="7.5" stroke="#ef4444" stroke-width="1" fill="#ef4444" fill-opacity="0.06" stroke-dasharray="2.5 1.5"/>
    <path d="M16 9.5l3.5 6.5-3.5 6.5-3.5-6.5L16 9.5z" fill="#ef4444" fill-opacity="0.25" stroke="#ef4444" stroke-width="1.5" stroke-linejoin="round"/>
    <path d="M9.5 16l6.5-3.5 6.5 3.5-6.5 3.5L9.5 16z" fill="#ef4444" fill-opacity="0.15" stroke="#ef4444" stroke-width="1.5" stroke-linejoin="round"/>
    <circle cx="16" cy="16" r="1.75" fill="#ef4444" fill-opacity="0.9"/>
</svg>

@else
{{-- Fallback: generic circle --}}
<svg class="tier-svg-icon" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="{{ ucfirst($tier) }} tier" role="img">
    <circle cx="16" cy="16" r="12" stroke="#6b7280" stroke-width="1.5"/>
</svg>
@endif
