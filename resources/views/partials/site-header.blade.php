<header class="site-header" id="siteHeader">
    <div class="container header-inner">
        <a href="{{ url('/') }}" class="brand" aria-label="LivejobsBD home">
            <span class="brand-mark"><i class="fa-solid fa-briefcase"></i></span>
            <span class="brand-name">Livejobs<b>BD</b></span>
        </a>

        <nav class="main-nav" id="mainNav" aria-label="Primary">
            <a href="{{ route('jobs.index') }}" class="{{ request()->routeIs('jobs.index') ? 'is-current' : '' }}">Find jobs</a>
            <a href="{{ url('/') }}#categories">Categories</a>
            <a href="{{ url('/') }}#companies">Companies</a>
            <a href="{{ url('/') }}#how">How it works</a>
            <a href="{{ route('employer.login') }}" class="nav-employer"><i class="fa-regular fa-building"></i> For employers</a>

            <div class="nav-actions">
                @if (auth()->check())
                    <a href="{{ route('candidate.dashboard') }}" class="btn btn-gold btn-sm"><i class="fa-regular fa-user"></i> My dashboard</a>
                @elseif (auth('employer')->check())
                    <a href="{{ route('employer.dashboard') }}" class="btn btn-gold btn-sm"><i class="fa-regular fa-building"></i> Employer dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-ghost btn-sm">Log in</a>
                    <a href="{{ route('register') }}" class="btn btn-gold btn-sm">Sign up free</a>
                @endif
            </div>
        </nav>

        <button class="nav-toggle" id="navToggle" aria-label="Open menu" aria-expanded="false" aria-controls="mainNav">
            <span></span><span></span><span></span>
        </button>
    </div>
</header>
