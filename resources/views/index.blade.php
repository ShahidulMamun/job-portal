@extends('layouts.home')

@section('title', 'LivejobsBD – Find your next job in Bangladesh')
@section('description', 'Search verified jobs across Dhaka, Chattogram, Sylhet and all of Bangladesh. See salary and deadline up front. Free for job seekers.')

@php
    $catIcons = [
        'it' => 'fa-code', 'software' => 'fa-code', 'develop' => 'fa-code', 'tech' => 'fa-microchip',
        'market' => 'fa-bullhorn', 'sales' => 'fa-handshake', 'design' => 'fa-pen-ruler', 'creative' => 'fa-pen-ruler',
        'financ' => 'fa-chart-line', 'account' => 'fa-calculator', 'bank' => 'fa-building-columns',
        'hr' => 'fa-users', 'human' => 'fa-users', 'health' => 'fa-heart-pulse', 'medic' => 'fa-stethoscope',
        'educat' => 'fa-graduation-cap', 'teach' => 'fa-chalkboard-user', 'engineer' => 'fa-gears',
        'admin' => 'fa-folder-open', 'customer' => 'fa-headset', 'support' => 'fa-headset', 'logistic' => 'fa-truck-fast',
        'garment' => 'fa-shirt', 'textile' => 'fa-shirt', 'legal' => 'fa-scale-balanced', 'content' => 'fa-feather-pointed',
        'data' => 'fa-database', 'ngo' => 'fa-hand-holding-heart', 'hotel' => 'fa-bell-concierge', 'media' => 'fa-photo-film',
    ];
    $iconFor = function ($name) use ($catIcons) {
        $n = Str::lower($name);
        foreach ($catIcons as $k => $icon) { if (Str::contains($n, $k)) return $icon; }
        return 'fa-briefcase';
    };
@endphp

@section('content')

<h1 class="sr-only">Find jobs and hire talent in Bangladesh</h1>

{{-- ============================= STATS ============================= --}}
<section class="stats-band">
    <div class="container">
        <div class="stats-grid reveal">
            <div class="stat"><i class="fa-solid fa-briefcase"></i><strong data-count="{{ $stats['jobs'] }}">{{ number_format($stats['jobs']) }}</strong><span>Open jobs</span></div>
            <div class="stat"><i class="fa-regular fa-building"></i><strong data-count="{{ $stats['companies'] }}">{{ number_format($stats['companies']) }}</strong><span>Companies hiring</span></div>
            <div class="stat"><i class="fa-solid fa-user-group"></i><strong data-count="{{ $stats['candidates'] }}">{{ number_format($stats['candidates']) }}</strong><span>Job seekers</span></div>
            <div class="stat"><i class="fa-regular fa-paper-plane"></i><strong data-count="{{ $stats['applications'] }}">{{ number_format($stats['applications']) }}</strong><span>Applications sent</span></div>
        </div>
    </div>
</section>

{{-- ============================= CATEGORIES (full width) ============================= --}}
<section class="section section--cats" id="categories">
    <div class="container">
        <div class="section-head reveal">
            <div>
                <span class="eyebrow">Browse by field</span>
                <h2>Pick where you want to <em>grow</em></h2>
            </div>
            <a href="{{ route('jobs.index') }}" class="link-arrow">All categories <i class="fa-solid fa-arrow-right"></i></a>
        </div>

        <div class="hcat-grid">
            @forelse ($categories as $cat)
                <a href="{{ route('jobs.index', ['category' => $cat->id]) }}" class="hcat reveal">
                    <span class="hcat-ico"><i class="fa-solid {{ $iconFor($cat->name) }}"></i></span>
                    <span class="hcat-txt">
                        <strong>{{ $cat->name }}</strong>
                        <small>{{ number_format($cat->jobs_count) }} {{ Str::plural('job', $cat->jobs_count) }}</small>
                    </span>
                </a>
            @empty
                <p class="muted">Categories will appear here once they are added.</p>
            @endforelse
        </div>
    </div>
</section>

{{-- ============================= SEARCH + CANDIDATE REGISTRATION ============================= --}}
<section class="hero" id="home">
    <div class="hero-bg" aria-hidden="true"></div>

    <div class="container hero-inner">
        <div class="hero-pill reveal">
            <span class="pulse"></span>
            <strong>{{ number_format($stats['jobs']) }}</strong> live jobs
            <span class="sep"></span>
            Updated every day
        </div>

        <div class="hero-split">
            <section class="hero-card hero-card--seeker reveal" aria-labelledby="seekerTitle">
                <span class="hc-badge"><i class="fa-solid fa-magnifying-glass"></i> Job search</span>
                <h2 id="seekerTitle">Find your next job</h2>

                @include('partials.search-box', ['filters' => [], 'allCategories' => $allCategories, 'popular' => $popular])

                <div class="quick-pills" aria-label="Quick filters">
                    <a href="{{ route('jobs.index', ['remote' => 1]) }}"><i class="fa-solid fa-house-laptop"></i> Remote</a>
                    <a href="{{ route('jobs.index', ['level' => ['Entry']]) }}"><i class="fa-solid fa-seedling"></i> Fresher friendly</a>
                    <a href="{{ route('jobs.index', ['type' => ['internship']]) }}"><i class="fa-solid fa-user-graduate"></i> Internships</a>
                    <a href="{{ route('jobs.index', ['posted' => 1]) }}"><i class="fa-solid fa-bolt"></i> Posted today</a>
                </div>
            </section>

            <section class="hero-card hero-card--reg reveal" aria-labelledby="regTitle">
                <span class="hc-badge hc-badge--ink"><i class="fa-regular fa-user"></i> Candidate registration</span>
                <h2 id="regTitle">Create your free profile</h2>
                <p class="hc-sub">Upload your CV once and apply to jobs in one click.</p>
                @include('partials.cv-drop')
                @guest
                    <p class="hc-login">Already registered? <a href="{{ route('login') }}">Log in</a></p>
                @endguest
            </section>
        </div>
    </div>
</section>

{{-- ============================= JOBS ============================= --}}
<section class="section section--tint" id="jobs">
    <div class="container">
        <div class="section-head reveal">
            <div>
                <span class="eyebrow">Fresh off the board</span>
                <h2>Latest &amp; featured <em>jobs</em></h2>
            </div>
            <a href="{{ route('jobs.index') }}" class="link-arrow">View all {{ number_format($stats['jobs']) }} jobs <i class="fa-solid fa-arrow-right"></i></a>
        </div>

        <div class="tabs reveal" id="jobTabs" role="tablist">
            <button class="is-active" data-tab="all" role="tab">All</button>
            <button data-tab="featured" role="tab"><i class="fa-solid fa-star"></i> Featured</button>
            <button data-tab="remote" role="tab"><i class="fa-solid fa-house-laptop"></i> Remote</button>
            <button data-tab="full-time" role="tab">Full-time</button>
            <button data-tab="part-time" role="tab">Part-time</button>
            <button data-tab="internship" role="tab">Internship</button>
        </div>

        <div class="job-list" id="jobGrid">
            @forelse ($jobs as $job)
                @include('partials.job-card', ['job' => $job])
            @empty
                <div class="empty-state">
                    <i class="fa-regular fa-folder-open"></i>
                    <h3>No open jobs right now</h3>
                    <p>New roles are added every day. Create a free account and we'll be ready for you.</p>
                    <a href="{{ route('register') }}" class="btn btn-gold">Create free account</a>
                </div>
            @endforelse
        </div>
        <p class="tab-empty" id="tabEmpty" hidden>Nothing in this tab yet &mdash; <a href="{{ route('jobs.index') }}">browse all jobs</a>.</p>

        <div class="center-cta">
            <a href="{{ route('jobs.index') }}" class="btn btn-dark">Browse all jobs <i class="fa-solid fa-arrow-right"></i></a>
        </div>
    </div>
</section>

{{-- ============================= CITIES ============================= --}}
<section class="section" id="cities">
    <div class="container">
        <div class="section-head reveal">
            <div>
                <span class="eyebrow">Jobs near you</span>
                <h2>Explore by <em>city</em></h2>
            </div>
            <a href="{{ route('jobs.index', ['remote' => 1]) }}" class="link-arrow">{{ number_format($remoteCount) }} remote jobs <i class="fa-solid fa-arrow-right"></i></a>
        </div>

        <div class="city-grid">
            @foreach ($cities as $city => $count)
                <a href="{{ route('jobs.index', ['location' => $city]) }}" class="city-card reveal">
                    <span class="city-name">{{ $city }}</span>
                    <span class="city-count">{{ number_format($count) }} {{ Str::plural('job', $count) }}</span>
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================= RECRUITER ============================= --}}
<section class="section" id="recruiters">
    <div class="container">
        <section class="hirer-wide reveal" aria-labelledby="hirerTitle">
            <div class="hirer-copy">
                <span class="hc-badge hc-badge--ink"><i class="fa-regular fa-building"></i> Recruiter</span>
                <h2 id="hirerTitle">Hire the right people, faster</h2>
                <p class="hc-sub">Post a job in about five minutes and reach job seekers across Bangladesh.</p>

                <ul class="checks">
                    <li><i class="fa-solid fa-circle-check"></i> Post a job in about five minutes</li>
                    <li><i class="fa-solid fa-circle-check"></i> Reach active job seekers across Bangladesh</li>
                    <li><i class="fa-solid fa-circle-check"></i> Review every application in one dashboard</li>
                </ul>

                <div class="hc-actions">
                    <a href="{{ route('employer.register') }}" class="btn btn-gold">Post a job <i class="fa-solid fa-arrow-right"></i></a>
                    <a href="{{ route('employer.login') }}" class="btn btn-ghost">Employer login</a>
                </div>
                <p class="hc-stat"><strong>{{ number_format($stats['candidates']) }}</strong> job seekers ready to apply</p>
            </div>
            <div class="hirer-art" aria-hidden="true">@include('partials.desk-art')</div>
        </section>
    </div>
</section>

{{-- ============================= COMPANIES ============================= --}}
@if ($companies->isNotEmpty())
<section class="section" id="companies">
    <div class="container">
        <div class="section-head reveal">
            <div>
                <span class="eyebrow">Who's hiring</span>
                <h2>Companies hiring <em>right now</em></h2>
            </div>
        </div>

        <div class="company-grid">
            @foreach ($companies as $co)
                @php
                    $hue = crc32($co->company_name) % 360;
                    $ini = Str::upper(collect(preg_split('/\s+/u', trim($co->company_name), -1, PREG_SPLIT_NO_EMPTY))->take(2)->map(fn ($w) => Str::substr($w, 0, 1))->implode(''));
                @endphp
                <a href="{{ route('jobs.index', ['q' => $co->company_name]) }}" class="company-card reveal">
                    <span class="avatar avatar-lg" style="background:hsl({{ $hue }} 75% 93%);color:hsl({{ $hue }} 55% 30%);">{{ $ini }}</span>
                    <strong>{{ $co->company_name }}</strong>
                    <span class="company-open">{{ $co->jobs_count }} open {{ Str::plural('role', $co->jobs_count) }}</span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ============================= EMPLOYER CTA ============================= --}}
<section class="section section--tight" id="employers">
    <div class="container">
        <div class="employer-card reveal">
            <div class="employer-copy">
                <span class="eyebrow eyebrow--gold">For employers</span>
                <h2>Hire your next great teammate, faster.</h2>
                <ul class="checks">
                    <li><i class="fa-solid fa-circle-check"></i> Post a job in about five minutes</li>
                    <li><i class="fa-solid fa-circle-check"></i> Reach active job seekers across Bangladesh</li>
                    <li><i class="fa-solid fa-circle-check"></i> Review every application in one dashboard</li>
                </ul>
                <div class="employer-actions">
                    <a href="{{ route('employer.register') }}" class="btn btn-gold">Post a job <i class="fa-solid fa-arrow-right"></i></a>
                    <a href="{{ route('employer.login') }}" class="btn btn-ghost">Employer login</a>
                </div>
            </div>
            <div class="employer-visual" aria-hidden="true">
                @foreach ($liveJobs as $lj)
                    <div class="mini-job" style="--i: {{ $loop->index }}">
                        <span class="mini-dot"></span>
                        <div><strong>{{ Str::limit($lj->job_title, 28) }}</strong><small>{{ Str::limit($lj->company_name, 26) }}</small></div>
                        <i class="fa-solid fa-check"></i>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ============================= FAQ ============================= --}}
<section class="section section--tint" id="faq">
    <div class="container container--narrow">
        <div class="section-head section-head--center reveal">
            <span class="eyebrow">Good to know</span>
            <h2>Questions, <em>answered</em></h2>
        </div>

        <div class="faq reveal">
            <details>
                <summary>Is LivejobsBD really free for job seekers?</summary>
                <p>Yes. Creating an account, searching, and applying never costs anything. We will never ask you to pay to apply for a job.</p>
            </details>
            <details>
                <summary>How do I apply for a job?</summary>
                <p>Open any job, click apply, attach your CV and add a short cover letter. You can follow the status of each application from your dashboard.</p>
            </details>
            <details>
                <summary>Can I search in Bangla or by voice?</summary>
                <p>Use the microphone in the search box and pick EN or বাং. We turn your speech into a keyword search instantly.</p>
            </details>
            <details>
                <summary>How do I post a job as an employer?</summary>
                <p>Register an employer account, add your company details and publish your first job. Listings appear after a quick approval.</p>
            </details>
        </div>
    </div>
</section>

{{-- ============================= FINAL CTA ============================= --}}
<section class="final-cta">
    <div class="container final-inner reveal">
        <h2>Your next role is <em>one search</em> away.</h2>
        <p>Join job seekers across Bangladesh finding work that fits.</p>
        <div class="final-actions">
            <a href="{{ route('register') }}" class="btn btn-gold btn-lg">Create free account</a>
            <a href="{{ route('jobs.index') }}" class="btn btn-ghost btn-lg">Browse jobs</a>
        </div>
    </div>
</section>

@endsection
