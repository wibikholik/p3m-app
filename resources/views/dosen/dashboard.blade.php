<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin P3M</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex">

    @include('dosen.layouts.sidebar')

    <div class="flex-1 flex flex-col ml-64">
        @include('dosen.layouts.navbar')
<div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    Selamat datang, {{ Auth::user()->name }}! 👋<br>
                    Ini adalah halaman dashboard khusus dosen.
                </div>
            </div>
        </div>
    </div>
        <main class="flex-1 p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Dashboard</h2>
           <div class="grid grid-cols-1 md:grid-cols-4 gap-6">


        </main>
    </div>

</body>
</html>
