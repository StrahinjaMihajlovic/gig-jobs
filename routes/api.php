<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\GigController;
use App\Http\Controllers\UserController;
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

Route::put('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/user/', 'showProfile');
        Route::patch('/user/update', 'updateProfile');
    });

    Route::controller(CompanyController::class)->group(function () {
        Route::get('/companies', 'index');
        Route::get('companies/{company}', 'show')->can('view', 'company');
        Route::put('/companies/create', 'store');
        Route::patch('companies/{company}/update', 'update')->can('update', 'company');
        Route::delete('companies/{company}/delete', 'destroy')->can('delete', 'company');
    });

    Route::controller(GigController::class)->group(function () {
        Route::get('/gigs', 'index');
        Route::get('gigs/{gig}', 'show')->can('view', 'gig');
        Route::put('/gigs/create', 'store');
        Route::patch('gigs/{gig}/update', 'update')->can('update', 'gig');
        Route::delete('gigs/{gig}/delete', 'destroy')->can('delete', 'gig');
    });
});
