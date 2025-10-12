<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReviewerController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\Admin\PengumumanController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman awal (public)
Route::get('/', function () {
    return view('welcome');
});

// 🔹 Redirect otomatis setelah login sesuai role
Route::get('/redirect', function () {
    $user = Auth::user();

    if (!$user) {
        return redirect('/'); // belum login → balik ke welcome
    }

    // Redirect sesuai role
    return match ($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'reviewer' => redirect()->route('reviewer.dashboard'),
        default => redirect()->route('dosen.dashboard'),
    };
})->middleware(['auth', 'verified'])->name('redirect');

// 🔹 Dashboard untuk tiap role (hanya untuk user login & terverifikasi)
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard Admin
    Route::get('/admin/dashboard', [AdminController::class, 'index'])
        ->middleware('role:admin')
        ->name('admin.dashboard');

    // Dashboard Reviewer
    Route::get('/reviewer/dashboard', [ReviewerController::class, 'index'])
        ->middleware('role:reviewer')
        ->name('reviewer.dashboard');

    // Dashboard Dosen (default)
    Route::get('/dashboard', [DosenController::class, 'index'])
        ->middleware('role:dosen')
        ->name('dosen.dashboard');

    // 🔹 Routes bawaan untuk profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
// 🔹 Routes untuk pengumuman (hanya admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.index');
    Route::get('/pengumuman/create', [PengumumanController::class, 'create'])->name('pengumuman.create');
    Route::post('/pengumuman', [PengumumanController::class, 'store'])->name('pengumuman.store');
    Route::get('/pengumuman/{id}', [PengumumanController::class, 'show'])->name('pengumuman.show');
    Route::get('/pengumuman/{id}/edit', [PengumumanController::class, 'edit'])->name('pengumuman.edit');
    Route::put('/pengumuman/{id}', [PengumumanController::class, 'update'])->name('pengumuman.update');
    Route::delete('/pengumuman/{id}', [PengumumanController::class, 'destroy'])->name('pengumuman.destroy');
});



// 🔹 Auth routes (login, register, logout, dll) dari Breeze
require __DIR__ . '/auth.php';
