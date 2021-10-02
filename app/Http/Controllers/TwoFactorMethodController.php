<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTwoFactorMethodRequest;
use App\Http\Requests\UpdateTwoFactorMethodRequest;
use App\Providers\AppServiceProvider;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use PragmaRX\Google2FAQRCode\Google2FA;
use App\TwoFactorMethod;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
        $google2fa = new Google2FA();

        if ($google2fa->verifyKey($data['two_factor_secret'], $data['two_factor_check'], 8)) {
            $user->two_factor_methods()->save(new TwoFactorMethod([
                'name' => $data['name'],
                'google2fa_secret' => encrypt($data['two_factor_secret'])
            ]));
            $request->session()->flash('success', 'Successfully added a new 2FA method');
        } else {
            $request->session()->flash('error', 'Invalid 2FA code supplied. Please try again!');
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
        if (TwoFactorMethod::find($twoFactorMethod)->update($data)) {
            $request->session()->flash('success', 'Successfully changed this 2FA method.');
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
                'digits:6'
            ]
        ]);
        $two_factor_methods = $request->user()->two_factor_methods()
            ->select(['id', 'google2fa_secret'])
            ->where('enabled', true)
            ->get();

        $google2fa = new Google2FA();
        $correctOtps = $two_factor_methods->filter(function ($two_factor_method) use ($google2fa, $data) {
            return $google2fa->verifyKey(decrypt($two_factor_method->google2fa_secret), $data['otp']);
        });

        if ($correctOtps->count() == 0) {
            return redirect(RouteServiceProvider::HOME)->with(['error' => 'This OTP is not valid.']);
        }

        $request->session()->put('2fa_method', $correctOtps->first()->id);
        return redirect(RouteServiceProvider::HOME);
    }
}
