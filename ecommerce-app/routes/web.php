<?php

use App\Http\Controllers\AuthWebController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    return redirect()->route('dashboard');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', [AuthWebController::class, 'authenticate'])->name('login');
Route::post('/logout', [AuthWebController::class, 'logout'])->name('logout');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/sso-foodpanda', [AuthWebController::class, 'SSOAuthRedirect']);
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
});