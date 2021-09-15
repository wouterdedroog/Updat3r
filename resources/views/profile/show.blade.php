@extends('layouts.dashboard')

@section('breadcrumb', 'Your Profile')
@section('title', 'Your Profile')

@section('content')
    @include('inc.messages')
    <div class="card">
        <h5 class="card-header d-flex justify-content-between align-items-center">
            <span>Your details</span>
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmDeletion">
                <i class="fas fa-trash mr-2"></i>Delete account
            </button>
        </h5>
        <div class="card-body">
            <p class="mb-0"><strong>Your name</strong></p>
            <p>{{ $user->name }}</p>

            <p class="mb-0"><strong>Your email address</strong></p>
            <p>{{ $user->email }}</p>

            <div class="btn-group" role="group">
                <a class="btn btn-primary mr-2" href="{{ route('users.edit', ['user' => $user]) }}"><i class="fas fa-pencil-alt mr-2"></i>Edit</a>
                {{--<a class="btn btn-primary" href="{{ route('users.edit', ['user' => $user]) }}"><i class="fas fa-lock mr-2"></i>Setup 2FA</a>--}}
            </div>
        </div>
    </div>

    <!-- <account deletion modal> -->
    <div class="modal fade" id="confirmDeletion" tabindex="-1" role="dialog" aria-labelledby="confirmDeletionLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeletionLabel">Confirm account deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-danger text-center">Deleting your account is irreversible. Proceed with caution.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                    <form id="delete-account-form" action="{{ route('users.destroy', ['user' => $user]) }}"
                          method="POST">
                        @method('DELETE')
                        @csrf
                        <button type="submit" class="btn btn-danger">Delete my account</button>
                    </form>
                </div>
            </div>
        </div>g
    </div>
    <!-- </account deletion modal> -->
@endsection
