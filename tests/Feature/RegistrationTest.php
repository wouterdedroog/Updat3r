<?php

use App\Models\User;
use function Pest\Faker\faker;

it('is possible to register', function() {
    $name = faker()->name;
    $email = faker()->email;
    $this->post(route('register'), [
        'name' => $name,
        'email' => $email,
        'password' => 'Welkom01!',
        'password_confirmation' => 'Welkom01!',
    ])->assertRedirect(route('dashboard'));

    $this->assertDatabaseHas('users', [
        'name' => $name,
        'email' => $email,
    ]);
});


it('is impossible to register with an existing email', function () {
    User::factory()->create();

    $this->post(route('register'), [
        'name' => faker()->name,
        'email' => App\Models\User::first()->email,
        'password' => 'Welkom01!',
        'password_confirmation' => 'Welkom01!',
    ])->assertSessionHasErrors('email');
});
