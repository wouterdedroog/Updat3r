@extends('layouts.auth')

@section('splash-description', 'Please enter your one-time password code.')

@section('content')
    <form method="POST" action="{{ route('2fa.verify_otp') }}">
        @csrf
        @include('inc.messages')

        <div class="form-group">
            <input id="otp" type="text" class="form-control @error('otp') is-invalid @enderror"
                   name="otp" required autocomplete="off" placeholder="123456">

            @error('otp')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary btn-lg btn-block"> {{ __('Submit') }}</button>

    </form>
@endsection
