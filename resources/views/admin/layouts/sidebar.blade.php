<!--
    File ini berisi perbaikan untuk sidebar.
    Perbaikan utama:
    1. Menambahkan 'openPengumuman: false' ke dalam x-data agar dropdown Pengumuman berfungsi.
    2. Menambahkan link untuk melihat daftar (index) pada setiap dropdown.
    3. Menambahkan atribut x-cloak untuk mencegah menu "flash" saat halaman dimuat.
-->
<aside class="w-64 bg-white shadow-md flex flex-col h-screen fixed top-0" x-data="{ openPenelitian: false, openPengumuman: false }">
    <!-- Header -->
    <div class="p-6 border-b">
        <h1 class="text-2xl font-bold text-gray-800">P3M Admin</h1>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 mt-4 space-y-1 px-2">
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}" class="block py-3 px-4 text-gray-700 hover:bg-gray-200 rounded flex items-center gap-3">
            <!-- Icon Dashboard -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h6v6H3V3zm0 12h6v6H3v-6zm12-12h6v6h-6V3zm0 12h6v6h-6v-6z"/>
            </svg>
            Dashboard
        </a>

        <!-- Pengumuman (Dropdown) -->
        <button @click="openPengumuman = !openPengumuman" class="w-full flex items-center justify-between py-3 px-4 text-gray-700 hover:bg-gray-200 rounded">
            <div class="flex items-center gap-3">
                <!-- Icon Pengumuman -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-2.236 9.168-5.584C18.354 1.832 18 3.65 18 4.5c0 1.25.333 2.45 1 3.5" />
                </svg>
                Pengumuman
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" :class="{'rotate-90': openPengumuman}" class="h-4 w-4 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>
        <!-- Dropdown Menu Pengumuman -->
        <div x-show="openPengumuman" x-cloak class="pl-8 space-y-1">
            <a href="{{ route('admin.pengumuman.index') }}" class="block py-2 px-4 text-gray-600 hover:bg-gray-200 rounded">Daftar Pengumuman</a>
            <a href="{{ route('admin.kategori-pengumuman.index') }}" class="block py-2 px-4 text-gray-600 hover:bg-gray-200 rounded">Daftar Kategori</a>
        </div>

        <!-- Penelitian dan Pengabdian (Dropdown) -->
        <button @click="openPenelitian = !openPenelitian" class="w-full flex items-center justify-between py-3 px-4 text-gray-700 hover:bg-gray-200 rounded">
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
        <div x-show="openPenelitian" x-cloak class="pl-8 space-y-1">
            <a href="#" class="block py-2 px-4 text-gray-600 hover:bg-gray-200 rounded">Daftar Penelitian</a>
            <a href="#" class="block py-2 px-4 text-gray-600 hover:bg-gray-200 rounded">Daftar Pengabdian</a>
        </div>

        <!-- Menu Lain -->
        {{-- Anda bisa menambahkan menu lain di sini --}}

    </nav>
</aside>

<!-- Alpine.js untuk dropdown -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
