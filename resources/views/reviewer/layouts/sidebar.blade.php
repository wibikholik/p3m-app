<aside class="w-64 bg-white shadow-md flex flex-col h-screen fixed top-0 overflow-y-auto" 
    x-data="{ 
        // Mengubah openLaporan menjadi openMonev untuk mencakup semua jenis laporan
        openPenelitian: {{ request()->routeIs('reviewer.usulan.*') ? 'true' : 'false' }}, 
        openMonev: {{ request()->routeIs('reviewer.laporan_kemajuan.*') || request()->routeIs('reviewer.laporan_akhir.*') ? 'true' : 'false' }} 
    }" x-cloak>
    
    @php
        $activeClass = 'bg-blue-600 text-white shadow font-semibold';
        $defaultClass = 'text-gray-700 hover:bg-blue-50 hover:text-blue-700';
        $activeSubClass = 'text-blue-700 bg-blue-100 font-semibold';
        $defaultSubClass = 'text-gray-600 hover:bg-gray-100 hover:text-gray-900';
    @endphp

    <div class="p-6 border-b border-gray-100">
        <h1 class="text-2xl font-bold text-gray-800">P3M Reviewer</h1>
    </div>

    <nav class="flex-1 mt-4 space-y-1 px-3">
        
        <a href="{{ route('reviewer.dashboard') }}" 
           class="block py-3 px-4 rounded-lg flex items-center gap-3 transition 
                  {{ request()->routeIs('reviewer.dashboard') ? $activeClass : $defaultClass }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M3 3h6v6H3V3zm0 12h6v6H3v-6zm12-12h6v6h-6V3zm0 12h6v6h-6v-6z"/>
            </svg>
            Dashboard
        </a>

        <div class="mt-2">
            <button @click="openPenelitian = !openPenelitian" 
                    class="w-full flex items-center justify-between py-3 px-4 rounded-lg transition 
                    {{ request()->routeIs('reviewer.usulan.*') ? $activeClass : $defaultClass }}">
                <span class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Review Usulan Awal
                </span>
                <svg :class="{'rotate-90': openPenelitian}" class="h-4 w-4 transform transition-transform" 
                      xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="openPenelitian" x-transition class="pl-8 mt-1 space-y-1">
                <a href="{{ route('reviewer.usulan.index') }}" 
                   class="block py-2 px-4 rounded-lg transition 
                   {{ request()->routeIs('reviewer.usulan.index') ? $activeSubClass : $defaultSubClass }}">Daftar Tugas Review</a>
            </div>
        </div>
        
        <div class="mt-2">
            <button @click="openMonev = !openMonev" 
                    class="w-full flex items-center justify-between py-3 px-4 rounded-lg transition 
                    {{ request()->routeIs('reviewer.laporan-kemajuan.*') || request()->routeIs('reviewer.laporan_akhir.*') ? $activeClass : $defaultClass }}">
                <span class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6a2 2 0 012-2h6m-6 4h6a2 2 0 002-2V9a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                    </svg>
                    Laporan & Monev
                </span>
                <svg :class="{'rotate-90': openMonev}" class="h-4 w-4 transform transition-transform" 
                      xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            
            <div x-show="openMonev" x-transition class="pl-8 mt-1 space-y-1">
                
                <a href="{{ route('reviewer.laporan-kemajuan.index') }}" 
                   class="block py-2 px-4 rounded-lg transition 
                   {{ request()->routeIs('reviewer.laporan-kemajuan.*') ? $activeSubClass : $defaultSubClass }}">Review Laporan Kemajuan</a>
                
                {{-- MENU BARU: LAPORAN AKHIR --}}
                <a href="{{ route('reviewer.laporan_akhir.index') }}" 
                   class="block py-2 px-4 rounded-lg transition 
                   {{ request()->routeIs('reviewer.laporan_akhir.*') ? $activeSubClass : $defaultSubClass }}">Review Laporan Akhir</a>
            </div>
        </div>

        {{-- Tambahkan menu lain jika perlu --}}
    </nav>
</aside>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>