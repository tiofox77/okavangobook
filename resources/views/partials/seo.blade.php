@php
    $seoAppName     = \App\Models\Setting::get('app_name', config('app.name', 'KiandaStay'));
    $seoDefaultDesc = \App\Models\Setting::get('meta_description', 'Encontre, compare e reserve as melhores acomodações em Angola — hotéis, resorts e hospedarias nas 18 províncias, com os melhores preços.');
    $seoDescription = trim($__env->yieldContent('meta_description', $seoDefaultDesc));
    $seoKeywords    = \App\Models\Setting::get('meta_keywords', 'hotéis Angola, reservas, resorts, hospedarias, Luanda, Benguela, alojamento, turismo Angola');
    $seoTitle       = trim($__env->yieldContent('title', 'Encontre as melhores acomodações em Angola'));
    $seoFullTitle   = $seoAppName . ' - ' . $seoTitle;
    $seoImage       = trim($__env->yieldContent('meta_image', asset('storage/locations/commons/luanda.jpg')));
    $seoUrl         = url()->current();
    $seoType        = trim($__env->yieldContent('og_type', 'website'));
@endphp

<meta name="description" content="{{ $seoDescription }}">
<meta name="keywords" content="{{ $seoKeywords }}">
<meta name="robots" content="@yield('robots', 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1')">
<meta name="author" content="{{ $seoAppName }}">
<link rel="canonical" href="{{ $seoUrl }}">

{{-- Open Graph --}}
<meta property="og:site_name" content="{{ $seoAppName }}">
<meta property="og:title" content="{{ $seoFullTitle }}">
<meta property="og:description" content="{{ $seoDescription }}">
<meta property="og:type" content="{{ $seoType }}">
<meta property="og:url" content="{{ $seoUrl }}">
<meta property="og:image" content="{{ $seoImage }}">
<meta property="og:locale" content="pt_AO">

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $seoFullTitle }}">
<meta name="twitter:description" content="{{ $seoDescription }}">
<meta name="twitter:image" content="{{ $seoImage }}">

{{-- Tema (cor da barra do browser) --}}
<meta name="theme-color" content="#134e91">

{{-- Dados estruturados base (Organização + Site com pesquisa) --}}
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'WebSite',
    'name' => $seoAppName,
    'url' => url('/'),
    'potentialAction' => [
        '@type' => 'SearchAction',
        'target' => route('search.results') . '?location={search_term_string}',
        'query-input' => 'required name=search_term_string',
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>

@yield('structured_data')
