<?php

use App\Http\Controllers\ApiController\LegacyProjectController;
use App\Http\Controllers\ApiController\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(LegacyProjectController::class)->prefix('/v1/updates')->name('api.v1.updates.')
    ->group(function () {
        Route::get('/', 'show')->name('show');
        Route::get('/download/', 'download')->name('download');
    });

Route::controller(ProjectController::class)->prefix('/v2/updates')->name('api.v2.updates.')
    ->group(function () {
        Route::get('/{project:name}/{filter}', 'show')->where(['filter' => '([0-9]+|latest)'])->name('show');
        Route::get('/download/{project:name}/{version}', 'download')->name('download');
    });
