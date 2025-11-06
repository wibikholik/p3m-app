<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dosen Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex">

    {{-- Sidebar --}}
    @include('dosen.layouts.sidebar')

    <div class="flex-1 flex flex-col min-h-screen ml-64">
        {{-- Navbar --}}
        @include('dosen.layouts.navbar')

        {{-- Main Content --}}
        <main class="flex-1 p-6">
            {{-- Notifikasi Success/Error --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
