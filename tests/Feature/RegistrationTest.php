<?php

use function Pest\Faker\faker;

it('is possible to register', function() {
    $this->post(route('register'), [
        'name' => faker()->name,
        'email' => faker()->email,
        'password' => 'Welkom01!',
        'password_confirmation' => 'Welkom01!',
    ])->assertRedirect(route('dashboard'));
});


it('is impossible to register with an existing email', function () {
    $this->post(route('register'), [
        'name' => faker()->name,
        'email' => App\Models\User::first()->email,
        'password' => 'Welkom01!',
        'password_confirmation' => 'Welkom01!',
    ])->assertSessionHasErrors('email');
});
