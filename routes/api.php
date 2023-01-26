<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;

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
Route::post('/login', [LoginController::class, 'login'])->name('user.auth.login');
Route::post('/register', [RegisterController::class, 'register'])->name('user.auth.register');
Route::post('/register/confirm-email', [RegisterController::class, 'confirmEmail'])->name('user.auth.register.confirm-email');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/logout', [LogoutController::class, 'logout'])->name('user.auth.logout');
});
