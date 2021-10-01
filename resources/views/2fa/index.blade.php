@extends('layouts.dashboard')

@section('breadcrumb_child', 'Your Profile')
@section('breadcrumb_child_target', route('users.show', ['user' => $user]))

@section('breadcrumb', '2FA Methods')
@section('title', 'Your 2FA Methods')

@section('content')
    @include('inc.messages')
    <div class="card">
        <h5 class="card-header d-flex justify-content-between align-items-center">
            <span>Your 2FA Methods</span>
        </h5>
        <div class="card-body">
            @if(count($user->two_factor_methods) == 0)
                <p>You currently do not have any 2FA methods set up.</p>
            @else
                <table class="table col-10">
                    <thead>
                        <tr>
                            <th scope="col" class="col-1">Status</th>
                            <th scope="col" class="col-3">Name</th>
                            <th scope="col" class="col-3">Method</th>
                            <th scope="col" class="col-2"></th>
                            <th scope="col" class="col-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->two_factor_methods as $two_factor_method)
                            <tr>
                                <td class="text-{{ $two_factor_method->enabled ? 'success' : 'danger' }}">
                                    <i class="far fa-{{ $two_factor_method->enabled ? 'check-circle' : 'times-circle' }}"></i>
                                </td>
                                <td>{{ $two_factor_method->name }}</td>
                                <td>{{ isset($two_factor_method->google2fa_secret) ? 'Mobile Device' : 'TBD' }}</td>
                                <td>
                                    <form action="{{ route('users.twofactormethods.update', ['user' => $user, 'twofactormethod' => $two_factor_method]) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" name="enabled" class="btn btn-{{$two_factor_method->enabled ? 'outline-danger' : 'brand'}}"
                                            value="{{ $two_factor_method->enabled ? 0 : 1 }}">
                                            {{ $two_factor_method->enabled ? 'Disable' : 'Enable' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <form action="{{ route('users.twofactormethods.destroy', ['user' => $user, 'twofactormethod' => $two_factor_method]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn text-danger">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <button type="button" class="btn btn-outline-brand" data-toggle="modal" data-target="#google2faModal">
                <i class="fas fa-plus-circle"></i> Add 2FA method
            </button>

            <!-- <google 2fa modal> -->
            <div class="modal fade" id="google2faModal" tabindex="-1" role="dialog"
                 aria-labelledby="google2faModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="google2faModalLabel">Google 2FA</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            {!! $two_factor_image !!}
                            <br/>
                            <i>Scan this QR code with your phone, or add the 2FA code manually: <code>{{ $two_factor_secret }}</code></i>
                            <form method="POST" action="{{ route('users.twofactormethods.store', ['user' => $user]) }}" class="mt-3">
                                @csrf
                                <input type="hidden" name="two_factor_secret" value="{{ $two_factor_secret }}">
                                <div class="form-group">
                                    <label for="two_factor_check">Your newly generated 2FA code:</label>
                                    <input name="two_factor_check" id="two_factor_check" class="form-control" type="text" placeholder="123456" required>
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="form-control btn btn-primary" value="Add 2FA method">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- </google 2fa modal> -->
        </div>
    </div>
@endsection
