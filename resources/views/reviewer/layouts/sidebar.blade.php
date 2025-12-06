<!-- Sidebar Reviewer -->
<aside class="w-64 bg-white shadow-md flex flex-col h-screen fixed top-0" 
       x-data="{ openPenelitian: false, openLaporan: false }" x-cloak>
    <!-- Header -->
    <div class="p-6 border-b">
        <h1 class="text-2xl font-bold text-gray-800">P3M Reviewer</h1>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 mt-4 space-y-1 px-2">
        <!-- Dashboard -->
        <a href="{{ route('reviewer.dashboard') }}" 
           class="block py-3 px-4 text-gray-700 hover:bg-gray-200 rounded flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M3 3h6v6H3V3zm0 12h6v6H3v-6zm12-12h6v6h-6V3zm0 12h6v6h-6v-6z"/>
            </svg>
            Dashboard
        </a>

        <!-- Penelitian & Pengabdian (Dropdown) -->
        <div>
            <button @click="openPenelitian = !openPenelitian" 
                    class="w-full flex items-center justify-between py-3 px-4 text-gray-700 hover:bg-gray-200 rounded">
                <span class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 8c-1.657 0-3 1.343-3 3 0 1.657 1.343 3 3 3s3-1.343 3-3c0-1.657-1.343-3-3-3z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 14v7"/>
                    </svg>
                    Penelitian & Pengabdian
                </span>
                <svg :class="{'rotate-90': openPenelitian}" class="h-4 w-4 transform transition-transform" 
                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="openPenelitian" x-transition class="pl-8 mt-1 space-y-1">
                <a href="{{ route('reviewer.usulan.index') }}" 
                   class="block py-2 px-4 text-gray-600 hover:bg-gray-100 rounded">Daftar Tugas Review</a>
            </div>
        </div>

        <!-- Laporan Kemajuan (Dropdown) -->
        <div>
            <button @click="openLaporan = !openLaporan" 
                    class="w-full flex items-center justify-between py-3 px-4 text-gray-700 hover:bg-gray-200 rounded mt-1">
                <span class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 17v-6a2 2 0 012-2h6M9 7h6a2 2 0 012 2v6M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Laporan Kemajuan
                </span>
                <svg :class="{'rotate-90': openLaporan}" class="h-4 w-4 transform transition-transform" 
                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="openLaporan" x-transition class="pl-8 mt-1 space-y-1">
                <a href="{{ route('reviewer.laporan-kemajuan.index') }}" 
                   class="block py-2 px-4 text-gray-600 hover:bg-gray-100 rounded">Daftar Laporan</a>
            </div>
        </div>

        {{-- Tambahkan menu lain jika perlu --}}
    </nav>
</aside>

<!-- Alpine.js -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
