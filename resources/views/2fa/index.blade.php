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
            @if(count($user->twoFactorMethods) == 0)
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
                        @foreach($user->twoFactorMethods as $twoFactorMethod)
                            <tr>
                                <td class="text-{{ $twoFactorMethod->enabled ? 'success' : 'danger' }}">
                                    <i class="far fa-{{ $twoFactorMethod->enabled ? 'check-circle' : 'times-circle' }}"></i>
                                </td>
                                <td>{{ $twoFactorMethod->name }}</td>
                                <td>{{ isset($twoFactorMethod->google2fa_secret) ? 'Mobile Device' : 'Yubikey' }}</td>
                                <td>
                                    <form action="{{ route('users.twofactormethods.update', ['user' => $user, 'twofactormethod' => $twoFactorMethod]) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" name="enabled" class="btn btn-{{$twoFactorMethod->enabled ? 'outline-danger' : 'brand'}}"
                                            value="{{ $twoFactorMethod->enabled ? 0 : 1 }}">
                                            {{ $twoFactorMethod->enabled ? 'Disable' : 'Enable' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <form action="{{ route('users.twofactormethods.destroy', ['user' => $user, 'twofactormethod' => $twoFactorMethod]) }}" method="POST">
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

            <button type="button" class="btn btn-outline-brand" data-toggle="modal" data-target="#add2FAModal">
                <i class="fas fa-plus-circle"></i> Add 2FA method
            </button>

            <!-- <add 2fa method modal> -->
            <div class="modal fade" id="add2FAModal" tabindex="-1" role="dialog"
                 aria-labelledby="add2FAModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="add2FAModalLabel">
                                <button class="btn btn-link" type="button" data-toggle="collapse" id="G2FAHeading"
                                        data-target="#collapseG2FA" aria-expanded="true" aria-controls="collapseG2FA">
                                    Google 2FA
                                </button>
                                |
                                <button class="btn btn-link" type="button" data-toggle="collapse" id="YubiOTPHeading"
                                        data-target="#collapseYubiOTP" aria-expanded="false" aria-controls="collapseYubiOTP">
                                    Yubikey OTP
                                </button>
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="collapseG2FA" class="collapse show" aria-labelledby="G2FAHeading" data-parent="#add2FAModal">
                                {!! $two_factor_image !!}
                                <br/>
                                <i>Scan this QR code with your phone, or add the 2FA code manually: <code>{{ $two_factor_secret }}</code></i>
                                <form method="POST" action="{{ route('users.twofactormethods.store', ['user' => $user]) }}" class="mt-3">
                                    @csrf
                                    <input type="hidden" name="two_factor_secret" value="{{ $two_factor_secret }}">
                                    <div class="form-group">
                                        <label for="name">Give your new 2FA method a name:</label>
                                        <input name="name" id="name" class="form-control" type="text" placeholder="My smartphone" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="two_factor_check">A newly generated 2FA code:</label>
                                        <input name="two_factor_check" id="two_factor_check" class="form-control" type="text" placeholder="123456" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" class="form-control btn btn-primary" value="Add 2FA method">
                                    </div>
                                </form>
                            </div>
                            <div id="collapseYubiOTP" class="collapse" aria-labelledby="YubiOTPHeading" data-parent="#add2FAModal">
                                <form method="POST" action="{{ route('users.twofactormethods.store', ['user' => $user]) }}" class="mt-3">
                                    @csrf
                                    <div class="form-group">
                                        <label for="name">Give your new 2FA method a name:</label>
                                        <input name="name" id="name" class="form-control" type="text" placeholder="My Yubikey" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="yubikey_otp">A newly generated Yubikey OTP:</label>
                                        <input name="yubikey_otp" id="yubikey_otp" class="form-control" type="text" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" class="form-control btn btn-primary" value="Add 2FA method">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- </add 2fa method modal> -->
        </div>
    </div>
@endsection
