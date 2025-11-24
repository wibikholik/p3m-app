<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Ini akan diisi oleh tampilan spesifik --}}
    <title>@yield('title', 'Aplikasi Web')</title>
    
    {{-- Tambahkan CSS utama di sini --}}
    @stack('styles') 
</head>
<body>

    @auth
        
        {{-- ADMIN LAYOUT --}}
        @if (Auth::user()->hasRole('admin'))
            @include('admin.layouts.app') 
        
        {{-- DOSEN LAYOUT --}}
        @elseif(Auth::user()->hasRole('dosen'))
            @include('dosen.layouts.app')
            
        {{-- REVIEWER LAYOUT --}} 
        @elseif(Auth::user()->hasRole('reviewer'))
            @include('reviewer.layouts.app')
            
        @else
            {{-- LAYOUT TAMU/NON-PERAN KHUSUS --}}
            @yield('content')
        @endif
        
    @endauth

    {{-- Ini untuk JavaScript --}}
    @stack('scripts')
</body>
</html>