<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    {{-- CSS Auth --}}
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">

</head>

<body>

    <div class="auth-container">
        <div class="auth-card">

            <h2 class="auth-title">Login</h2>
            <p class="auth-subtitle">Silakan masuk ke akun Anda</p>

            <!-- Session Status -->
            @if (session('status'))
                <div class="auth-status">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="auth-input-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <span class="auth-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="auth-input-group">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required>
                    @error('password')
                        <span class="auth-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Remember Me --}}
                <div class="auth-remember">
                    <input id="remember_me" type="checkbox" name="remember">
                    <label for="remember_me">Remember me</label>
                </div>

                {{-- Tombol Login --}}
                <button class="auth-btn">Login</button>

                {{-- Link Register --}}
                <p class="auth-bottom-text">
                    Belum punya akun?
                    <a href="{{ route('register') }}">Daftar</a>
                </p>

            </form>

        </div>
    </div>

</body>
</html>
