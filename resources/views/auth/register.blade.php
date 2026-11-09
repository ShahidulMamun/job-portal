@extends('layouts.auth')

@php
    $role = old('user_type', request('role', $role ?? 'candidate'));
    $role = in_array($role, ['candidate', 'employer'], true) ? $role : 'candidate';
@endphp

@section('title', 'Create account')

@section('alt')
    Already have an account? <a href="{{ route('login') }}">Log in</a>
@endsection

@section('content')
<div class="auth-card" data-role="{{ $role }}">
    <h1>
        <span data-for="candidate">Create your account</span>
        <span data-for="employer">Start hiring today</span>
    </h1>
    <p class="lead">
        <span data-for="candidate">Free for job seekers. Takes about a minute.</span>
        <span data-for="employer">Post your first job in about five minutes.</span>
    </p>

    <div class="seg" role="tablist" aria-label="Account type">
        <button type="button" role="tab" data-role-btn="candidate"><i class="fa-regular fa-user"></i> Job seeker</button>
        <button type="button" role="tab" data-role-btn="employer"><i class="fa-regular fa-building"></i> Employer</button>
    </div>

    <form method="POST" action="{{ route('register') }}"
          data-action-candidate="{{ route('register') }}"
          data-action-employer="{{ route('employer.register.store') }}">
        @csrf
        <input type="hidden" name="user_type" value="{{ $role }}" data-role-input>

        <div class="field {{ $errors->has('name') ? 'has-error' : '' }}">
            <label for="name"><span data-for="candidate">Full name</span><span data-for="employer">Your name</span></label>
            <div class="ctl">
                <i class="fa-regular fa-user ic"></i>
                <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Rahim Uddin" autocomplete="name" required autofocus>
            </div>
            @error('name')<p class="field-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>@enderror
        </div>

        <div class="field {{ $errors->has('company_name') ? 'has-error' : '' }}" data-for="employer">
            <label for="company_name">Company name</label>
            <div class="ctl">
                <i class="fa-regular fa-building ic"></i>
                <input id="company_name" type="text" name="company_name" value="{{ old('company_name') }}" placeholder="e.g. Shonar Tech Ltd" autocomplete="organization" data-employer-only>
            </div>
            @error('company_name')<p class="field-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>@enderror
        </div>

        <div class="field {{ $errors->has('email') ? 'has-error' : '' }}">
            <label for="email"><span data-for="candidate">Email address</span><span data-for="employer">Work email</span></label>
            <div class="ctl">
                <i class="fa-regular fa-envelope ic"></i>
                <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" autocomplete="username" required>
            </div>
            @error('email')<p class="field-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>@enderror
        </div>

        <div class="field {{ $errors->has('password') ? 'has-error' : '' }}">
            <label for="password">Password</label>
            <div class="ctl">
                <i class="fa-solid fa-lock ic"></i>
                <input id="password" type="password" name="password" placeholder="At least 8 characters" autocomplete="new-password" required minlength="8" data-strength data-caps>
                <button type="button" class="eye" data-eye aria-label="Show password" aria-pressed="false"><i class="fa-regular fa-eye"></i></button>
            </div>
            <div class="meter" data-meter data-s="0" aria-hidden="true"><span></span><span></span><span></span><span></span></div>
            <p class="hint" data-meter-label>Use 8 or more characters with letters and numbers.</p>
            <p class="hint is-warn" data-caps-hint hidden><i class="fa-solid fa-circle-exclamation"></i> Caps Lock is on</p>
            @error('password')<p class="field-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>@enderror
        </div>

        <div class="field {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
            <label for="password_confirmation">Confirm password</label>
            <div class="ctl">
                <i class="fa-solid fa-lock ic"></i>
                <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Type it once more" autocomplete="new-password" required data-confirm>
                <button type="button" class="eye" data-eye aria-label="Show password" aria-pressed="false"><i class="fa-regular fa-eye"></i></button>
            </div>
            <p class="hint" data-match hidden></p>
            @error('password_confirmation')<p class="field-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>@enderror
        </div>

        <div class="field {{ $errors->has('captcha_answer') ? 'has-error' : '' }}">
            <label for="captchaAnswer">Quick security check</label>
            <div class="captcha">
                <span class="captcha-q"><b id="captchaNum1">{{ $captchaNum1 ?? 4 }}</b> + <b id="captchaNum2">{{ $captchaNum2 ?? 7 }}</b> =</span>
                <input id="captchaAnswer" type="text" name="captcha_answer" inputmode="numeric" autocomplete="off" placeholder="?" required>
                <button type="button" class="captcha-refresh" data-captcha-refresh data-url="{{ route('captcha.refresh') }}" aria-label="Get a new question"><i class="fa-solid fa-rotate"></i></button>
            </div>
            @error('captcha_answer')<p class="field-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>@enderror
        </div>

        <button type="submit" class="btn btn-gold btn-block" data-submit><span>Create account</span> <i class="fa-solid fa-arrow-right"></i></button>
        <p class="fine">By creating an account you agree to our Terms &amp; Conditions and Privacy Policy.</p>
    </form>
</div>
@endsection
