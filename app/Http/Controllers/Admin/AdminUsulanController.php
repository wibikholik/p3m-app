<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usulan;
use Illuminate\Support\Facades\Storage; // Import Storage facade
use Illuminate\Support\Facades\Log; // Import Log facade
use Illuminate\Support\Facades\DB; // Import DB facade

class AdminUsulanController extends Controller
{
    public function usulanindex(Request $request)
    {
        $filterStatus = $request->input('status');
        // Eager loading: pengusul, pengumuman, dan kategori (melalui pengumuman)
        $query = Usulan::with(['pengusul', 'pengumuman.kategori']);

        if ($filterStatus) {
            $query->where('status', $filterStatus);
        }

        $usulan = $query->latest()->get(); 
        return view('admin.usulan.index', compact('usulan'));
    }

    public function usulanDetail($id)
    {
        // Memastikan semua relasi yang dibutuhkan di show.blade.php dimuat
        $usulan = Usulan::with(['pengusul', 'anggota', 'pengumuman.kategori'])->findOrFail($id);
        return view('admin.usulan.show', compact('usulan'));
    }

    /**
     * Menangani pengunduhan file usulan. 
     * Menggunakan Storage::response untuk menampilkan konten file (cocok untuk preview).
     */
    public function downloadFile($filename)
{
    $path = public_path('storage/usulan/' . $filename);

    if (!file_exists($path)) {
        abort(404, 'File tidak ditemukan.');
    }

    return response()->download($path);
}

    /**
     * Memproses aksi verifikasi Administrasi (Lolos/Tolak) dengan validasi checklist.
     */
    public function verifikasi(Request $request, $id)
{
    $usulan = Usulan::findOrFail($id);

    $action = $request->action;

    if ($action === 'approve') {

        $usulan->status = 'lolos_admin';
        $usulan->catatan_admin = null;
        $usulan->checklist = json_encode($request->checklist);

    } elseif ($action === 'reject') {

        if (!$request->catatan_admin) {
            return back()->with('error', 'Catatan wajib diisi jika menolak.');
        }

        $usulan->status = 'ditolak';
        $usulan->catatan_admin = $request->catatan_admin;
        $usulan->checklist = json_encode($request->checklist);

    }

    $usulan->save();

    return back()->with('success', 'Status verifikasi berhasil diperbarui.');
}

}