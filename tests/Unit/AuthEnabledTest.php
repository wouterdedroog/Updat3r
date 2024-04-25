<?php

use Illuminate\Support\Facades\Route;

test('the registration and password reset routes are disabled.')
    ->skip(fn() => config('updat3r.registration_enabled'), 'Registration is currently enabled')
    ->expect(fn($route) => Route::has($route))
    ->toBeFalse()
    ->with(['register', 'password.request', 'password.reset']);
