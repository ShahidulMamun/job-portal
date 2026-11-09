@extends('layouts.auth')

@section('title', 'Reset password')

@section('alt')
    Remembered it? <a href="{{ route('login') }}">Log in</a>
@endsection

@section('content')
<div class="auth-card">
    <span class="auth-icon"><i class="fa-solid fa-key"></i></span>
    <h1>Forgot your password?</h1>
    <p class="lead">No problem. Enter your email and we will send you a link to choose a new one.</p>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        @if (session('status'))
            <div class="auth-alert is-ok" role="status"><i class="fa-solid fa-circle-check"></i><span>{{ session('status') }}</span></div>
        @endif
        @if ($errors->any())
            <div class="auth-alert" role="alert"><i class="fa-solid fa-circle-exclamation"></i><span>{{ $errors->first() }}</span></div>
        @endif

        <div class="field {{ $errors->has('email') ? 'has-error' : '' }}">
            <label for="email">Email address</label>
            <div class="ctl">
                <i class="fa-regular fa-envelope ic"></i>
                <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" autocomplete="username" required autofocus>
            </div>
        </div>

        <button type="submit" class="btn btn-gold btn-block" data-submit><span>Email me a reset link</span> <i class="fa-solid fa-arrow-right"></i></button>
    </form>

    <p class="auth-switch"><a href="{{ route('login') }}"><i class="fa-solid fa-arrow-left"></i> Back to log in</a></p>
</div>
@endsection
