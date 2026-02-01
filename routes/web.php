<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SantriController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LeaderboardController;

// --- 1. JALUR UMUM (Bisa diakses siapa saja) ---
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/santri/{id}/cetak', [SantriController::class, 'cetakPdf'])->name('santri.cetakPdf');
Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');

// --- 2. JALUR RAHASIA (Harus Login Dulu) ---
// Kita bungkus semua route lama dengan middleware 'auth'
Route::middleware(['auth'])->group(function () {

    // Dashboard Utama
    Route::get('/', [SantriController::class, 'index'])->name('santri.index');

    // CRUD Santri
    Route::post('/santri', [SantriController::class, 'store'])->name('santri.store');
    Route::get('/santri/{id}', [SantriController::class, 'show'])->name('santri.show');
    Route::get('/santri/{id}/edit', [SantriController::class, 'edit'])->name('santri.edit');
    Route::put('/santri/{id}', [SantriController::class, 'update'])->name('santri.update');
    Route::delete('/santri/{id}', [SantriController::class, 'destroy'])->name('santri.destroy');

    // CRUD Pelanggaran
    Route::post('/santri/{id}/violation', [SantriController::class, 'storeViolation'])->name('violation.store');
    Route::get('/violation/{id}/edit', [SantriController::class, 'editViolation'])->name('violation.edit');
    Route::put('/violation/{id}', [SantriController::class, 'updateViolation'])->name('violation.update');
    Route::delete('/violation/{id}', [SantriController::class, 'destroyViolation'])->name('violation.destroy');

    // CRUD Prestasi
    Route::post('/santri/{id}/achievement', [SantriController::class, 'storeAchievement'])->name('achievement.store');
    Route::get('/achievement/{id}/edit', [SantriController::class, 'editAchievement'])->name('achievement.edit');
    Route::put('/achievement/{id}', [SantriController::class, 'updateAchievement'])->name('achievement.update');
    Route::delete('/achievement/{id}', [SantriController::class, 'destroyAchievement'])->name('achievement.destroy');
});
