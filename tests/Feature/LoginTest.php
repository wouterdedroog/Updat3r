<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use function Pest\Faker\faker;

it('is possible to login', function () {
    $password = faker()->password;
    $user = User::factory()->create([
        'password' => Hash::make($password),
    ]);
    $this->post(route('login'), [
        'email' => $user->email,
        'password' => $password,
    ])->assertSessionHasNoErrors()
        ->assertRedirect(route('dashboard'));
});

it('is not possible to login with an incorrect password', function () {
    $user = User::factory()->create();
    $this->post(route('login'), [
        'email' => $user->email,
        'password' => faker()->password,
    ])->assertSessionHasErrors('email');
});
