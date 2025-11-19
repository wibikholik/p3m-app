<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReviewerController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\Admin\PengumumanController;
use App\Http\Controllers\Admin\KategoriPengumumanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Dosen\DosenPengumumanController;
use App\Http\Controllers\Dosen\UsulanController;

/*
|--------------------------------------------------------------------------
| Halaman Public
|--------------------------------------------------------------------------
*/




Route::get('/', [LandingPageController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Switch Role (POST)
|--------------------------------------------------------------------------
*/
Route::post('/switch-role', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'role' => 'required|string'
    ]);

    $user = Auth::user();
    if (!$user) return redirect('/');

    // Hanya izinkan role yang dimiliki user
    if ($user->roles()->where('name', $request->role)->exists()) {
        session(['active_role' => $request->role]);
    }

    // Redirect ke dashboard role tersebut
    return redirect()->route('redirect.role');

})->middleware('auth')->name('switch-role');

/*
|--------------------------------------------------------------------------
| Redirect otomatis berdasarkan active role
|--------------------------------------------------------------------------
*/
Route::get('/redirect-role', function () {

    $user = Auth::user();
    if (!$user) return redirect('/');

    // Ambil role aktif dari session, jika kosong ambil role pertama user
    $activeRole = session('active_role') ?? $user->roles()->first()->name ?? 'dosen';

    return match ($activeRole) {
        'admin'    => redirect()->route('admin.dashboard'),
        'reviewer' => redirect()->route('reviewer.dashboard'),
        default     => redirect()->route('dosen.dashboard'),
    };

})->middleware(['auth', 'verified'])->name('redirect.role');

/*
|--------------------------------------------------------------------------
| Profile Routes (semua role)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

        Route::resource('kategori-pengumuman', KategoriPengumumanController::class);
        Route::resource('pengumuman', PengumumanController::class);

        Route::resource('users', UserController::class)->except(['show']);
        Route::post('users/{user}/block', [UserController::class, 'block'])->name('users.block');
        Route::post('users/{user}/unblock', [UserController::class, 'unblock'])->name('users.unblock');
    });

/*
|--------------------------------------------------------------------------
| REVIEWER
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:reviewer'])
    ->prefix('reviewer')
    ->name('reviewer.')
    ->group(function () {

        Route::get('/dashboard', [ReviewerController::class, 'index'])->name('dashboard');

        // Route tambahan reviewer tambahkan disini
    });

/*
|--------------------------------------------------------------------------
| DOSEN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:dosen'])
    ->prefix('dosen')
    ->name('dosen.')
    ->group(function () {

        Route::get('/dashboard', [DosenController::class, 'index'])->name('dashboard');

        // Pengumuman
        Route::get('/pengumuman', [DosenPengumumanController::class, 'index'])->name('pengumuman.index');
        Route::get('/pengumuman/{id}', [DosenPengumumanController::class, 'show'])->name('pengumuman.show');

        // Usulan
        Route::get('/usulan', [UsulanController::class, 'index'])->name('usulan.index');
        Route::get('/usulan/create/{id_pengumuman}', [UsulanController::class, 'create'])->name('usulan.create');
        Route::post('/usulan/store', [UsulanController::class, 'store'])->name('usulan.store');
        Route::get('/usulan/{id}', [UsulanController::class, 'show'])->name('usulan.show');
        Route::get('/usulan/{id}/edit', [UsulanController::class, 'edit'])->name('usulan.edit');
        Route::put('/usulan/{id}', [UsulanController::class, 'update'])->name('usulan.update');
        Route::post('/usulan/{id}/submit', [UsulanController::class, 'submitUsulan'])->name('usulan.submit');

        Route::get('/search', [UsulanController::class, 'search'])->name('search');
    });

    
/*
|--------------------------------------------------------------------------
| Auth Routes (Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
