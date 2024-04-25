@extends('layouts.auth')

@section('splash-description', 'Please enter your information to log in.')

@section('content')
<form method="POST" action="{{ route('login') }}">
    @csrf
    @include('inc.messages')
    <div class="form-group">
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
            value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email">

        @error('email')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group">
        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
            name="password" required autocomplete="current-password" placeholder="Password">

        @error('password')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <button type="submit" class="btn btn-primary btn-lg btn-block"> {{ __('Login') }}</button>

</form>
@endsection

@section('switch-button')
    @if(Route::has('register'))
        <a href="/register" class="footer-link">Register</a>
    @endif
@endsection
