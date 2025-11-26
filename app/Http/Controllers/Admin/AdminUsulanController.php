<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usulan;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
    public function usulanDetail($id)
    {
        $usulan = Usulan::with(['pengusul', 'anggota', 'pengumuman.kategori'])->findOrFail($id);
        return view('admin.usulan.show', compact('usulan'));
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
    public function verifikasi(Request $request, $id)
    {
        $usulan = Usulan::findOrFail($id);
        $action = $request->action;

        if ($action === 'approve') {
            $usulan->status = 'lolos_administrasi';
            $usulan->catatan_admin = null;
            $usulan->checklist = json_encode($request->checklist);
        } elseif ($action === 'reject') {
            if (!$request->catatan_admin) {
                return back()->with('error', 'Catatan wajib diisi jika menolak.');
            }
            $usulan->status = 'ditolak_administrasi';
            $usulan->catatan_admin = $request->catatan_admin;
            $usulan->checklist = json_encode($request->checklist);
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
