@extends('layouts.auth')

@section('title', 'Choose a new password')

@section('alt')
    <a href="{{ route('login') }}">Back to log in</a>
@endsection

@section('content')
<div class="auth-card">
    <span class="auth-icon"><i class="fa-solid fa-lock"></i></span>
    <h1>Choose a new password</h1>
    <p class="lead">Pick something you have not used before. You will be logged in right after.</p>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        @if ($errors->any())
            <div class="auth-alert" role="alert"><i class="fa-solid fa-circle-exclamation"></i><span>{{ $errors->first() }}</span></div>
        @endif

        <div class="field {{ $errors->has('email') ? 'has-error' : '' }}">
            <label for="email">Email address</label>
            <div class="ctl">
                <i class="fa-regular fa-envelope ic"></i>
                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" autocomplete="username" required>
            </div>
        </div>

        <div class="field {{ $errors->has('password') ? 'has-error' : '' }}">
            <label for="password">New password</label>
            <div class="ctl">
                <i class="fa-solid fa-lock ic"></i>
                <input id="password" type="password" name="password" placeholder="At least 8 characters" autocomplete="new-password" required minlength="8" data-strength data-caps autofocus>
                <button type="button" class="eye" data-eye aria-label="Show password" aria-pressed="false"><i class="fa-regular fa-eye"></i></button>
            </div>
            <div class="meter" data-meter data-s="0" aria-hidden="true"><span></span><span></span><span></span><span></span></div>
            <p class="hint" data-meter-label>Use 8 or more characters with letters and numbers.</p>
            <p class="hint is-warn" data-caps-hint hidden><i class="fa-solid fa-circle-exclamation"></i> Caps Lock is on</p>
        </div>

        <div class="field">
            <label for="password_confirmation">Confirm new password</label>
            <div class="ctl">
                <i class="fa-solid fa-lock ic"></i>
                <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Type it once more" autocomplete="new-password" required data-confirm>
                <button type="button" class="eye" data-eye aria-label="Show password" aria-pressed="false"><i class="fa-regular fa-eye"></i></button>
            </div>
            <p class="hint" data-match hidden></p>
        </div>

        <button type="submit" class="btn btn-gold btn-block" data-submit><span>Reset password</span> <i class="fa-solid fa-arrow-right"></i></button>
    </form>
</div>
@endsection
