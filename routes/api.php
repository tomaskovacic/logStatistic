<?php

use App\Http\Controllers\UploadController;
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


Route::get('getFilenames', [UploadController::class, 'getFilenames']);
Route::get('getData/{value}', [UploadController::class, 'getData']);
Route::get('getNumber/{value}', [UploadController::class, 'getNumber']);
Route::get('getErrors/{value}', [UploadController::class, 'getErrors']);
Route::get('sort/{value}', [UploadController::class, 'sort']);
