<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LivejobsBD – Find your next job in Bangladesh')</title>
    <meta name="description" content="@yield('description', 'Search thousands of verified jobs across Dhaka, Chattogram, Sylhet and all of Bangladesh. Free for job seekers.')">
    <meta property="og:title" content="@yield('title', 'LivejobsBD – Find your next job in Bangladesh')">
    <meta property="og:description" content="@yield('description', 'Search verified jobs across Bangladesh. Free for job seekers.')">
    <meta property="og:type" content="website">
    <meta name="theme-color" content="#f8f5ef">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,500;1,9..144,600&family=Hind+Siliguri:wght@400;500;600&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/premium.css') }}?v={{ @filemtime(public_path('css/premium.css')) }}">

    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => 'LivejobsBD',
        'url' => url('/'),
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => route('jobs.index') . '?q={search_term_string}',
            'query-input' => 'required name=search_term_string',
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    @stack('head')
</head>
<body class="@yield('body-class')">
    <script>document.documentElement.classList.add('js');</script>
    <a class="skip-link" href="#main">Skip to content</a>

    @include('partials.site-header')

    <main id="main">
        @yield('content')
    </main>

    @include('partials.site-footer')

    <button type="button" class="to-top" id="toTop" aria-label="Back to top"><i class="fa-solid fa-arrow-up"></i></button>

    <script src="{{ asset('js/premium.js') }}?v={{ @filemtime(public_path('js/premium.js')) }}" defer></script>
    @stack('scripts')
</body>
</html>
