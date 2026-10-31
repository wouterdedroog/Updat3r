<?php

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;
use function Pest\Faker\faker;

it('is possible to login')
    ->defer(fn() => User::factory()->create([
        'email' => 'wouter@example.com',
        'password' => Hash::make('Welkom01!')
    ]))
    ->post('/login', [
        'email' => 'wouter@example.com',
        'password' => 'Welkom01!'
    ])
    ->assertSessionHasNoErrors()
    ->assertRedirect(RouteServiceProvider::HOME);

it('is not possible to login with an incorrect password')
    ->defer(fn() => User::factory(['email' => 'wouter@example.com'])->create())
    ->post('/login', [
        'email' => 'wouter@example.com',
        'password' => fake()->password(),
    ])->assertSessionHasErrors('email');
