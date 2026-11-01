@php
    $company   = $job->company_name ?: 'Company';
    $initials  = Str::upper(collect(preg_split('/\s+/u', trim($company), -1, PREG_SPLIT_NO_EMPTY))->take(2)->map(fn ($w) => Str::substr($w, 0, 1))->implode(''));
    $hue       = crc32($company) % 360;
    $typeLabel = $job->job_type ? ucwords(str_replace('-', ' ', $job->job_type)) : null;
    $isNew     = $job->created_at && $job->created_at->gt(now()->subDays(3));
    $daysLeft  = $job->deadline ? (int) now()->startOfDay()->diffInDays($job->deadline->copy()->startOfDay(), false) : null;
    $tags      = array_slice(array_values(array_filter(array_map('trim', explode(',', (string) $job->tags)))), 0, 3);
    $salary    = ($job->salary_hidden || ! $job->salary_range) ? null : $job->salary_range;
@endphp
<article class="job-card {{ $job->is_featured ? 'is-featured' : '' }}"
         data-featured="{{ $job->is_featured ? 1 : 0 }}"
         data-remote="{{ $job->remote_available ? 1 : 0 }}"
         data-type="{{ $job->job_type }}">
    <div class="job-top">
        <div class="avatar" style="background:hsl({{ $hue }} 75% 93%);color:hsl({{ $hue }} 55% 30%);">{{ $initials }}</div>
        <div class="job-heading">
            <h3><a href="{{ route('jobs.show', $job->slug) }}" class="stretch">{{ $job->job_title }}</a></h3>
            <p class="company">{{ $company }}</p>
        </div>
        <div class="job-badges">
            @if ($job->is_featured)<span class="badge badge-gold"><i class="fa-solid fa-star"></i> Featured</span>
            @elseif ($isNew)<span class="badge badge-mint">New</span>@endif
        </div>
    </div>

    <ul class="job-meta">
        @if ($job->location)<li><i class="fa-solid fa-location-dot"></i>{{ $job->location }}</li>@endif
        @if ($typeLabel)<li><i class="fa-regular fa-clock"></i>{{ $typeLabel }}</li>@endif
        @if ($job->job_level)<li><i class="fa-solid fa-signal"></i>{{ $job->job_level }}</li>@endif
        @if ($job->remote_available)<li class="is-remote"><i class="fa-solid fa-house-laptop"></i>Remote OK</li>@endif
    </ul>

    @if ($tags)
        <div class="job-tags">
            @foreach ($tags as $tag)<span class="tag">{{ $tag }}</span>@endforeach
        </div>
    @endif

    <div class="job-foot">
        <div class="job-pay">
            @if ($salary)
                <strong>{{ $salary }}</strong>
            @else
                <strong class="muted">Negotiable</strong>
            @endif
            <span>
                @if (! is_null($daysLeft))
                    <i class="fa-regular fa-calendar"></i>
                    @if ($daysLeft <= 0) Last day to apply
                    @elseif ($daysLeft === 1) 1 day left
                    @else {{ $daysLeft }} days left @endif
                @else
                    Posted {{ $job->created_at?->diffForHumans() }}
                @endif
            </span>
        </div>
        <span class="job-cta {{ (! is_null($daysLeft) && $daysLeft <= 3) ? 'is-urgent' : '' }}">View <i class="fa-solid fa-arrow-right"></i></span>
    </div>
</article>
