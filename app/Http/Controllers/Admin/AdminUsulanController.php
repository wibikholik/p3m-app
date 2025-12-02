<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usulan;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\MasterKelengkapan;
use App\Models\UsulanKelengkapan;


class AdminUsulanController extends Controller
{
    /**
     * Menampilkan daftar usulan dengan filter status
     */
    public function usulanindex(Request $request)
    {
        $filterStatus = $request->input('status');

        $query = Usulan::with(['pengusul', 'pengumuman.kategori']);

        if ($filterStatus) {
            $query->where('status', $filterStatus);
        }

        $usulan = $query->latest()->get();

        return view('admin.usulan.index', compact('usulan'));
    }

    /**
     * Menampilkan detail usulan
     */
    // public function usulanDetail($id)
    // {
    //     $usulan = Usulan::with(['pengusul', 'anggota', 'pengumuman.kategori'])->findOrFail($id);
    //     return view('admin.usulan.show', compact('usulan'));
    // }

    public function usulanDetail($id)
{
    $usulan = Usulan::with(['pengusul', 'anggota', 'pengumuman.kategori'])->findOrFail($id);

    // Ambil semua master kelengkapan aktif (urutkan jika perlu)
    $masterKelengkapan = MasterKelengkapan::where('is_active', 1)
                            ->orderBy('order')
                            ->get();

    // Ambil checklist/usulan_kelengkapan yang sudah tersimpan untuk usulan ini
    // keyBy supaya mudah diakses di view dengan $ceklistUsulan[$item->id]
    $ceklistUsulan = UsulanKelengkapan::where('usulan_id', $id)->get()->keyBy('kelengkapan_id');

    return view('admin.usulan.show', compact('usulan','masterKelengkapan','ceklistUsulan'));
}
    /**
     * Preview file usulan secara inline
     */
    public function previewFile($filename)
    {
        $path = public_path('storage/usulan/' . $filename);

        if (!file_exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->file($path);
    }

    /**
     * Download file usulan
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
     * Verifikasi administrasi (approve/reject)
     */
    // public function verifikasi(Request $request, $id)
    // {
    //     $usulan = Usulan::findOrFail($id);
    //     $action = $request->action;

    //     if ($action === 'approve') {
    //         $usulan->status = 'lolos_administrasi';
    //         $usulan->catatan_admin = null;
    //         $usulan->checklist = json_encode($request->checklist);
    //     } elseif ($action === 'reject') {
    //         if (!$request->catatan_admin) {
    //             return back()->with('error', 'Catatan wajib diisi jika menolak.');
    //         }
    //         $usulan->status = 'ditolak_administrasi';
    //         $usulan->catatan_admin = $request->catatan_admin;
    //         $usulan->checklist = json_encode($request->checklist);
    //     }

    //     $usulan->save();

    //     return back()->with('success', 'Status verifikasi berhasil diperbarui.');
    // }

    public function verifikasi(Request $request, $id)
{
    $usulan = Usulan::findOrFail($id);

    // Simpan setiap checklist ke tabel usulan_kelengkapan
    $checked = $request->input('checklist', []); // selalu array, walau kosong
    foreach ($checked as $kelengkapanId) {
        UsulanKelengkapan::create([
            'usulan_id'       => $id,
            'kelengkapan_id'  => $kelengkapanId,
            'status'          => 1,
        ]);
    }

    // Tentukan status administrasi
    $action = $request->action;

    if ($action === 'approve') {
        $usulan->status = 'lolos_administrasi';
        $usulan->catatan_admin = null;
    } else {
        if (!$request->catatan_admin) {
            return back()->with('error', 'Catatan wajib diisi jika menolak.');
        }
        $usulan->status = 'ditolak_administrasi';
        $usulan->catatan_admin = $request->catatan_admin;
    }

    $usulan->save();

    return back()->with('success', 'Status verifikasi berhasil diperbarui.');
}


    /**
     * Menampilkan halaman assign reviewer
     */
    public function assignReviewerPage($id)
    {
        $usulan = Usulan::with('reviewers')->findOrFail($id);

        $reviewers = User::whereHas('roles', fn($q) => $q->where('name', 'reviewer'))->get();

        return view('admin.usulan.assign_reviewer', compact('usulan', 'reviewers'));
    }

    /**
     * Proses assign reviewer ke usulan
     */
    public function assignReviewer(Request $request, $id)
{
    $usulan = Usulan::findOrFail($id);

    $data = $request->validate([
        'reviewers' => 'required|array|min:1',
        'reviewers.*.id' => 'required|exists:users,id',
        'reviewers.*.deadline' => 'nullable|date',
        'reviewers.*.catatan_assign' => 'nullable|string',
    ]);

    DB::transaction(function() use ($data, $usulan) {
        foreach ($data['reviewers'] as $rev) {
            // Cek jika reviewer sudah ditugaskan
            $exists = DB::table('usulan_reviewer')
                ->where('usulan_id', $usulan->id)
                ->where('reviewer_id', $rev['id'])
                ->exists();

            if ($exists) continue;

            DB::table('usulan_reviewer')->insert([
                'usulan_id'      => $usulan->id,
                'reviewer_id'    => $rev['id'],
                'assigned_by'    => auth()->id(),
                'assigned_at'    => now(),
                'deadline'       => $rev['deadline'] ?? null,
                'catatan_assign' => $rev['catatan_assign'] ?? null,
                'status'         => 'assigned',
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }

        // **Update status usulan menjadi in_review**
        $usulan->status = 'sedang_di_review';
        $usulan->save();
    });

    return back()->with('success', 'Reviewer berhasil ditugaskan dan status usulan diperbarui menjadi sedang_di_review.');
}

}
