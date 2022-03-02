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

Route::get('/v1/updates/', [LegacyProjectController::class, 'show']);
Route::get('/v1/updates/download/', [LegacyProjectController::class, 'download']);

Route::get('/v2/updates/download/{project:name}/{version}', [ProjectController::class, 'download']);
Route::get('/v2/updates/{project:name}/{filter}', [ProjectController::class, 'show'])
    ->where(['filter' => '([0-9]+|latest)']);
