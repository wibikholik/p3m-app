<aside class="w-64 bg-white shadow-md flex flex-col h-screen fixed top-2" x-data="{ openPenelitian: false }">
    <!-- Header -->
    <div class="p-6 border-b">
        <h1 class="text-2xl font-bold text-gray-800">P3M Admin</h1>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 mt-4 space-y-1">
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}" class="block py-3 px-6 text-gray-700 hover:bg-gray-200 rounded flex items-center gap-2">
            <!-- Icon Dashboard -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h6v6H3V3zm0 12h6v6H3v-6zm12-12h6v6h-6V3zm0 12h6v6h-6v-6z"/>
            </svg>
            Dashboard
        </a>

        <a href="{{ route('admin.pengumuman.index') }}" class="block py-3 px-6 text-gray-700 hover:bg-gray-200 rounded flex items-center gap-2">
    Pengumuman
</a>

        <!-- Penelitian dan Pengabdian (Dropdown) -->
        <button @click="openPenelitian = !openPenelitian" class="w-full flex items-center justify-between py-3 px-6 text-gray-700 hover:bg-gray-200 rounded">
            <div class="flex items-center gap-2">
                <!-- Icon Penelitian -->
                <!-- <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-3.866 0-7 1.79-7 4s3.134 4 7 4 7-1.79 7-4-3.134-4-7-4z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12v8m0 0l-3-3m3 3l3-3"/>
                </svg> -->
                Penelitian dan Pengabdian
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" :class="{'rotate-90': openPenelitian}" class="h-4 w-4 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>
        <!-- Dropdown Menu -->
        <div x-show="openPenelitian" class="pl-12 space-y-1">
            <a href="#" class="block py-2 px-4 text-gray-600 hover:bg-gray-200 rounded">Daftar Penelitian</a>
            <a href="#" class="block py-2 px-4 text-gray-600 hover:bg-gray-200 rounded">Daftar Pengabdian</a>
            <a href="#" class="block py-2 px-4 text-gray-600 hover:bg-gray-200 rounded"></a>
        </div>

        <!-- Menu Lain -->
        <a href="#" class="block py-3 px-6 text-gray-700 hover:bg-gray-200 rounded flex items-center gap-2">
            <!-- Icon Manajemen -->
            <!-- <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4h14V3H5zm0 6v12h14V9H5z"/>
            </svg> -->
            Manajemen Pengguna
        </a>

        <a href="#" class="block py-3 px-6 text-gray-700 hover:bg-gray-200 rounded flex items-center gap-2">
            <!-- Icon Reviewer -->
            <!-- <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg> -->
            Reviewer
        </a>

        <a href="#" class="block py-3 px-6 text-gray-700 hover:bg-gray-200 rounded flex items-center gap-2">
            <!-- Icon Pelaporan -->
            <!-- <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6h6v6h5v2H4v-2h5z"/>
            </svg> -->
            Pelaporan dan Evaluasi
        </a>

        <!-- Tambahkan menu lain dengan ikon serupa -->
        <a href="#" class="block py-3 px-6 text-gray-700 hover:bg-gray-200 rounded flex items-center gap-2">Publikasi</a>
        <a href="#" class="block py-3 px-6 text-gray-700 hover:bg-gray-200 rounded flex items-center gap-2">Arsip</a>
        <a href="#" class="block py-3 px-6 text-gray-700 hover:bg-gray-200 rounded flex items-center gap-2">Pengaturan</a>
    </nav>
</aside>

<!-- Alpine.js untuk dropdown -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
