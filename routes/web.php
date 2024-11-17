<?php

use App\Http\Controllers\FirebaseAuthController;
use Illuminate\Support\Facades\Route;
use Kreait\Firebase\Exception\FirebaseException;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('register', [FirebaseAuthController::class, 'showRegisterForm'])->name('register');
Route::post('register', [FirebaseAuthController::class, 'register'])->name('register.submit');

Route::get('login', [FirebaseAuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [FirebaseAuthController::class, 'login'])->name('login.submit');
