<?php

use App\Models\TwoFactorMethod;
use App\Models\User;
use PragmaRX\Google2FAQRCode\Google2FA;
use function Pest\Faker\faker;

it('is possible to create two factor methods', function () {
    $user = User::factory()->create();

    $google2fa = new Google2FA();
    $secret = $google2fa->generateSecretKey(32);
    $otp = $google2fa->getCurrentOtp($secret);

    $userData = ['name' => faker()->word, 'two_factor_check' => $otp, 'two_factor_secret' => $secret];
    $this->actingAs($user)->post(route('users.twofactormethods.store', ['user' => $user]), $userData)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('users.twofactormethods.index', ['user' => $user]))
        ->assertSessionHas('2fa_method');

    expect($user->twoFactorMethods)->toHaveCount(1);
});

it('is possible to disable two factor methods', function () {
    $user = User::factory()
        ->has(TwoFactorMethod::factory()->enabled())
        ->create();
    $twofactormethod = $user->twoFactorMethods->first();

    $this->actingAs($user)
        ->session(['2fa_method' => $twofactormethod->id])
        ->put(route('users.twofactormethods.update', ['user' => $user, 'twofactormethod' => $twofactormethod]),
            ['enabled' => false])
        ->assertSessionHasNoErrors()
        ->assertSessionHas('success')
        ->assertRedirect(route('users.twofactormethods.index', ['user' => $user]));

    expect($twofactormethod->fresh()->attributesToArray())->toMatchArray(['enabled' => false]);
});

it('is possible to delete two factor methods', function () {
    $user = User::factory()
        ->has(TwoFactorMethod::factory()->enabled())
        ->create();
    $twofactormethod = $user->twoFactorMethods->first();

    $this->actingAs($user)
        ->session(['2fa_method' => $twofactormethod->id])
        ->delete(route('users.twofactormethods.destroy', ['user' => $user, 'twofactormethod' => $twofactormethod]))
        ->assertSessionHasNoErrors()
        ->assertSessionHas('success')
        ->assertRedirect(route('users.twofactormethods.index', ['user' => $user]));

    expect($user->fresh()->twoFactorMethods)->toBeEmpty();
});
