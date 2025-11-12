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

// ======================================================================
// 🔹 HALAMAN AWAL (PUBLIC)
// ======================================================================
Route::get('/', function () {
    return view('welcome');
});

// ======================================================================
// 🔹 REDIRECT SESUAI ROLE
// ======================================================================
Route::get('/redirect', function () {
    $user = Auth::user();
    if (!$user) return redirect('/');

    return match ($user->role) {
        'admin'    => redirect()->route('admin.dashboard'),
        'reviewer' => redirect()->route('reviewer.dashboard'),
        default    => redirect()->route('dosen.dashboard'),
    };
})->middleware(['auth', 'verified'])->name('redirect');

// ======================================================================
// 🔹 DASHBOARD & PROFILE (UNTUK SEMUA ROLE)
// ======================================================================
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard sesuai role
    Route::get('/admin/dashboard', [AdminController::class, 'index'])
        ->middleware('role:admin')->name('admin.dashboard');

    Route::get('/reviewer/dashboard', [ReviewerController::class, 'index'])
        ->middleware('role:reviewer')->name('reviewer.dashboard');

    Route::get('/dashboard', [DosenController::class, 'index'])
        ->middleware('role:dosen')->name('dosen.dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ======================================================================
// 🔹 ADMIN ROUTES
// ======================================================================
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('kategori-pengumuman', KategoriPengumumanController::class);
        Route::resource('pengumuman', PengumumanController::class);
        Route::resource('users', UserController::class)->except(['show']);
        Route::post('users/{user}/block', [UserController::class, 'block'])->name('users.block');
        Route::post('users/{user}/unblock', [UserController::class, 'unblock'])->name('users.unblock');
    });

// ======================================================================
// 🔹 DOSEN ROUTES
// ======================================================================
Route::middleware(['auth', 'role:dosen'])
    ->prefix('dosen')
    ->name('dosen.')
    ->group(function () {

        // 🔸 Pengumuman
        Route::get('/pengumuman', [DosenPengumumanController::class, 'index'])
            ->name('pengumuman.index');

        Route::get('/pengumuman/{id}', [DosenPengumumanController::class, 'show'])
            ->name('pengumuman.show');

        // 🔸 Usulan
        Route::get('/usulan/create/{id_pengumuman}', [UsulanController::class, 'create'])
            ->name('usulan.create');

        Route::post('/usulan/store', [UsulanController::class, 'store'])
            ->name('usulan.store');

        Route::get('/usulan', [UsulanController::class, 'index'])
            ->name('usulan.index');

        Route::get('/usulan/{id}', [UsulanController::class, 'show'])
            ->name('usulan.show');
        Route::get('/search', [UsulanController::class, 'search'])->name('search');
    });
// 🔸 Usulan Dosen
Route::middleware(['auth', 'role:dosen'])
    ->prefix('dosen')
    ->name('dosen.')
    ->group(function () {
        // Daftar semua usulan
        Route::get('/usulan', [UsulanController::class, 'index'])
            ->name('usulan.index');

        // Detail usulan
        Route::get('/usulan/{id}', [UsulanController::class, 'show'])
            ->name('usulan.show');

        // Edit usulan
        Route::get('/usulan/{id}/edit', [UsulanController::class, 'edit'])
            ->name('usulan.edit');

        // Update usulan
        Route::put('/usulan/{id}', [UsulanController::class, 'update'])
            ->name('usulan.update');

        // Submit usulan
        Route::post('/usulan/{id}/submit', [UsulanController::class, 'submitUsulan'])
            ->name('usulan.submit');
    });


// ======================================================================
// 🔹 AUTH ROUTES (BREEZE DEFAULT)
// ======================================================================
require __DIR__ . '/auth.php';
