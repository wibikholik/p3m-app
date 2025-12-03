<?php

use App\Http\Controllers\Admin\AdminUsulanController;
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
use App\Http\Controllers\Reviewer\ReviewerUsulanController;
use App\Http\Controllers\Admin\MasterKelengkapanController;
use App\Http\Controllers\Admin\UsulanKelengkapanController;
use App\Http\Controllers\Admin\MasterPenilaianController;


/*
|--------------------------------------------------------------------------
| Halaman Public
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingPageController::class, 'index'])->name('home');


/*
|--------------------------------------------------------------------------
| Switch Role
|--------------------------------------------------------------------------
*/
Route::post('/switch-role', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'role' => 'required|string'
    ]);

    $user = Auth::user();
    if (!$user) return redirect('/');

    if ($user->roles()->where('name', $request->role)->exists()) {
        session(['active_role' => $request->role]);
    }

    return redirect()->route('redirect.role');

})->middleware('auth')->name('switch-role');


Route::get('/redirect-role', function () {
    $user = Auth::user();
    if (!$user) return redirect('/');

    $activeRole = session('active_role') ?? $user->roles()->first()->name ?? 'dosen';

    return match ($activeRole) {
        'admin'     => redirect()->route('admin.dashboard'),
        'reviewer' => redirect()->route('reviewer.dashboard'),
        default     => redirect()->route('dosen.dashboard'),
    };
})->middleware(['auth', 'verified'])->name('redirect.role');


/*
|--------------------------------------------------------------------------
| Profile Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
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

        // USULAN INDEX
        Route::get('/usulan', [AdminUsulanController::class, 'usulanIndex'])
            ->name('usulan.index');

        // USULAN DETAIL
        Route::get('/usulan/{id}', [AdminUsulanController::class, 'usulanDetail'])
            ->name('usulan.show');

        // PREVIEW FILE
        Route::get('/usulan/preview/{filename}', [AdminUsulanController::class, 'previewFile'])
            ->name('usulan.preview_file');

        // DOWNLOAD FILE
        Route::get('/usulan/download/{filename}', [AdminUsulanController::class, 'downloadFile'])
            ->name('usulan.download_file');

        // VERIFIKASI ADMINISTRASI
        Route::put('/usulan/{usulan}/verifikasi', [AdminUsulanController::class, 'verifikasi'])
            ->name('usulan.verifikasi');

        // Assign Reviewer
        Route::get('/usulan/{id}/assign-reviewer', [AdminUsulanController::class, 'assignReviewerPage'])
            ->name('usulan.assignReviewer.page');

        Route::post('/usulan/{id}/assign-reviewer', [AdminUsulanController::class, 'assignReviewer'])
            ->name('usulan.assignReviewer');

        /*
        |--------------------------------------------------------------------------
        | Master Kelengkapan
        |--------------------------------------------------------------------------
        */
        Route::resource('kelengkapan', MasterKelengkapanController::class)->except(['show']);
        Route::post('/kelengkapan/{id}/toggle', [MasterKelengkapanController::class,'toggle'])
            ->name('kelengkapan.toggle');

        /*
        |--------------------------------------------------------------------------
        | Master Penilaian (Fixed No Duplicate Route)
        |--------------------------------------------------------------------------
        */
        Route::resource('penilaian', MasterPenilaianController::class)->except(['show']);

        Route::post('/penilaian/{id}/toggle', [MasterPenilaianController::class, 'toggle'])
            ->name('penilaian.toggle');
    });


// Checklist per usulan
Route::middleware(['auth','can:check-usulan'])->group(function() {
    Route::get('/usulan/{id}/checklist', [UsulanKelengkapanController::class,'editChecklist'])->name('usulan.checklist.edit');
    Route::post('/usulan/{id}/checklist', [UsulanKelengkapanController::class,'updateChecklist'])->name('usulan.checklist.update');
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

        Route::get('/usulan', [ReviewerUsulanController::class, 'index'])->name('usulan.index');
        Route::get('/usulan/{id}', [ReviewerUsulanController::class, 'show'])->name('usulan.show');
        Route::post('/usulan/{id}/accept', [ReviewerUsulanController::class, 'accept'])->name('usulan.accept');
        Route::post('/usulan/{id}/decline', [ReviewerUsulanController::class, 'decline'])->name('usulan.decline');
        Route::get('/usulan/{id}/review', [ReviewerUsulanController::class, 'review'])->name('usulan.review');
        Route::post('/usulan/{id}/review/submit', [ReviewerUsulanController::class, 'submitReview'])->name('usulan.review.submit');
        Route::post('/usulan/{id}/review/revisi', [ReviewerUsulanController::class, 'requestRevision'])->name('usulan.review.revisi');
        Route::get('/usulan/{id}/download/{filename}', [ReviewerUsulanController::class, 'downloadFile'])->name('usulan.download_file');
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

        Route::get('/pengumuman', [DosenPengumumanController::class, 'index'])->name('pengumuman.index');
        Route::get('/pengumuman/{id}', [DosenPengumumanController::class, 'show'])->name('pengumuman.show');

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
| Auth Routes
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
