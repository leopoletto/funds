<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterUserController;
use App\Http\Controllers\FundController;
use App\Http\Controllers\ReconcileFundController;
use App\Http\Controllers\SeedFundController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/users', RegisterUserController::class);
Route::post('/login', LoginController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/funds/seed', SeedFundController::class);
    Route::post('/funds/{fund}/reconcile', ReconcileFundController::class);
    Route::resource('/funds', FundController::class);
});
