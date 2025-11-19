<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */

public function store(LoginRequest $request): RedirectResponse

{
    $request->authenticate();
    $request->session()->regenerate();

    $user = $request->user();

    // Ambil semua role user
    $userRoles = $user->roles()->pluck('name')->toArray();

    // Set default role di session, misal ambil yang pertama (dosen)
    session(['role' => $userRoles[0]]);

    return $this->redirectByRole($userRoles[0]);
}


    /**
     * Pilih role aktif untuk user dengan multi-role
     */
    public function setRole(Request $request): RedirectResponse
    {
        $request->validate([
            'role' => 'required|string',
        ]);

        $role = $request->role;
        session(['role' => $role]);
        session()->forget('available_roles');

        return $this->redirectByRole($role);
    }

    /**
     * Redirect berdasarkan role aktif
     */
    private function redirectByRole(string $role): RedirectResponse
    {
        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'reviewer' => redirect()->route('reviewer.dashboard'),
            default => redirect()->route('dosen.dashboard'),
        };
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
