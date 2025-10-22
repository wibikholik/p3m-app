<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReviewerController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\Admin\PengumumanController;
use App\Http\Controllers\Admin\KategoriPengumumanController;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman awal (public)
Route::get('/', function () {
    return view('welcome');
});

// ... (Bagian redirect dan dashboard lainnya biarkan sama) ...
Route::get('/redirect', function () {
    $user = Auth::user();
    if (!$user) { return redirect('/'); }
    return match ($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'reviewer' => redirect()->route('reviewer.dashboard'),
        default => redirect()->route('dosen.dashboard'),
    };
})->middleware(['auth', 'verified'])->name('redirect');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->middleware('role:admin')->name('admin.dashboard');
    Route::get('/reviewer/dashboard', [ReviewerController::class, 'index'])->middleware('role:reviewer')->name('reviewer.dashboard');
    Route::get('/dashboard', [DosenController::class, 'index'])->middleware('role:dosen')->name('dosen.dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// ======================================================================
// 🔹 SEMUA RUTE ADMIN DIGABUNGKAN DI SINI (LEBIH BERSIH DAN AMAN) 🔹
// ======================================================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Menggunakan Route::resource untuk Kategori (Nama rute: admin.kategori-pengumuman.*)
    Route::resource('kategori-pengumuman', KategoriPengumumanController::class);
    
    // Menggunakan Route::resource untuk Pengumuman (Nama rute: admin.pengumuman.*)
    Route::resource('pengumuman', PengumumanController::class);

});
Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('users', UserController::class)->except(['show']);

        Route::post('users/{user}/block', [UserController::class, 'block'])->name('users.block');
        Route::post('users/{user}/unblock', [UserController::class, 'unblock'])->name('users.unblock');
    });


// Auth routes (login, register, logout, dll) dari Breeze
require __DIR__ . '/auth.php';

