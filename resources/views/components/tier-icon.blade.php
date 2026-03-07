{{--
    Tier Icon Component
    Usage: <x-tier-icon tier="free|starter|basic|pro|enterprise" size="24" />
    Matches the dark luxury design system: bg #0f1115, gold #d4af37
    Design language: UI Pro Max — Retro-Futurism × Aurora Gradient
--}}
@php
    $size  = $size  ?? 24;
    $tier  = strtolower($tier ?? 'free');
@endphp

@if($tier === 'free')
{{-- Atom Orbit — silver/slate glow --}}
<svg class="tier-svg-icon" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="Free tier" role="img">
  <defs>
    <radialGradient id="free-center" cx="50%" cy="50%" r="50%">
      <stop offset="0%" stop-color="#e2e8f0"/>
      <stop offset="100%" stop-color="#64748b"/>
    </radialGradient>
    <linearGradient id="free-ring" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#94a3b8"/>
      <stop offset="100%" stop-color="#475569"/>
    </linearGradient>
  </defs>
  <ellipse cx="16" cy="16" rx="13" ry="5.5" stroke="url(#free-ring)" stroke-width="1.5" fill="none" style="filter:drop-shadow(0 0 3px #64748b)"/>
  <ellipse cx="16" cy="16" rx="13" ry="5.5" stroke="url(#free-ring)" stroke-width="1.5" fill="none" transform="rotate(60 16 16)" opacity="0.7"/>
  <ellipse cx="16" cy="16" rx="13" ry="5.5" stroke="url(#free-ring)" stroke-width="1.5" fill="none" transform="rotate(120 16 16)" opacity="0.5"/>
  <circle cx="16" cy="16" r="3" fill="url(#free-center)" style="filter:drop-shadow(0 0 4px #94a3b8)"/>
</svg>

@elseif($tier === 'starter')
{{-- Electric Bolt — electric blue neon --}}
<svg class="tier-svg-icon" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="Starter tier" role="img">
  <defs>
    <linearGradient id="starter-bg" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#1e3a5f" stop-opacity="0.6"/>
      <stop offset="100%" stop-color="#0c1a2e" stop-opacity="0.3"/>
    </linearGradient>
    <linearGradient id="starter-bolt" x1="0%" y1="0%" x2="30%" y2="100%">
      <stop offset="0%" stop-color="#93c5fd"/>
      <stop offset="50%" stop-color="#3b82f6"/>
      <stop offset="100%" stop-color="#1d4ed8"/>
    </linearGradient>
  </defs>
  <circle cx="16" cy="16" r="13" fill="url(#starter-bg)" stroke="#3b82f6" stroke-width="1" stroke-opacity="0.4"/>
  <path d="M19 5L11 17h7L12 27l10-13h-6.5L19 5z" fill="url(#starter-bolt)" style="filter:drop-shadow(0 0 6px #60a5fa)"/>
  <path d="M19 5L11 17h7L12 27l10-13h-6.5L19 5z" fill="none" stroke="#93c5fd" stroke-width="0.5" stroke-opacity="0.6"/>
</svg>

@elseif($tier === 'basic')
{{-- Aurora Prism — purple aurora gradient --}}
<svg class="tier-svg-icon" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="Basic tier" role="img">
  <defs>
    <linearGradient id="basic-bg" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#2d1b69" stop-opacity="0.5"/>
      <stop offset="100%" stop-color="#1e1040" stop-opacity="0.3"/>
    </linearGradient>
    <linearGradient id="basic-gem" x1="0%" y1="0%" x2="60%" y2="100%">
      <stop offset="0%" stop-color="#c4b5fd"/>
      <stop offset="40%" stop-color="#8b5cf6"/>
      <stop offset="100%" stop-color="#5b21b6"/>
    </linearGradient>
    <linearGradient id="basic-shine" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#ffffff" stop-opacity="0.4"/>
      <stop offset="100%" stop-color="#ffffff" stop-opacity="0"/>
    </linearGradient>
  </defs>
  <circle cx="16" cy="16" r="13" fill="url(#basic-bg)" stroke="#8b5cf6" stroke-width="1" stroke-opacity="0.5"/>
  <path d="M16 5l6 6-2 10-4 4-4-4-2-10z" fill="url(#basic-gem)" style="filter:drop-shadow(0 0 7px #a78bfa)"/>
  <path d="M10 11h12" stroke="#c4b5fd" stroke-width="0.8" stroke-opacity="0.5"/>
  <path d="M16 5l6 6" stroke="url(#basic-shine)" stroke-width="1.5" stroke-linecap="round"/>
</svg>

@elseif($tier === 'pro')
{{-- Gold Crown Radiant — gold gradient + burst rays --}}
<svg class="tier-svg-icon" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="Pro tier" role="img">
  <defs>
    <radialGradient id="pro-bg" cx="50%" cy="40%" r="60%">
      <stop offset="0%" stop-color="#3d2c00" stop-opacity="0.6"/>
      <stop offset="100%" stop-color="#1a1200" stop-opacity="0.2"/>
    </radialGradient>
    <linearGradient id="pro-crown" x1="0%" y1="0%" x2="50%" y2="100%">
      <stop offset="0%" stop-color="#fef08a"/>
      <stop offset="40%" stop-color="#d4af37"/>
      <stop offset="100%" stop-color="#92700a"/>
    </linearGradient>
    <radialGradient id="pro-glow" cx="50%" cy="50%" r="50%">
      <stop offset="0%" stop-color="#fbbf24" stop-opacity="0.3"/>
      <stop offset="100%" stop-color="#d4af37" stop-opacity="0"/>
    </radialGradient>
  </defs>
  <circle cx="16" cy="16" r="13" fill="url(#pro-bg)" stroke="#d4af37" stroke-width="1" stroke-opacity="0.5"/>
  <circle cx="16" cy="16" r="13" fill="url(#pro-glow)"/>
  <!-- Rays -->
  <line x1="16" y1="3" x2="16" y2="6" stroke="#fbbf24" stroke-width="1" stroke-opacity="0.5"/>
  <line x1="24" y1="6" x2="22" y2="8.5" stroke="#fbbf24" stroke-width="1" stroke-opacity="0.4"/>
  <line x1="28" y1="14" x2="25" y2="14.5" stroke="#fbbf24" stroke-width="1" stroke-opacity="0.3"/>
  <!-- Crown -->
  <path d="M8.5 22h15" stroke="#d4af37" stroke-width="1.8" stroke-linecap="round"/>
  <path d="M8.5 22l2.5-9 4.5 4.5L16 10.5l1.5 7 4.5-4.5 2.5 9H8.5z" fill="url(#pro-crown)" style="filter:drop-shadow(0 0 6px #d4af37)"/>
  <!-- Tips -->
  <circle cx="8.5" cy="13" r="1.5" fill="#fef08a"/>
  <circle cx="16" cy="10.5" r="1.5" fill="#fef08a"/>
  <circle cx="23.5" cy="13" r="1.5" fill="#fef08a"/>
</svg>

@elseif($tier === 'enterprise')
{{-- Shield Diamond — crimson gradient + inner facets --}}
<svg class="tier-svg-icon" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="Enterprise tier" role="img">
  <defs>
    <linearGradient id="ent-bg" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#450a0a" stop-opacity="0.6"/>
      <stop offset="100%" stop-color="#1a0505" stop-opacity="0.3"/>
    </linearGradient>
    <linearGradient id="ent-shield" x1="20%" y1="0%" x2="80%" y2="100%">
      <stop offset="0%" stop-color="#fca5a5"/>
      <stop offset="40%" stop-color="#ef4444"/>
      <stop offset="100%" stop-color="#7f1d1d"/>
    </linearGradient>
    <linearGradient id="ent-shine" x1="0%" y1="0%" x2="100%" y2="80%">
      <stop offset="0%" stop-color="#ffffff" stop-opacity="0.35"/>
      <stop offset="100%" stop-color="#ffffff" stop-opacity="0"/>
    </linearGradient>
  </defs>
  <circle cx="16" cy="16" r="13" fill="url(#ent-bg)" stroke="#ef4444" stroke-width="1" stroke-opacity="0.5"/>
  <!-- Outer shield -->
  <path d="M16 5l9 4v7c0 5-4 8-9 10C11 24 7 21 7 16V9z" fill="url(#ent-shield)" style="filter:drop-shadow(0 0 8px #ef4444)"/>
  <path d="M16 5l9 4v7c0 5-4 8-9 10C11 24 7 21 7 16V9z" fill="url(#ent-shine)"/>
  <!-- Inner diamond -->
  <path d="M16 10l3.5 5.5-3.5 5.5-3.5-5.5z" fill="#fca5a5" fill-opacity="0.6" stroke="#fff" stroke-width="0.5" stroke-opacity="0.4"/>
</svg>

@else
{{-- Fallback: generic circle --}}
<svg class="tier-svg-icon" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="{{ ucfirst($tier) }} tier" role="img">
    <circle cx="16" cy="16" r="12" stroke="#6b7280" stroke-width="1.5"/>
</svg>
@endif
