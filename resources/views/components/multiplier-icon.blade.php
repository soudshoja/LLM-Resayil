{{--
    Multiplier Icon Component
    Usage: <x-multiplier-icon multiplier="0.5|1.0|1.5|2.5|3.5" size="20" />
    Represents the 5 credit multiplier tiers in the LLM Resayil system
--}}
@php
    $size = $size ?? 20;
    $m    = (string)($multiplier ?? '1.0');
@endphp

@if($m === '0.5')
{{-- 0.5× Standard Lightweight — "Comet" — slate/sky, fast and light --}}
<svg class="mult-svg-icon" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="0.5× Standard Lightweight" role="img">
  <defs>
    <linearGradient id="c05-tail" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#38bdf8" stop-opacity="0"/>
      <stop offset="100%" stop-color="#94a3b8" stop-opacity="0.5"/>
    </linearGradient>
    <radialGradient id="c05-core" cx="65%" cy="35%" r="50%">
      <stop offset="0%" stop-color="#e0f2fe"/>
      <stop offset="60%" stop-color="#38bdf8"/>
      <stop offset="100%" stop-color="#0284c7"/>
    </radialGradient>
  </defs>
  <circle cx="16" cy="16" r="13" fill="#0c1a2e" fill-opacity="0.4" stroke="#38bdf8" stroke-width="0.75" stroke-opacity="0.3"/>
  <!-- Comet tail streaks -->
  <path d="M6 22 Q10 16 20 12" stroke="url(#c05-tail)" stroke-width="3" stroke-linecap="round"/>
  <path d="M4 26 Q9 20 18 15" stroke="url(#c05-tail)" stroke-width="1.5" stroke-linecap="round" opacity="0.5"/>
  <path d="M8 28 Q12 22 20 17" stroke="url(#c05-tail)" stroke-width="1" stroke-linecap="round" opacity="0.3"/>
  <!-- Comet core -->
  <circle cx="21" cy="11" r="4" fill="url(#c05-core)" style="filter:drop-shadow(0 0 5px #38bdf8)"/>
  <circle cx="21" cy="11" r="1.5" fill="#e0f2fe" fill-opacity="0.9"/>
</svg>

@elseif($m === '1.0')
{{-- 1.0× Frontier Embedding — "Neural Node" — cyan/teal, vector network --}}
<svg class="mult-svg-icon" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="1.0× Frontier Embedding" role="img">
  <defs>
    <radialGradient id="c10-center" cx="50%" cy="50%" r="50%">
      <stop offset="0%" stop-color="#99f6e4"/>
      <stop offset="100%" stop-color="#0d9488"/>
    </radialGradient>
    <linearGradient id="c10-edge" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#2dd4bf"/>
      <stop offset="100%" stop-color="#0f766e"/>
    </linearGradient>
  </defs>
  <circle cx="16" cy="16" r="13" fill="#021f1c" fill-opacity="0.5" stroke="#2dd4bf" stroke-width="0.75" stroke-opacity="0.35"/>
  <!-- Connection lines -->
  <line x1="16" y1="16" x2="8"  y2="9"  stroke="#2dd4bf" stroke-width="1" stroke-opacity="0.5"/>
  <line x1="16" y1="16" x2="24" y2="9"  stroke="#2dd4bf" stroke-width="1" stroke-opacity="0.5"/>
  <line x1="16" y1="16" x2="8"  y2="23" stroke="#2dd4bf" stroke-width="1" stroke-opacity="0.5"/>
  <line x1="16" y1="16" x2="24" y2="23" stroke="#2dd4bf" stroke-width="1" stroke-opacity="0.5"/>
  <line x1="16" y1="16" x2="16" y2="6"  stroke="#2dd4bf" stroke-width="1" stroke-opacity="0.4"/>
  <line x1="16" y1="16" x2="16" y2="26" stroke="#2dd4bf" stroke-width="1" stroke-opacity="0.4"/>
  <!-- Outer nodes -->
  <circle cx="8"  cy="9"  r="2.2" fill="url(#c10-edge)" style="filter:drop-shadow(0 0 3px #2dd4bf)"/>
  <circle cx="24" cy="9"  r="2.2" fill="url(#c10-edge)" style="filter:drop-shadow(0 0 3px #2dd4bf)"/>
  <circle cx="8"  cy="23" r="2.2" fill="url(#c10-edge)" style="filter:drop-shadow(0 0 3px #2dd4bf)"/>
  <circle cx="24" cy="23" r="2.2" fill="url(#c10-edge)" style="filter:drop-shadow(0 0 3px #2dd4bf)"/>
  <circle cx="16" cy="6"  r="1.8" fill="url(#c10-edge)" style="filter:drop-shadow(0 0 3px #2dd4bf)" opacity="0.7"/>
  <circle cx="16" cy="26" r="1.8" fill="url(#c10-edge)" style="filter:drop-shadow(0 0 3px #2dd4bf)" opacity="0.7"/>
  <!-- Center hub -->
  <circle cx="16" cy="16" r="3.5" fill="url(#c10-center)" style="filter:drop-shadow(0 0 6px #2dd4bf)"/>
</svg>

@elseif($m === '1.5')
{{-- 1.5× Standard Mid — "Hex Engine" — indigo/violet, structured power --}}
<svg class="mult-svg-icon" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="1.5× Standard Mid" role="img">
  <defs>
    <linearGradient id="c15-outer" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#c4b5fd"/>
      <stop offset="50%" stop-color="#818cf8"/>
      <stop offset="100%" stop-color="#4338ca"/>
    </linearGradient>
    <linearGradient id="c15-inner" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#e0e7ff"/>
      <stop offset="100%" stop-color="#6366f1"/>
    </linearGradient>
    <linearGradient id="c15-shine" x1="0%" y1="0%" x2="60%" y2="100%">
      <stop offset="0%" stop-color="#fff" stop-opacity="0.3"/>
      <stop offset="100%" stop-color="#fff" stop-opacity="0"/>
    </linearGradient>
  </defs>
  <circle cx="16" cy="16" r="13" fill="#150f3d" fill-opacity="0.5" stroke="#818cf8" stroke-width="0.75" stroke-opacity="0.4"/>
  <!-- Outer hexagon -->
  <path d="M16 5l9.5 5.5v11L16 27l-9.5-5.5v-11z" fill="url(#c15-outer)" fill-opacity="0.25" stroke="url(#c15-outer)" stroke-width="1.5" style="filter:drop-shadow(0 0 5px #818cf8)"/>
  <!-- Inner hexagon -->
  <path d="M16 10l5.5 3.2v6.4L16 22.8l-5.5-3.2v-6.4z" fill="url(#c15-inner)" fill-opacity="0.5" stroke="#c4b5fd" stroke-width="1"/>
  <!-- Shine -->
  <path d="M16 5l9.5 5.5" stroke="url(#c15-shine)" stroke-width="2" stroke-linecap="round"/>
  <!-- Center dot -->
  <circle cx="16" cy="16" r="2.5" fill="#e0e7ff" style="filter:drop-shadow(0 0 4px #818cf8)"/>
</svg>

@elseif($m === '2.5')
{{-- 2.5× Frontier Mid — "Amber Crystal" — amber/orange, precision & quality --}}
<svg class="mult-svg-icon" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="2.5× Frontier Mid" role="img">
  <defs>
    <linearGradient id="c25-gem" x1="10%" y1="0%" x2="90%" y2="100%">
      <stop offset="0%" stop-color="#fed7aa"/>
      <stop offset="40%" stop-color="#fb923c"/>
      <stop offset="100%" stop-color="#c2410c"/>
    </linearGradient>
    <linearGradient id="c25-top" x1="0%" y1="0%" x2="100%" y2="60%">
      <stop offset="0%" stop-color="#ffedd5"/>
      <stop offset="100%" stop-color="#fb923c"/>
    </linearGradient>
    <linearGradient id="c25-shine" x1="0%" y1="0%" x2="70%" y2="100%">
      <stop offset="0%" stop-color="#fff" stop-opacity="0.35"/>
      <stop offset="100%" stop-color="#fff" stop-opacity="0"/>
    </linearGradient>
  </defs>
  <circle cx="16" cy="16" r="13" fill="#2a0e00" fill-opacity="0.45" stroke="#fb923c" stroke-width="0.75" stroke-opacity="0.4"/>
  <!-- Crown facets (top) -->
  <path d="M16 5l5 6H11z" fill="url(#c25-top)" style="filter:drop-shadow(0 0 4px #fb923c)"/>
  <!-- Main body facets -->
  <path d="M11 11h10l-3 12H14z" fill="url(#c25-gem)" style="filter:drop-shadow(0 0 6px #fb923c)"/>
  <!-- Side facets -->
  <path d="M11 11l-4 8 3-8z" fill="#fb923c" fill-opacity="0.4"/>
  <path d="M21 11l4 8-3-8z" fill="#fb923c" fill-opacity="0.4"/>
  <!-- Shine -->
  <path d="M16 5l5 6" stroke="url(#c25-shine)" stroke-width="1.5" stroke-linecap="round"/>
  <path d="M13 13l2 5" stroke="#fff" stroke-width="0.7" stroke-opacity="0.4" stroke-linecap="round"/>
</svg>

@elseif($m === '3.5')
{{-- 3.5× Frontier Large — "Gold Nova" — gold+crimson, maximum power --}}
<svg class="mult-svg-icon" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="3.5× Frontier Large" role="img">
  <defs>
    <radialGradient id="c35-glow" cx="50%" cy="50%" r="50%">
      <stop offset="0%" stop-color="#d4af37" stop-opacity="0.4"/>
      <stop offset="70%" stop-color="#ef4444" stop-opacity="0.1"/>
      <stop offset="100%" stop-color="#7f1d1d" stop-opacity="0"/>
    </radialGradient>
    <radialGradient id="c35-core" cx="40%" cy="35%" r="60%">
      <stop offset="0%" stop-color="#fef08a"/>
      <stop offset="50%" stop-color="#d4af37"/>
      <stop offset="100%" stop-color="#b45309"/>
    </radialGradient>
    <linearGradient id="c35-ray" x1="0%" y1="0%" x2="100%" y2="0%">
      <stop offset="0%" stop-color="#fbbf24" stop-opacity="0.8"/>
      <stop offset="100%" stop-color="#fbbf24" stop-opacity="0"/>
    </linearGradient>
  </defs>
  <circle cx="16" cy="16" r="13" fill="#1a0a00" fill-opacity="0.5" stroke="#d4af37" stroke-width="0.75" stroke-opacity="0.5"/>
  <!-- Outer glow ring -->
  <circle cx="16" cy="16" r="13" fill="url(#c35-glow)"/>
  <!-- 8 rays -->
  <line x1="16" y1="3"    x2="16" y2="8"    stroke="#fbbf24" stroke-width="1.5" stroke-linecap="round" opacity="0.8"/>
  <line x1="16" y1="24"   x2="16" y2="29"   stroke="#fbbf24" stroke-width="1.5" stroke-linecap="round" opacity="0.8"/>
  <line x1="3"  y1="16"   x2="8"  y2="16"   stroke="#fbbf24" stroke-width="1.5" stroke-linecap="round" opacity="0.8"/>
  <line x1="24" y1="16"   x2="29" y2="16"   stroke="#fbbf24" stroke-width="1.5" stroke-linecap="round" opacity="0.8"/>
  <line x1="6"  y1="6"    x2="9.5" y2="9.5" stroke="#fbbf24" stroke-width="1" stroke-linecap="round" opacity="0.6"/>
  <line x1="22.5" y1="9.5" x2="26" y2="6"   stroke="#fbbf24" stroke-width="1" stroke-linecap="round" opacity="0.6"/>
  <line x1="6"  y1="26"   x2="9.5" y2="22.5" stroke="#fbbf24" stroke-width="1" stroke-linecap="round" opacity="0.6"/>
  <line x1="22.5" y1="22.5" x2="26" y2="26" stroke="#fbbf24" stroke-width="1" stroke-linecap="round" opacity="0.6"/>
  <!-- Nova core -->
  <circle cx="16" cy="16" r="6" fill="url(#c35-core)" style="filter:drop-shadow(0 0 8px #d4af37)"/>
  <circle cx="14" cy="14" r="2" fill="#fef9c3" fill-opacity="0.7"/>
</svg>

@else
{{-- Fallback --}}
<svg class="mult-svg-icon" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="Multiplier" role="img">
  <circle cx="16" cy="16" r="12" stroke="#6b7280" stroke-width="1.5"/>
</svg>
@endif
