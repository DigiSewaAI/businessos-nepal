@php
    $branding = app(\App\Services\BrandingService::class);
    $title = $branding->get('meta_title', config('app.name', 'BusinessOS'));
    $description = $branding->get('meta_description', '');
    $ogTitle = $branding->get('og_title', $title);
    $ogDesc = $branding->get('og_description', $description);
    $ogImage = asset($branding->get('og_image', 'images/default-og.jpg'));
    $ogUrl = url()->current();
    $canonical = $ogUrl;
    $twitterCard = $branding->get('twitter_card', 'summary_large_image');
    $twitterSite = $branding->get('twitter_site', '');
    $socialLinks = $branding->get('social_links', []);
    
    // JSON-LD को लागि array बनाउने
    $organizationData = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => $branding->get('brand_name', 'BusinessOS'),
        'url' => $ogUrl,
        'logo' => asset($branding->get('logo')),
        'contactPoint' => [
            '@type' => 'ContactPoint',
            'email' => $branding->get('contact_email', ''),
            'contactType' => 'sales'
        ],
        'sameAs' => array_values($socialLinks)
    ];
@endphp

<!-- ============ FAVICON ============ -->
<link rel="icon" type="image/png" href="{{ asset('favicon-96x96.png?v=2') }}" sizes="96x96" />
<link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg?v=2') }}" />
<link rel="shortcut icon" href="{{ asset('favicon.ico?v=2') }}" />
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png?v=2') }}" />
<meta name="apple-mobile-web-app-title" content="{{ $branding->get('brand_name', 'BusinessOS') }}" />
<link rel="manifest" href="{{ asset('site.webmanifest') }}" />

<!-- ============ PRIMARY META ============ -->
<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
<meta name="keywords" content="{{ $branding->get('meta_keywords', '') }}">

<!-- ============ OPEN GRAPH ============ -->
<meta property="og:title" content="{{ $ogTitle }}">
<meta property="og:description" content="{{ $ogDesc }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:url" content="{{ $ogUrl }}">
<meta property="og:type" content="{{ $branding->get('og_type', 'website') }}">
<meta property="og:site_name" content="{{ $branding->get('brand_name', 'BusinessOS') }}">

<!-- ============ TWITTER CARD ============ -->
<meta name="twitter:card" content="{{ $twitterCard }}">
@if($twitterSite)
    <meta name="twitter:site" content="{{ $twitterSite }}">
@endif
<meta name="twitter:title" content="{{ $ogTitle }}">
<meta name="twitter:description" content="{{ $ogDesc }}">
<meta name="twitter:image" content="{{ $ogImage }}">

<!-- ============ CANONICAL ============ -->
<link rel="canonical" href="{{ $canonical }}">

<!-- ============ JSON-LD: ORGANIZATION SCHEMA ============ -->
<script type="application/ld+json">
{!! json_encode($organizationData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) !!}
</script>

<!-- ============ PER-PAGE SEO ============ -->
@stack('seo')
