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
            // Ambil semua user kecuali admin yang sedang login
            $users = User::where('id', '!=', auth()->id())->paginate(10);
            return view('admin.users.index', compact('users'));
        }
/**
 * Menambahkan user baru.
 */
        public function store(Request $request)
        {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
                'role' => ['required', Rule::in(['admin', 'dosen', 'reviewer'])],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'password' => Hash::make($request->password),
            ]);

            return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
        }
       public function create()
{
    $user = new User();
    return view('admin.users.create', compact('user'));
}

        /**
         * Menampilkan form untuk mengedit user.
         */
        public function edit(User $user)
        {
            return view('admin.users.edit', compact('user'));
        }

        /**
         * Memproses update data user.
         */
        public function update(Request $request, User $user)
        {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'role' => ['required', Rule::in(['admin', 'dosen', 'reviewer'])],
            ]);

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
            ]);

            // Jika password diisi, update password
            if ($request->filled('password')) {
                $request->validate([
                    'password' => ['required', 'confirmed', Rules\Password::defaults()],
                ]);
                $user->update(['password' => Hash::make($request->password)]);
            }

            return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
        }

        /**
         * Menghapus user dari database.
         */
        public function destroy(User $user)
        {
            // Tambahkan proteksi agar user tidak bisa menghapus dirinya sendiri (walaupun sudah dicegah di index)
            if ($user->id === auth()->id()) {
                return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
            }

            $user->delete();
            return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
        }
        
        /**
         * Memblokir user.
         */
        public function block(User $user)
        {
            $user->update(['blocked_at' => now()]);
            return redirect()->route('admin.users.index')->with('success', 'User berhasil diblokir.');
        }

        /**
         * Membuka blokir user.
         */
        public function unblock(User $user)
        {
            $user->update(['blocked_at' => null]);
            return redirect()->route('admin.users.index')->with('success', 'Blokir user berhasil dibuka.');
        }
    }
    
