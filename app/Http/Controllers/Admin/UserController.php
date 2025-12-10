<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua user.
     */
    public function index()
    {
        // AMBIL SEMUA USER BESERTA RELASI ROLESNYA
        $users = User::with('roles')
                     ->where('id', '!=', auth()->id())
                     // HAPUS ->orderBy('role') KARENA KOLOM INI TIDAK ADA
                     ->latest() // Urutkan berdasarkan yang terbaru
                     ->paginate(10); 
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Menambahkan user baru.
     * CATATAN: Karena ini multi-role, Anda harus menambahkan logika 
     * untuk menautkan role ke user setelah user dibuat. 
     * Saya asumsikan role utama yang dipilih (misal: 'dosen') ditautkan ke user.
     */
    public function store(Request $request)
    {
        // Asumsi: Role yang valid ada di tabel 'roles' (admin, dosen, reviewer)
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'role_id' => ['required', 'exists:roles,id'], // Validasi role_id
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // Kolom 'role' sudah dihapus dari tabel users, jadi kita tidak mengisinya di sini
        ]);
        
        // TAUTKAN ROLE YANG DIPILIH KE USER
        $user->roles()->attach($request->role_id);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }
    
    public function create()
    {
        // Di sini Anda perlu memuat daftar Role dari database untuk form
        $roles = \App\Models\Role::all(); 
        $user = new User();
        return view('admin.users.create', compact('user', 'roles'));
    }

    /**
     * Menampilkan form untuk mengedit user.
     */
    public function edit(User $user)
    {
        $roles = \App\Models\Role::all();
        // Ambil ID role yang dimiliki user
        $userRoleIds = $user->roles->pluck('id')->toArray();
        return view('admin.users.edit', compact('user', 'roles', 'userRoleIds'));
    }

    /**
     * Memproses update data user.
     * CATATAN: Logika ini harus diubah untuk menangani update roles melalui tabel pivot.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'roles' => ['required', 'array', 'exists:roles,id'], // Validasi array roles
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        
        // SINKRONISASI ROLES
        $user->roles()->sync($request->roles);

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    // Metode destroy, block, unblock (tidak berubah, asalkan Model User memiliki fungsi isBlocked())

    /**
     * Menghapus user dari database.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        try {
             // Detach semua role sebelum menghapus user
             $user->roles()->detach();
             $user->delete();
             return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
        } catch (\Exception $e) {
             return back()->with('error', 'Gagal menghapus user.');
        }
    }
    
    public function block(User $user)
    {
        // ... (tetap sama)
        $user->update(['blocked_at' => now()]);
        return redirect()->route('admin.users.index')->with('success', 'User berhasil diblokir.');
    }

    public function unblock(User $user)
    {
        // ... (tetap sama)
        $user->update(['blocked_at' => null]);
        return redirect()->route('admin.users.index')->with('success', 'Blokir user berhasil dibuka.');
    }
    
    // --- FUNGSI PEMBERIAN/PENGHAPUSAN ROLE REVIEWER ---
    
    /**
     * Mengangkat Dosen menjadi Reviewer.
     */
    public function assignReviewer(User $user)
    {
        // Ambil ID role 'dosen' dan 'reviewer'
        $dosenRole = \App\Models\Role::where('name', 'dosen')->first();
        $reviewerRole = \App\Models\Role::where('name', 'reviewer')->first();
        
        // Cek apakah user memiliki role 'dosen' (syarat utama)
        if (!$user->hasRole('dosen')) {
            return back()->with('error', 'Hanya pengguna Dosen yang dapat diangkat menjadi Reviewer.');
        }
        
        // Tautkan role 'reviewer' ke user (jika belum ada)
        if (!$user->hasRole('reviewer')) {
            $user->roles()->attach($reviewerRole->id);
            return redirect()->route('admin.users.index')->with('success', 'Dosen ' . $user->name . ' berhasil diangkat menjadi Reviewer.');
        }
        
        return back()->with('error', 'User sudah memiliki peran Reviewer.');
    }
    
    /**
     * Menghapus peran Reviewer dari Dosen.
     */
    public function removeReviewer(User $user)
    {
        $reviewerRole = \App\Models\Role::where('name', 'reviewer')->first();

        // Cek apakah user memiliki role 'reviewer'
        if ($user->hasRole('reviewer')) {
            // Lepaskan role 'reviewer'
            $user->roles()->detach($reviewerRole->id);
            return redirect()->route('admin.users.index')->with('success', 'Peran Reviewer dari Dosen ' . $user->name . ' berhasil dihapus.');
        }
        
        return back()->with('error', 'User tidak memiliki peran Reviewer.');
    }
}