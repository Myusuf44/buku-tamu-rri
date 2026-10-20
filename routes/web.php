<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\SuperAdminMiddleware;

// === ROUTE PUBLIK (Buku Tamu / Pendaftaran) ===
Route::get('/', [GuestController::class, 'index'])->name('home');
Route::post('/feedback/store', [VisitorController::class, 'storeFeedback'])->name('feedback.store');
Route::get('/registrasi', [GuestController::class, 'registrasi'])->name('registrasi');
Route::post('/registrasi', [VisitorController::class, 'store'])->name('visitor.store');

// === ROUTE AUTENTIKASI ===
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'updatePassword'])->name('password.update');
});

// === ROUTE ADMIN (Wajib Login) ===
Route::middleware('auth')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::match(['put', 'patch', 'post'], '/visitor/{id}/status', [AdminController::class, 'updateVisitorStatus'])->name('visitor.status');
        Route::get('/feedbacks', [AdminController::class, 'manageFeedbacks'])->name('feedbacks');
        
        // --- RUTE AKSI STATUS & KIRIM EMAIL OTOMATIS ---
        Route::post('/visitors/{id}/approve', [AdminController::class, 'approveVisitor'])->name('visitors.approve');
        Route::post('/visitors/{id}/complete', [AdminController::class, 'completeVisitor'])->name('visitors.complete');
        Route::post('/visitors/{id}/cancel', [AdminController::class, 'cancelVisitor'])->name('visitors.cancel');
        // ---------------------------------------------

        // Rute Ekspor Laporan Kunjungan Tamu (PDF & Excel)
        Route::get('/export/pdf', [VisitorController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/export/excel', [VisitorController::class, 'exportExcel'])->name('export.excel');

        // Rute Ekspor Laporan Ulasan / Feedback (PDF & Excel)
        Route::get('/export/feedback/pdf', [AdminController::class, 'exportFeedbackPdf'])->name('export.feedback.pdf');
        Route::get('/export/feedback/excel', [AdminController::class, 'exportFeedbackExcel'])->name('export.feedback.excel');

        // Rute Khusus Super Admin (Manajemen User/Petugas)
        Route::middleware(SuperAdminMiddleware::class)->group(function () {
            Route::get('/users', [AdminController::class, 'indexUsers'])->name('users.index');
            Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
            Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
            Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('users.destroy');
        });
    });
});