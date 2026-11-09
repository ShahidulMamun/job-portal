@extends('layouts.auth')

@php
    $role = old('role', request('role', $role ?? 'candidate'));
    $role = in_array($role, ['candidate', 'employer'], true) ? $role : 'candidate';
@endphp

@section('title', 'Log in')

@section('alt')
    New here? <a href="{{ route('register') }}">Create an account</a>
@endsection

@section('content')
<div class="auth-card" data-role="{{ $role }}">
    <h1>Welcome back</h1>
    <p class="lead">
        <span data-for="candidate">Log in to continue your job search.</span>
        <span data-for="employer">Log in to manage your jobs and applicants.</span>
    </p>

    <div class="seg" role="tablist" aria-label="Account type">
        <button type="button" role="tab" data-role-btn="candidate"><i class="fa-regular fa-user"></i> Job seeker</button>
        <button type="button" role="tab" data-role-btn="employer"><i class="fa-regular fa-building"></i> Employer</button>
    </div>

    <form method="POST" action="{{ route('login') }}"
          data-action-candidate="{{ route('login') }}"
          data-action-employer="{{ route('employer.login') }}">
        @csrf
        <input type="hidden" name="role" value="{{ $role }}" data-role-input>

        @if ($errors->any())
            <div class="auth-alert" role="alert"><i class="fa-solid fa-circle-exclamation"></i><span>{{ $errors->first() }}</span></div>
        @endif
        @if (session('status'))
            <div class="auth-alert is-ok" role="status"><i class="fa-solid fa-circle-check"></i><span>{{ session('status') }}</span></div>
        @endif

        <div class="field {{ $errors->has('email') ? 'has-error' : '' }}">
            <label for="email">Email address</label>
            <div class="ctl">
                <i class="fa-regular fa-envelope ic"></i>
                <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" autocomplete="username" required autofocus>
            </div>
        </div>

        <div class="field">
            <div class="label-row">
                <label for="password">Password</label>
                <a href="{{ route('password.request') }}" class="small-link">Forgot password?</a>
            </div>
            <div class="ctl">
                <i class="fa-solid fa-lock ic"></i>
                <input id="password" type="password" name="password" placeholder="Enter your password" autocomplete="current-password" required data-caps>
                <button type="button" class="eye" data-eye aria-label="Show password" aria-pressed="false"><i class="fa-regular fa-eye"></i></button>
            </div>
            <p class="hint is-warn" data-caps-hint hidden><i class="fa-solid fa-circle-exclamation"></i> Caps Lock is on</p>
        </div>

        <label class="check"><input type="checkbox" name="remember"><span>Keep me signed in on this device</span></label>

        <button type="submit" class="btn btn-gold btn-block" data-submit><span>Log in</span> <i class="fa-solid fa-arrow-right"></i></button>
    </form>

    <p class="auth-switch">
        <span data-for="candidate">Looking to hire? <a href="{{ route('employer.register') }}">Create an employer account</a></span>
        <span data-for="employer">New employer? <a href="{{ route('employer.register') }}">Create an employer account</a></span>
    </p>
</div>
@endsection
