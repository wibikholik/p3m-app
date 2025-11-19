<!--
    File ini berisi perbaikan untuk sidebar, diubah menjadi tema TERANG (LIGHT THEME) 
    agar tidak menyilaukan/membuat mata lelah, sesuai permintaan pengguna.
-->
<aside class="w-64 bg-white shadow-lg flex flex-col h-screen fixed top-0 overflow-y-auto" 
       x-data="{ 
            openPenelitian: {{ request()->routeIs('admin.penelitian.*') || request()->routeIs('admin.pengabdian.*') ? 'true' : 'false' }}, 
            openPengumuman: {{ request()->routeIs('admin.pengumuman.*') || request()->routeIs('admin.kategori-pengumuman.*') ? 'true' : 'false' }} 
       }">
    <!-- Header -->
    <div class="p-6 border-b border-gray-200 bg-white">
        <h1 class="text-xl font-extrabold text-gray-900 tracking-wider">P3M DASHBOARD</h1>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 mt-4 space-y-1 px-3">
        @php
            // Kelas aktif (Warna biru terang untuk kontras pada latar belakang putih)
            $activeClass = 'bg-blue-500 text-white shadow-md font-semibold';
            // Kelas default (Teks gelap, hover abu-abu muda)
            $defaultClass = 'text-gray-700 hover:bg-gray-100 hover:text-gray-900';
            // Kelas aktif untuk sub-menu
            $activeSubClass = 'text-blue-700 bg-blue-100 font-semibold';
            // Kelas default untuk sub-menu
            $defaultSubClass = 'text-gray-600 hover:bg-gray-100 hover:text-gray-900';
        @endphp

        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}" 
           class="block py-3 px-4 rounded-lg flex items-center gap-3 transition duration-150 ease-in-out 
                  {{ request()->routeIs('admin.dashboard') ? $activeClass : $defaultClass }}">
            <!-- Icon Dashboard -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h6v6H3V3zm0 12h6v6H3v-6zm12-12h6v6h-6V3zm0 12h6v6h-6v-6z"/>
            </svg>
            Dashboard
        </a>

        <!-- Pengumuman (Dropdown) -->
        <button @click="openPengumuman = !openPengumuman" 
                class="w-full flex items-center justify-between py-3 px-4 rounded-lg transition duration-150 ease-in-out 
                       {{ request()->routeIs('admin.pengumuman.*') || request()->routeIs('admin.kategori-pengumuman.*') ? $activeClass : $defaultClass }}">
            <div class="flex items-center gap-3">
                <!-- Icon Pengumuman -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-2.236 9.168-5.584C18.354 1.832 18 3.65 18 4.5c0 1.25.333 2.45 1 3.5" />
                </svg>
                Manajemen Pengumuman
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" :class="{'rotate-90': openPengumuman}" class="h-4 w-4 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>
        <!-- Dropdown Menu Pengumuman -->
        <div x-show="openPengumuman" x-collapse x-cloak class="pl-8 space-y-1">
            <a href="{{ route('admin.pengumuman.index') }}" 
               class="block py-2 px-4 rounded-lg transition duration-150 ease-in-out 
                      {{ request()->routeIs('admin.pengumuman.*') && !request()->routeIs('admin.kategori-pengumuman.*') ? $activeSubClass : $defaultSubClass }}">
               Daftar Pengumuman
            </a>
            <a href="{{ route('admin.kategori-pengumuman.index') }}" 
               class="block py-2 px-4 rounded-lg transition duration-150 ease-in-out 
                      {{ request()->routeIs('admin.kategori-pengumuman.*') ? $activeSubClass : $defaultSubClass }}">
               Daftar Kategori
            </a>
        </div>

        <!-- Penelitian dan Pengabdian (Dropdown) -->
        <button @click="openPenelitian = !openPenelitian" 
                class="w-full flex items-center justify-between py-3 px-4 rounded-lg transition duration-150 ease-in-out 
                       {{ request()->routeIs('admin.penelitian.*') || request()->routeIs('admin.pengabdian.*') ? $activeClass : $defaultClass }}">
            <div class="flex items-center gap-3">
                <!-- Icon Penelitian -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547a2 2 0 00-.547 1.806l.477 2.387a6 6 0 00.517 3.86l.158.318a6 6 0 003.86.517l2.387.477a2 2 0 001.806-.547a2 2 0 00.547-1.806l-.477-2.387a6 6 0 00-.517-3.86l-.158-.318a6 6 0 01-.517-3.86l.477-2.387a2 2 0 011.806-.547z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25a4.5 4.5 0 100 9 4.5 4.5 0 000-9z" />
                </svg>
                Penelitian & Pengabdian
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" :class="{'rotate-90': openPenelitian}" class="h-4 w-4 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>
        <!-- Dropdown Menu Penelitian -->
        <div x-show="openPenelitian" x-collapse x-cloak class="pl-8 space-y-1">
            <a href="#" class="block py-2 px-4 rounded-lg transition duration-150 ease-in-out {{ $defaultSubClass }}">Daftar Penelitian</a>
            <a href="#" class="block py-2 px-4 rounded-lg transition duration-150 ease-in-out {{ $defaultSubClass }}">Daftar Pengabdian</a>
        </div>
        
        <!-- Manajemen User -->
        <a href="{{ route('admin.users.index') }}" 
           class="block py-3 px-4 rounded-lg flex items-center gap-3 transition duration-150 ease-in-out
                  {{ request()->routeIs('admin.users.*') ? $activeClass : $defaultClass }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.124-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.653.124-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            Manajemen User
        </a>
    </nav>
</aside>

<!-- Alpine.js untuk dropdown -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>