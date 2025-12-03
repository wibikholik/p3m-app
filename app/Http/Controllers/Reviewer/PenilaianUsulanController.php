<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PenilaianUsulanController extends Controller
{
    public function form($usulan_id)
    {
        $komponen = MasterPenilaian::where('is_active', 1)->orderBy('order')->get();
        $usulan = Usulan::findOrFail($usulan_id);

        return view('reviewer.usulan.penilaian', compact('usulan', 'komponen'));
    }

    public function store(Request $request, $usulan_id)
    {
        $reviewer_id = auth()->id();

        // hapus nilai lama (jika reviewer nilai ulang)
        UsulanPenilaian::where('usulan_id', $usulan_id)
            ->where('reviewer_id', $reviewer_id)
            ->delete();

        foreach ($request->nilai as $komponen_id => $value) {
            UsulanPenilaian::create([
                'usulan_id'   => $usulan_id,
                'reviewer_id' => $reviewer_id,
                'komponen_id' => $komponen_id,
                'nilai'       => $value ?? 0,
                'catatan'     => $request->catatan[$komponen_id] ?? null,
            ]);
        }

        return back()->with('success', 'Penilaian berhasil disimpan.');
    }
}
