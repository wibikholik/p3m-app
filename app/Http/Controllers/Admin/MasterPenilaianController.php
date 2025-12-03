<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterPenilaian;

class MasterPenilaianController extends Controller
{
    public function index()
    {
        $items = MasterPenilaian::orderBy('order')->get();
        return view('admin.penilaian.index', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'bobot' => 'required|integer|min:0',
            'order' => 'required|integer'
        ]);

        MasterPenilaian::create($request->only(['nama','deskripsi','bobot','order','is_active']));

        return back()->with('success', 'Komponen penilaian berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $item = MasterPenilaian::findOrFail($id);

        $item->update($request->all());

        return back()->with('success', 'Komponen penilaian diperbarui.');
    }

    public function edit($id)
{
    $item = MasterPenilaian::findOrFail($id);
    return view('admin.penilaian.edit', compact('item'));
}


    public function destroy($id)
    {
        MasterPenilaian::findOrFail($id)->delete();
        return back()->with('success', 'Komponen penilaian dihapus.');
    }

    public function toggle($id)
{
    $item = MasterPenilaian::findOrFail($id);

    $item->is_active = !$item->is_active;
    $item->save();

    return back()->with('success', 'Status penilaian berhasil diubah.');
}

}
