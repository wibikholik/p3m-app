<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin P3M')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex min-h-screen">

    {{-- Sidebar --}}
    @include('admin.layouts.sidebar')

    {{-- Bagian kanan (konten utama + navbar + footer) --}}
    <div class="flex-1 flex flex-col ml-64">

        {{-- Navbar --}}
        @include('admin.layouts.navbar')

        {{-- Konten Utama --}}
        <main class="flex-1 p-6">
            @yield('content')
        </main>

        {{-- Footer --}}
        @include('admin.layouts.footer')

        @stack('scripts')
    </div>

</body>
</html>
