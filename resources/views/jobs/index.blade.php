@extends('layouts.home')

@php
    $title = 'Jobs';
    if ($filters['q'] !== '') $title = $filters['q'] . ' jobs';
    if ($filters['location'] !== '') $title .= ' in ' . $filters['location'];
    elseif ($activeCategory) $title = $activeCategory->name . ' jobs';

    $drop = function (string $key, $value = null) use ($filters) {
        $p = request()->query();
        if ($value === null) { unset($p[$key]); }
        else { $p[$key] = array_values(array_diff((array) ($p[$key] ?? []), [$value])); }
        unset($p['page']);
        $p = array_filter($p, fn ($v) => $v !== [] && $v !== '' && $v !== null);
        return route('jobs.index', $p);
    };

    $hasFilters = $filters['q'] !== '' || $filters['location'] !== '' || $filters['category'] !== ''
        || $filters['type'] || $filters['level'] || $filters['posted'] || $filters['remote'];

    $typeOpts  = ['full-time' => 'Full time', 'part-time' => 'Part time', 'contract' => 'Contract', 'internship' => 'Internship'];
    $levelOpts = ['Entry' => 'Entry level', 'Mid' => 'Mid level', 'Senior' => 'Senior level', 'Lead' => 'Team lead', 'Manager' => 'Manager'];
    $postedOpts = [1 => 'Last 24 hours', 3 => 'Last 3 days', 7 => 'Last 7 days', 30 => 'Last 30 days'];
@endphp

@section('title', $title . ' – LivejobsBD')
@section('description', 'Browse ' . $jobs->total() . ' open jobs in Bangladesh. Filter by city, category, level and job type.')
@section('body-class', 'page-listing')

@section('content')

<section class="page-hero">
    <div class="hero-bg" aria-hidden="true"><span class="orb orb-1"></span><span class="orb orb-2"></span></div>
    <div class="container">
        <nav class="crumbs" aria-label="Breadcrumb"><a href="{{ url('/') }}">Home</a> <i class="fa-solid fa-chevron-right"></i> <span>Jobs</span></nav>
        <h1>
            @if ($hasFilters) {{ $title }} @else Find your next <em>job</em> @endif
        </h1>
        <div class="page-search">
            @include('partials.search-box', ['filters' => $filters, 'allCategories' => $allCategories, 'popular' => $popular])
        </div>
    </div>
</section>

<section class="listing">
    <div class="container listing-grid">

        {{-- ---------- Filters ---------- --}}
        <div class="filter-backdrop" data-close-filters></div>
        <aside class="filters" id="filters" aria-label="Filters">
            <form method="GET" action="{{ route('jobs.index') }}" data-autosubmit>
                <div class="filters-head">
                    <h2>Filters</h2>
                    <button type="button" class="filters-close" data-close-filters aria-label="Close filters"><i class="fa-solid fa-xmark"></i></button>
                </div>

                <input type="hidden" name="q" value="{{ $filters['q'] }}">
                <input type="hidden" name="location" value="{{ $filters['location'] }}">
                <input type="hidden" name="category" value="{{ $filters['category'] }}">

                <div class="filter-group">
                    <h3>Job type</h3>
                    @foreach ($typeOpts as $val => $label)
                        <label class="check"><input type="checkbox" name="type[]" value="{{ $val }}" @checked(in_array($val, $filters['type']))><span>{{ $label }}</span></label>
                    @endforeach
                </div>

                <div class="filter-group">
                    <h3>Experience level</h3>
                    @foreach ($levelOpts as $val => $label)
                        <label class="check"><input type="checkbox" name="level[]" value="{{ $val }}" @checked(in_array($val, $filters['level']))><span>{{ $label }}</span></label>
                    @endforeach
                </div>

                <div class="filter-group">
                    <h3>Date posted</h3>
                    <label class="radio"><input type="radio" name="posted" value="" @checked(! $filters['posted'])><span>Any time</span></label>
                    @foreach ($postedOpts as $val => $label)
                        <label class="radio"><input type="radio" name="posted" value="{{ $val }}" @checked($filters['posted'] === $val)><span>{{ $label }}</span></label>
                    @endforeach
                </div>

                <div class="filter-group">
                    <label class="switch">
                        <input type="checkbox" name="remote" value="1" @checked($filters['remote'])>
                        <span class="track"></span>
                        <span class="switch-label">Remote available</span>
                    </label>
                </div>

                <input type="hidden" name="sort" value="{{ $filters['sort'] }}">

                <div class="filters-foot">
                    <a href="{{ route('jobs.index') }}" class="btn btn-light btn-sm">Reset</a>
                    <button type="submit" class="btn btn-gold btn-sm">Show {{ number_format($jobs->total()) }} jobs</button>
                </div>
            </form>
        </aside>

        {{-- ---------- Results ---------- --}}
        <div class="results">
            <div class="results-bar">
                <p class="results-count"><strong>{{ number_format($jobs->total()) }}</strong> {{ Str::plural('job', $jobs->total()) }} found</p>

                <div class="results-tools">
                    <button type="button" class="btn btn-light btn-sm filters-open" data-open-filters><i class="fa-solid fa-sliders"></i> Filters</button>
                    <form method="GET" action="{{ route('jobs.index') }}" class="sort" data-autosubmit>
                        @foreach (request()->except(['sort', 'page']) as $k => $v)
                            @foreach ((array) $v as $vv)
                                @if (is_string($vv))<input type="hidden" name="{{ is_array($v) ? $k . '[]' : $k }}" value="{{ $vv }}">@endif
                            @endforeach
                        @endforeach
                        <label for="sortSel">Sort</label>
                        <select id="sortSel" name="sort">
                            @if ($filters['q'] !== '')<option value="relevance" @selected($filters['sort'] === 'relevance')>Most relevant</option>@endif
                            <option value="latest" @selected($filters['sort'] === 'latest')>Newest first</option>
                            <option value="deadline" @selected($filters['sort'] === 'deadline')>Deadline soonest</option>
                        </select>
                    </form>
                </div>
            </div>

            @if ($hasFilters)
                <div class="chips" aria-label="Active filters">
                    @if ($filters['q'] !== '')<a class="chip" href="{{ $drop('q') }}"><i class="fa-solid fa-magnifying-glass"></i> {{ $filters['q'] }} <i class="fa-solid fa-xmark x"></i></a>@endif
                    @if ($filters['location'] !== '')<a class="chip" href="{{ $drop('location') }}"><i class="fa-solid fa-location-dot"></i> {{ $filters['location'] }} <i class="fa-solid fa-xmark x"></i></a>@endif
                    @if ($activeCategory)<a class="chip" href="{{ $drop('category') }}"><i class="fa-solid fa-layer-group"></i> {{ $activeCategory->name }} <i class="fa-solid fa-xmark x"></i></a>@endif
                    @foreach ($filters['type'] as $t)<a class="chip" href="{{ $drop('type', $t) }}">{{ $typeOpts[$t] ?? $t }} <i class="fa-solid fa-xmark x"></i></a>@endforeach
                    @foreach ($filters['level'] as $l)<a class="chip" href="{{ $drop('level', $l) }}">{{ $levelOpts[$l] ?? $l }} <i class="fa-solid fa-xmark x"></i></a>@endforeach
                    @if ($filters['posted'])<a class="chip" href="{{ $drop('posted') }}">{{ $postedOpts[$filters['posted']] }} <i class="fa-solid fa-xmark x"></i></a>@endif
                    @if ($filters['remote'])<a class="chip" href="{{ $drop('remote') }}">Remote <i class="fa-solid fa-xmark x"></i></a>@endif
                    <a class="chip-clear" href="{{ route('jobs.index') }}">Clear all</a>
                </div>
            @endif

            @if ($jobs->count())
                <div class="job-list">
                    @foreach ($jobs as $job)
                        @include('partials.job-card', ['job' => $job])
                    @endforeach
                </div>

                @if ($jobs->hasPages())
                    @php
                        $cur = $jobs->currentPage(); $last = $jobs->lastPage();
                        $from = max(1, $cur - 2); $to = min($last, $cur + 2);
                    @endphp
                    <nav class="pager" aria-label="Pagination">
                        @if ($jobs->onFirstPage())<span class="pg is-disabled"><i class="fa-solid fa-chevron-left"></i></span>
                        @else<a class="pg" rel="prev" href="{{ $jobs->previousPageUrl() }}"><i class="fa-solid fa-chevron-left"></i></a>@endif

                        @if ($from > 1)<a class="pg" href="{{ $jobs->url(1) }}">1</a>@if ($from > 2)<span class="pg-gap">…</span>@endif @endif
                        @for ($i = $from; $i <= $to; $i++)
                            @if ($i === $cur)<span class="pg is-current" aria-current="page">{{ $i }}</span>
                            @else<a class="pg" href="{{ $jobs->url($i) }}">{{ $i }}</a>@endif
                        @endfor
                        @if ($to < $last)@if ($to < $last - 1)<span class="pg-gap">…</span>@endif<a class="pg" href="{{ $jobs->url($last) }}">{{ $last }}</a>@endif

                        @if ($jobs->hasMorePages())<a class="pg" rel="next" href="{{ $jobs->nextPageUrl() }}"><i class="fa-solid fa-chevron-right"></i></a>
                        @else<span class="pg is-disabled"><i class="fa-solid fa-chevron-right"></i></span>@endif
                    </nav>
                @endif
            @else
                <div class="empty-state">
                    <i class="fa-regular fa-face-meh"></i>
                    <h3>No jobs match your search</h3>
                    <p>Try fewer filters, check the spelling, or start from one of these popular searches.</p>
                    <div class="empty-chips">
                        @foreach ($popular as $term)<a href="{{ route('jobs.index', ['q' => $term]) }}" class="chip">{{ $term }}</a>@endforeach
                    </div>
                    <a href="{{ route('jobs.index') }}" class="btn btn-gold">Clear all filters</a>
                </div>
            @endif
        </div>
    </div>
</section>

@endsection
