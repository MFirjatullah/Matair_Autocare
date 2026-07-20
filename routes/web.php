<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\RatingController;

// ── Public ────────────────────────────────────────────────────
Route::get('/', function () {
    $services = \App\Models\Service::where('is_active', true)
                    ->get()->groupBy('category');
    return view('home', compact('services'));
})->name('home');

// ── Auth ──────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login']);
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// ── Guest redirect untuk reservasi ───────────────────────────
// HARUS di atas group auth agar nama route tersedia untuk semua kondisi
Route::get('/reservasi', function () {
    return redirect()->route('login')
        ->with('info', 'Silakan login terlebih dahulu untuk membuat reservasi.');
})->middleware('guest')->name('reservasi');

// ── Customer ──────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Reservasi - pakai prefix agar tidak konflik dengan guest route di atas
    Route::get('/reservasi', [ReservationController::class, 'index'])
        ->name('reservasi');
    Route::post('/reservasi', [ReservationController::class, 'store'])
        ->name('reservasi.store');
    Route::get('/reservasi/sukses/{reservation}', [ReservationController::class, 'success'])
        ->name('reservasi.success');
    Route::get('/riwayat', [ReservationController::class, 'history'])
        ->name('reservasi.history');
    Route::get('/check-slots', [ReservationController::class, 'checkSlots'])
        ->name('reservasi.check-slots');

    // Rating
    Route::get('/rating/{reservation}', [RatingController::class, 'create'])
        ->name('rating.create');
    Route::post('/rating/{reservation}', [RatingController::class, 'store'])
        ->name('rating.store');

});

// ── Karyawan ──────────────────────────────────────────────────
Route::prefix('karyawan')
    ->middleware(['auth', \App\Http\Middleware\KaryawanMiddleware::class])
    ->name('karyawan.')
    ->group(function () {

        Route::get('/', [KaryawanController::class, 'dashboard'])
            ->name('dashboard');

        Route::post('/pesanan/{reservation}/terima', [KaryawanController::class, 'terima'])
            ->name('pesanan.terima');

        Route::post('/pesanan/{reservation}/tolak', [KaryawanController::class, 'tolak'])
            ->name('pesanan.tolak');

        Route::patch('/pesanan/{reservation}/status', [KaryawanController::class, 'updateStatus'])
            ->name('pesanan.status');

        Route::post('/toggle-availability', [KaryawanController::class, 'toggleAvailability'])
            ->name('toggle-availability');

    });

// ── Admin ─────────────────────────────────────────────────────
Route::prefix('admin')
    ->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->name('admin.')
    ->group(function () {

        Route::get('/', [AdminController::class, 'dashboard'])
            ->name('dashboard');

        // Reservasi
        Route::get('/reservasi', [AdminController::class, 'reservations'])
            ->name('reservations');
        Route::patch('/reservasi/{reservation}/status', [AdminController::class, 'updateStatus'])
            ->name('reservations.update');
        Route::post('/reservasi/{reservation}/assign', [AdminController::class, 'assignKaryawan'])
            ->name('reservations.assign');
        Route::post('/reservasi/{reservation}/reassign', [AdminController::class, 'reassignKaryawan'])
            ->name('reservations.reassign');

        // Pelanggan
        Route::get('/pelanggan', [AdminController::class, 'customers'])
            ->name('customers');

        // Karyawan
        Route::get('/karyawan', [AdminController::class, 'karyawan'])
            ->name('karyawan');
        Route::post('/karyawan', [AdminController::class, 'karyawanStore'])
            ->name('karyawan.store');
        Route::delete('/karyawan/{user}', [AdminController::class, 'karyawanDestroy'])
            ->name('karyawan.destroy');

        // Layanan
        Route::get('/layanan', [AdminController::class, 'services'])
            ->name('services');
        Route::patch('/layanan/{service}/toggle', [AdminController::class, 'toggleService'])
            ->name('services.toggle');

        // Laporan
        Route::get('/laporan', [AdminController::class, 'laporanIndex'])
            ->name('laporan');
        Route::get('/laporan/export', [AdminController::class, 'laporanExportPdf'])
            ->name('laporan.export');

    });