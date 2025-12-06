<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReviewerController;
use App\Http\Controllers\DosenController;

// Pengumuman Public & Admin
use App\Http\Controllers\PengumumanController as PublicPengumumanController;
use App\Http\Controllers\Admin\PengumumanController;
use App\Http\Controllers\Admin\KategoriPengumumanController;

// Users
use App\Http\Controllers\Admin\UserController;

// Usulan dosen/admin/reviewer
use App\Http\Controllers\Dosen\UsulanController;
use App\Http\Controllers\Dosen\DosenPengumumanController;
use App\Http\Controllers\Admin\AdminUsulanController;
use App\Http\Controllers\Reviewer\ReviewerUsulanController;
use App\Http\Controllers\Reviewer\PenilaianUsulanController;

// Kelengkapan & Penilaian
use App\Http\Controllers\Admin\MasterKelengkapanController;
use App\Http\Controllers\Admin\UsulanKelengkapanController;
use App\Http\Controllers\Admin\MasterPenilaianController;

use App\Http\Controllers\Dosen\LaporankemajuanDosenController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingPageController::class, 'index'])->name('home');

Route::get('/pengumuman', [PublicPengumumanController::class, 'index'])->name('pengumuman.index');
Route::get('/pengumuman/{id}', [PublicPengumumanController::class, 'show'])->name('pengumuman.show');

/*
|--------------------------------------------------------------------------
| Switch Role
|--------------------------------------------------------------------------
*/
Route::post('/switch-role', function (\Illuminate\Http\Request $request) {
    $request->validate(['role' => 'required|string']);
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
        'reviewer'  => redirect()->route('reviewer.dashboard'),
        default     => redirect()->route('dosen.dashboard'),
    };
})->middleware(['auth', 'verified'])->name('redirect.role');

/*
|--------------------------------------------------------------------------
| Profile
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

        // Pengumuman
        Route::resource('kategori-pengumuman', KategoriPengumumanController::class);
        Route::resource('pengumuman', PengumumanController::class);

        // Users
        Route::resource('users', UserController::class)->except(['show']);
        Route::post('users/{user}/block', [UserController::class, 'block'])->name('users.block');
        Route::post('users/{user}/unblock', [UserController::class, 'unblock'])->name('users.unblock');

        // Usulan administrasi
        Route::get('/usulan', [AdminUsulanController::class, 'usulanIndex'])->name('usulan.index');
        Route::get('/usulan/{id}', [AdminUsulanController::class, 'usulanDetail'])->name('usulan.show');
        Route::get('/usulan/preview/{filename}', [AdminUsulanController::class, 'previewFile'])->name('usulan.preview_file');
        Route::get('/usulan/download/{filename}', [AdminUsulanController::class, 'downloadFile'])->name('usulan.download_file');
        Route::put('/usulan/{usulan}/verifikasi', [AdminUsulanController::class, 'verifikasi'])->name('usulan.verifikasi');
        Route::get('/usulan/{id}/assign-reviewer', [AdminUsulanController::class, 'assignReviewerPage'])->name('usulan.assignReviewer.page');
        Route::post('/usulan/{id}/assign-reviewer', [AdminUsulanController::class, 'assignReviewer'])->name('usulan.assignReviewer');

        // Master Kelengkapan
        Route::resource('kelengkapan', MasterKelengkapanController::class)->except(['show']);
        Route::post('/kelengkapan/{id}/toggle', [MasterKelengkapanController::class,'toggle'])->name('kelengkapan.toggle');

        // Master Penilaian
        Route::resource('penilaian', MasterPenilaianController::class)->except(['show']);
        Route::post('/penilaian/{id}/toggle', [MasterPenilaianController::class,'toggle'])->name('penilaian.toggle');

        // Checklist per-usulan
        Route::get('/usulan/{id}/checklist', [UsulanKelengkapanController::class,'editChecklist'])->name('usulan.checklist.edit');
        Route::post('/usulan/{id}/checklist', [UsulanKelengkapanController::class,'updateChecklist'])->name('usulan.checklist.update');
        
        // Rute Rekap dan Finalisasi
        // Menggunakan nama usulan.rekap dan usulan.finalize
        Route::get('/usulan/{usulan}/rekap', [AdminUsulanController::class,'show'])->name('usulan.rekap');
        Route::post('/usulan/{usulan}/finalize', [AdminUsulanController::class,'finalize'])->name('usulan.finalize'); 
        
        // Rute untuk Verifikasi Revisi (juga digunakan di view rekap-penilaian.blade.php)
        Route::post('/usulan/{usulan}/verify-revision', [UsulanController::class, 'verifyRevision'])->name('usulan.verify-revision');
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

        // Daftar Usulan
        Route::get('/usulan', [ReviewerUsulanController::class, 'index'])->name('usulan.index');
        Route::get('/usulan/{id}', [ReviewerUsulanController::class, 'show'])->name('usulan.show');
        
        Route::post('/usulan/{id}/accept', [ReviewerUsulanController::class, 'accept'])->name('usulan.accept');
        Route::post('/usulan/{id}/decline', [ReviewerUsulanController::class, 'decline'])->name('usulan.decline');

        // Review menggunakan multi-komponen
        Route::get('/usulan/{usulan}/review', [PenilaianUsulanController::class, 'review'])->name('usulan.review');
        Route::post('/usulan/{usulan}/review/submit', 
    [PenilaianUsulanController::class, 'submitReview']
        )->name('penilaian.submit');


        // Download file
        
        Route::get('/usulan/{id}/download/{filename}', [ReviewerUsulanController::class,'downloadFile'])->name('usulan.download_file');
    
        
        Route::get('/usulan/{usulan}/hasil', [PenilaianUsulanController::class, 'showHasilPenilaian'])->name('penilaian.hasil');
 Route::get('/usulan/{id}/review-revisi', [ReviewerUsulanController::class, 'reviewRevisi'])->name('usulan.review_revisi');
 Route::post('/usulan/{id}/review-revisi', [ReviewerUsulanController::class, 'submitReviewRevisi'])->name('usulan.submit_revisi');


 Route::get('/laporan-kemajuan', [App\Http\Controllers\Reviewer\LaporanKemajuanController::class, 'index'])->name('laporan-kemajuan.index');
    Route::get('/laporan-kemajuan/{id}', [App\Http\Controllers\Reviewer\LaporanKemajuanController::class, 'show'])->name('laporan-kemajuan.show');
    Route::post('/laporan-kemajuan/{id}/nilai', [App\Http\Controllers\Reviewer\LaporanKemajuanController::class, 'nilai'])->name('laporan-kemajuan.nilai');
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


        /*
        |--------------------------------------------------------------------------
        | LAPORAN KEMAJUAN 
        |--------------------------------------------------------------------------
        */
        
        // Resource utama
        Route::resource('laporan-kemajuan', LaporanKemajuanDosenController::class)
        ->names('laporankemajuan')
        ->parameters(['laporankemajuan' => 'id']);


        // Custom route untuk create & store pakai id_usulan
        Route::get('/laporan-kemajuan/create/{id_usulan}', 
            [LaporanKemajuanDosenController::class, 'create'])
            ->name('laporankemajuan.create-usulan');

        Route::post('/laporan-kemajuan/store/{id_usulan}', 
            [LaporanKemajuanDosenController::class, 'store'])
            ->name('laporankemajuan.store-usulan');

    });

require __DIR__.'/auth.php';