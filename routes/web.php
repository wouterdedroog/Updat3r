<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TwoFactorMethodController;
use App\Http\Controllers\UpdateController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::view('/about', 'about');

Route::post('/verify-otp', [TwoFactorMethodController::class, 'verify_otp'])
    ->middleware('auth')->name('2fa.verify_otp');

Route::middleware(['auth', '2fa'])->group(function () {
    Route::get('/', [ProjectController::class, 'index'])->name('dashboard');
    Route::view('/documentation', 'dashboard.documentation')->name('documentation');

    Route::resource('projects', ProjectController::class)->except(['index', 'edit']);
    Route::resource('projects.updates', UpdateController::class)->only(['store', 'update', 'destroy']);

    Route::resource('users', UserController::class)->except(['index', 'create', 'store']);
    Route::resource('users.twofactormethods', TwoFactorMethodController::class)->except(['show', 'create', 'edit']);
});
