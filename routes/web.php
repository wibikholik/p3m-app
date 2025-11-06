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
use App\Http\Controllers\Dosen\DosenPengumumanController;
use App\Http\Controllers\Dosen\UsulanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman awal (public)
Route::get('/', function () {
    return view('welcome');
});

// Redirect sesuai role
Route::get('/redirect', function () {
    $user = Auth::user();
    if (!$user) return redirect('/');
    return match ($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'reviewer' => redirect()->route('reviewer.dashboard'),
        default => redirect()->route('dosen.dashboard'),
    };
})->middleware(['auth', 'verified'])->name('redirect');

// Dashboard & Profile (semua role)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->middleware('role:admin')->name('admin.dashboard');
    Route::get('/reviewer/dashboard', [ReviewerController::class, 'index'])->middleware('role:reviewer')->name('reviewer.dashboard');
    Route::get('/dashboard', [DosenController::class, 'index'])->middleware('role:dosen')->name('dosen.dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ======================================================================
// 🔹 ADMIN ROUTES 🔹
// ======================================================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('kategori-pengumuman', KategoriPengumumanController::class);
    Route::resource('pengumuman', PengumumanController::class);
    Route::resource('users', UserController::class)->except(['show']);
    Route::post('users/{user}/block', [UserController::class, 'block'])->name('users.block');
    Route::post('users/{user}/unblock', [UserController::class, 'unblock'])->name('users.unblock');
});

// ======================================================================
// 🔹 DOSEN ROUTES 🔹
// ======================================================================
Route::prefix('dosen')->middleware(['auth', 'role:dosen'])->group(function () {

    // Pengumuman
    Route::get('/pengumuman', [DosenPengumumanController::class, 'index'])->name('dosen.pengumuman.index');
    Route::get('/pengumuman/{id}', [DosenPengumumanController::class, 'show'])->name('dosen.pengumuman.show');

    // Usulan
    Route::get('/usulan/create/{id_pengumuman}', [UsulanController::class, 'create'])
        ->name('dosen.usulan.create');

    Route::post('/usulan/store', [UsulanController::class, 'store'])
        ->name('dosen.usulan.store');
});

// Auth routes (login, register, logout, dll) dari Breeze
require __DIR__ . '/auth.php';
