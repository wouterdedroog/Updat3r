<?php

use App\Models\User;
use App\Providers\RouteServiceProvider;
use function Pest\Faker\faker;

it('is possible to register')
    ->post('/register', [
        'name' => faker()->name(),
        'email' => faker()->email(),
        'password' => 'Welkom01!',
        'password_confirmation' => 'Welkom01!',
    ])
    ->assertSessionHasNoErrors()
    ->assertRedirect(RouteServiceProvider::HOME);

it('is impossible to register with an existing email')
    ->tap(fn() => User::factory()->create(['email' => 'wouter@example.com']))
    ->post('/register', [
        'name' => faker()->name(),
        'email' => 'wouter@example.com',
        'password' => faker()->password(),
        'password_confirmation' => faker()->password(),
    ])->assertSessionHasErrors(['email' => 'The email has already been taken.']);
