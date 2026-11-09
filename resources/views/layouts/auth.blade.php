@php
    $authJobs = \Illuminate\Support\Facades\Cache::remember('auth.live_jobs', 600, fn () => \App\Models\JobPost::open()->count());
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') – LivejobsBD</title>
    <meta name="robots" content="noindex">
    <meta name="theme-color" content="#ffffff">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Hind+Siliguri:wght@400;500;600&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/premium.css') }}?v={{ @filemtime(public_path('css/premium.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}?v={{ @filemtime(public_path('css/auth.css')) }}">
</head>
<body class="auth-body">
<div class="auth">

    <div class="auth-main">
        <header class="auth-top">
            <a href="{{ url('/') }}" class="brand" aria-label="LivejobsBD home">
                <span class="brand-mark"><i class="fa-solid fa-briefcase"></i></span>
                <span class="brand-name">Livejobs<b>BD</b></span>
            </a>
            <p class="auth-alt">@yield('alt')</p>
        </header>

        <main class="auth-content">
            @yield('content')
        </main>

        <footer class="auth-foot">
            <span>&copy; {{ date('Y') }} LivejobsBD</span>
            <a href="{{ url('/') }}"><i class="fa-solid fa-arrow-left"></i> Back to jobs</a>
        </footer>
    </div>

    <aside class="auth-visual">
        <div class="av-inner">
            <h2>Your next role is one search away.</h2>
            <p class="av-sub">Verified employers, salary and deadline up front, and no fees for job seekers.</p>
            <ul class="av-points">
                <li><i class="fa-solid fa-circle-check"></i> Apply with one click using a saved CV</li>
                <li><i class="fa-solid fa-circle-check"></i> Track every application in one dashboard</li>
                <li><i class="fa-solid fa-circle-check"></i> Employers reach active candidates across Bangladesh</li>
            </ul>
            <div class="av-art">
                @include('partials.desk-art')
                <span class="av-chip"><span class="pulse"></span><b>{{ number_format($authJobs) }}</b> live jobs</span>
            </div>
        </div>
    </aside>
</div>

<script src="{{ asset('js/auth.js') }}?v={{ @filemtime(public_path('js/auth.js')) }}" defer></script>
</body>
</html>
