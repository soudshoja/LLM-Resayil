@props([
    'currentPath' => null,
    'isXDefault' => false,
])

@php
    // Determine current path
    $path = $currentPath ?? request()->path();

    // Normalize path - remove /ar prefix if present, normalize /locale/ar patterns
    if (str_starts_with($path, '/locale/')) {
        // Convert /locale/ar or /locale/en to just the base path
        $path = preg_replace('|^/locale/[a-z]{2}/?|', '/', $path);
    }

    // Get base URL
    $baseUrl = url('/');

    // Generate URLs
    // English version: base path without lang query param or /ar suffix
    $enPath = '/' . ltrim($path, '/');
    $enPath = str_contains($enPath, '?')
        ? preg_replace('/[?&]lang=ar/', '', $enPath)
        : $enPath;
    $enUrl = rtrim($baseUrl, '/') . $enPath;

    // Arabic version: add ?lang=ar or append to existing query
    $arPath = str_contains($enPath, '?')
        ? $enPath . '&lang=ar'
        : $enPath . '?lang=ar';
    $arUrl = rtrim($baseUrl, '/') . $arPath;

    // Remove duplicate query params if they exist
    $enUrl = preg_replace('/[?&]lang=en/', '', $enUrl);
    $arUrl = str_replace('??', '?', $arUrl);
    $arUrl = str_replace('&?', '&', $arUrl);
@endphp

<!-- Hreflang Tags for Multilingual SEO -->
<link rel="alternate" hreflang="en" href="{{ $enUrl }}" />
<link rel="alternate" hreflang="ar" href="{{ $arUrl }}" />
@if ($isXDefault)
    <link rel="alternate" hreflang="x-default" href="{{ $enUrl }}" />
@endif
