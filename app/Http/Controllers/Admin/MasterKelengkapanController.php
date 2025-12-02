<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterKelengkapan;
use Illuminate\Http\Request;

class MasterKelengkapanController extends Controller
{
    public function index()
    {
        $items = MasterKelengkapan::orderBy('order')->get();
        return view('admin.kelengkapan.index', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'order' => 'nullable|integer'
        ]);

        MasterKelengkapan::create($request->only(['nama','deskripsi','order']));

        return redirect()->back()->with('success', 'Aturan kelengkapan Administrasi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = MasterKelengkapan::findOrFail($id);
        return view('admin.kelengkapan.edit', compact('item'));
    }

    public function update(Request $request, $id)
{
    $item = MasterKelengkapan::findOrFail($id);

    $item->nama = $request->nama;
    $item->deskripsi = $request->deskripsi;
    $item->order = $request->order;
    $item->is_active = $request->has('is_active') ? 1 : 0;

    $item->save();

    return redirect()->route('admin.kelengkapan.index')
        ->with('success', 'Data berhasil diperbarui!');
}


    public function destroy($id)
    {
        MasterKelengkapan::findOrFail($id)->delete();
        return redirect()->back()->with('success','Aturan dihapus.');
    }

    // toggle aktif/nonaktif via ajax or form
    public function toggle($id)
    {
        $item = MasterKelengkapan::findOrFail($id);
        $item->is_active = !$item->is_active;
        $item->save();
        return redirect()->back()->with('success','Status diubah.');
    }
}
