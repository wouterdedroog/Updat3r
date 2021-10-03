<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTwoFactorMethodRequest;
use App\Http\Requests\UpdateTwoFactorMethodRequest;
use App\Providers\RouteServiceProvider;
use Exception;
use Illuminate\Support\Arr;
use Bitbeans\Yubikey\YubikeyFacade as Yubikey;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use PragmaRX\Google2FAQRCode\Google2FA;
use App\TwoFactorMethod;
use App\User;
use Illuminate\Http\Request;

class TwoFactorMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param User $user
     * @return Application|Factory|View
     */
    public function index(User $user)
    {
        $google2fa = new Google2FA();
        $two_factor_secret = $google2fa->generateSecretKey(32);
        $qrCodeUrl = $google2fa->getQRCodeInline(
            config('app.name'),
            $user->email,
            $two_factor_secret
        );

        return view('2fa.index')->with([
            'user' => $user,
            'two_factor_secret' => $two_factor_secret,
            'two_factor_image' => $qrCodeUrl,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreTwoFactorMethodRequest $request
     * @param User $user
     * @return RedirectResponse
     */
    public function store(StoreTwoFactorMethodRequest $request, User $user)
    {
        $data = $request->validated();

        if (Arr::has($data, 'yubikey_otp')) {
            try {
                // Validate yubikey with a timeout of max. 2 seconds
                Yubikey::verify($data['yubikey_otp'], null, false, null, 2);

                $prefix = Yubikey::parsePasswordOTP($data['yubikey_otp'])['prefix'];
                $two_factor_method = new TwoFactorMethod([
                    'name' => $data['name'],
                    'yubikey_otp' => encrypt($prefix)
                ]);
                $user->two_factor_methods()->save($two_factor_method);
                $request->session()->flash('success', 'Successfully added a new 2FA method');

                // Update session to prevent user needing to type in OTP
                $request->session()->put('2fa_method', $two_factor_method->id);
            } catch (Exception $exception) {
                if ($exception->getMessage() == 'REPLAYED_OTP') {
                    $request->session()->flash('error', 'The supplied OTP has been used before.');
                }
                $request->session()->flash('error', 'Invalid OTP supplied. Please try again!');
            }
        } else {
            $google2fa = new Google2FA();

            if ($google2fa->verifyKey($data['two_factor_secret'], $data['two_factor_check'], 8)) {
                $two_factor_method = new TwoFactorMethod([
                    'name' => $data['name'],
                    'google2fa_secret' => encrypt($data['two_factor_secret'])
                ]);
                $user->two_factor_methods()->save($two_factor_method);
                $request->session()->flash('success', 'Successfully added a new 2FA method');

                // Update session to prevent user needing to type in OTP
                $request->session()->put('2fa_method', $two_factor_method->id);
            } else {
                $request->session()->flash('error', 'Invalid 2FA code supplied. Please try again!');
            }
        }
        return redirect()->route('users.twofactormethods.index', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTwoFactorMethodRequest $request
     * @param User $user
     * @param $twoFactorMethod
     * @return RedirectResponse
     */
    public function update(UpdateTwoFactorMethodRequest $request, User $user, $twoFactorMethod)
    {
        $data = $request->validated();
        $two_factor_method = TwoFactorMethod::find($twoFactorMethod);
        if ($two_factor_method->update($data)) {
            $request->session()->flash('success', 'Successfully changed this 2FA method.');

            // Update session to prevent user needing to type in OTP
            $request->session()->put('2fa_method', $two_factor_method->id);
        } else {
            $request->session()->flash('error', 'Something went wrong when changing this 2FA method.');
        }
        return redirect()->route('users.twofactormethods.index', ['user' => $user]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param TwoFactorMethod $twoFactorMethod
     * @return RedirectResponse
     */
    public function destroy(Request $request, User $user, $twoFactorMethod)
    {
        if (TwoFactorMethod::destroy($twoFactorMethod)) {
            $request->session()->flash('success', 'Successfully deleted this 2FA method.');
        } else {
            $request->session()->flash('error', 'Something went wrong whilst deleting this 2FA method.');
        }
        return redirect()->route('users.twofactormethods.index', ['user' => $user]);
    }

    /**
     * Verify the 2FA OTP
     *
     * @param Request $request
     */
    public function verify_otp(Request $request)
    {
        $data = $request->validate([
            'otp' => [
                'required',
                'min:6',
                'max:44'
            ]
        ]);
        $two_factor_methods = $request->user()->two_factor_methods()
            ->select(['id', 'google2fa_secret', 'yubikey_otp'])
            ->where('enabled', true)
            ->get();

        $google2fa = new Google2FA();
        $correctOtps = $two_factor_methods->filter(function ($two_factor_method) use ($google2fa, $data) {
            if ($two_factor_method->google2fa_secret != null) {
                return $google2fa->verifyKey(decrypt($two_factor_method->google2fa_secret), $data['otp'], 2);
            } else {
                $parsedOtp = Yubikey::parsePasswordOTP($data['otp']);
                if ($parsedOtp && decrypt($two_factor_method->yubikey_otp) == $parsedOtp['prefix']) {
                    try {
                        // Validate yubikey with a timeout of max. 2 seconds
                        return Yubikey::verify($data['otp'], null, false, null, 2);
                    } catch (Exception $ex) {
                        return false;
                    }
                }
            }
            return false;
        });

        if ($correctOtps->count() == 0) {
            return redirect(RouteServiceProvider::HOME)->with(['error' => 'This OTP is not valid.']);
        }

        $request->session()->put('2fa_method', $correctOtps->first()->id);
        return redirect(RouteServiceProvider::HOME);
    }
}
