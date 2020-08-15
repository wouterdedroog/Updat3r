<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::get('/v2/updates/?project={project}&key={key}&show={filter}', 'ApiController\LegacyProjectController@show');
//Route::get('/v1/updates/?project={project}&key={key}&show={filter}', function($project, $key, $filter) {
 //   return 'yeet';
//});
Route::get('/v1/updates/', 'ApiController\LegacyProjectController@show');
Route::get('/v1/updates/download/', 'ApiController\LegacyProjectController@download');

Route::get('/v2/updates/download/{project}/{version}', 'ApiController\ProjectController@download');
Route::get('/v2/updates/{project}/{filter}', 'ApiController\ProjectController@show');
