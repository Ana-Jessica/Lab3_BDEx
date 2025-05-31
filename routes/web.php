<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\RegisterController;

// Rotas públicas
Route::get('/', function () {
    return view('auth.login'); // Altere para sua página inicial desejada
});

// Rotas de autenticação
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    // Route::post('/register', [RegisterController::class, 'register'])->name('register');
    
    Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Rotas protegidas - Área da Empresa
Route::middleware(['auth:company', 'company'])->prefix('company')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Company\DashboardController::class, 'index'])->name('company.dashboard');
    // Adicione outras rotas da empresa aqui
});

// Rotas protegidas - Área do Desenvolvedor
Route::middleware(['auth:developer', 'developer'])->prefix('developer')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Developer\DashboardController::class, 'index'])->name('developer.dashboard');
    // Adicione outras rotas do desenvolvedor aqui
});

// Rota de logout (comum para ambos)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');