@extends('layouts.dashboard')

@section('breadcrumb', 'Edit Profile')
@section('title', 'Edit Profile')

@section('content')
<div class="card">
    <h5 class="card-header">Edit profile {{ $user->name }}</h5>
    <div class="card-body">
        @include('inc.messages')
        <form action="{{ route('users.update', ['user' => $user]) }}" method="POST">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="name">Your name</label>
                <input class="form-control form-control-lg" id="name" name="name" type="text"
                    value="{{ $user->name }}" autocomplete="off" required>
            </div>
            <div class="form-group">
                <label for="email">Your email address</label>
                <input class="form-control form-control-lg" id="email" name="email" type="email"
                       value="{{ $user->email }}" autocomplete="off" required>
            </div>
            <hr/>
            <p>If you want to change your password, you can type a new one below, or leave the fields blank.</p>
            <div class="form-group">
                <label for="current_password">Current password</label>
                <input class="form-control form-control-lg" id="current_password"
                       name="current_password" type="password">
            </div>
            <div class="form-group">
                <label for="password">New password</label>
                <input class="form-control form-control-lg" id="password" name="password" type="password">
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirm new password</label>
                <input class="form-control form-control-lg" id="password_confirmation"
                       name="password_confirmation" type="password">
            </div>

            <button type="submit" class="btn btn-primary btn-lg btn-block">Change</button>
        </form>
    </div>
</div>
@endsection
