<aside class="w-64 bg-white shadow-lg flex flex-col h-screen fixed top-0 overflow-y-auto" 
    x-data="{ 
        // Logic for dropdown active state
        openUsulanMonev: {{ request()->routeIs('admin.usulan.*') || request()->routeIs('admin.monev.laporan_kemajuan.*') || request()->routeIs('admin.monev.laporan_akhir.*') ? 'true' : 'false' }},
        openPengumuman: {{ request()->routeIs('admin.pengumuman.*') || request()->routeIs('admin.kategori-pengumuman.*') ? 'true' : 'false' }}, 
        openOperasional: {{ request()->routeIs('admin.kelengkapan.*') || request()->routeIs('admin.penilaian.*') || request()->routeIs('admin.users.*') ? 'true' : 'false' }} 
    }">
    
    <div class="p-6 border-b border-gray-200 bg-white">
        <h1 class="text-xl font-extrabold text-gray-900 tracking-wider">P3M DASHBOARD</h1>
    </div>

    <nav class="flex-1 mt-4 space-y-1 px-3">
        @php
            // Classes for styling
            $activeClass = 'bg-blue-500 text-white shadow-md font-semibold';
            $defaultClass = 'text-gray-700 hover:bg-gray-100 hover:text-gray-900';
            $activeSubClass = 'text-blue-700 bg-blue-100 font-semibold';
            $defaultSubClass = 'text-gray-600 hover:bg-gray-100 hover:text-gray-900';
        @endphp

        <a href="{{ route('admin.dashboard') }}" 
            class="block py-3 px-4 rounded-lg flex items-center gap-3 transition duration-150 ease-in-out 
                   {{ request()->routeIs('admin.dashboard') ? $activeClass : $defaultClass }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h6v6H3V3zm0 12h6v6H3v-6zm12-12h6v6h-6V3zm0 12h6v6h-6v-6z"/>
            </svg>
            Dashboard
        </a>

        <button @click="openPengumuman = !openPengumuman" 
                class="w-full flex items-center justify-between py-3 px-4 rounded-lg transition duration-150 ease-in-out 
                        {{ request()->routeIs('admin.pengumuman.*') || request()->routeIs('admin.kategori-pengumuman.*') ? $activeClass : $defaultClass }}">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-2.236 9.168-5.584C18.354 1.832 18 3.65 18 4.5c0 1.25.333 2.45 1 3.5" />
                </svg>
                Manajemen Pengumuman
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" :class="{'rotate-90': openPengumuman}" class="h-4 w-4 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>
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

        
        {{-- ========================================================== --}}
        {{-- ========================================================== --}}
        <div x-data="{ open: openUsulanMonev }" class="mb-2">

            <button @click="open = !open"
                class="w-full py-3 px-4 flex justify-between items-center rounded-lg transition duration-150 ease-in-out 
                {{ request()->routeIs('admin.usulan.*') 
                    || request()->routeIs('admin.monev.laporan_kemajuan.*') 
                    || request()->routeIs('admin.monev.laporan_akhir.*') 
                    ? $activeClass : $defaultClass }}">

                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Manajemen Usulan & Monev
                </div>

                <svg :class="open ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4 transform transition-transform duration-200"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="open" x-collapse x-cloak class="mt-1 ml-6 space-y-1">

                <a href="{{ route('admin.usulan.index') }}"
                    class="block py-2 px-4 rounded-lg flex items-center gap-3 transition duration-150 ease-in-out
                    {{ request()->routeIs('admin.usulan.*') ? $activeSubClass : $defaultSubClass }}">
                    Manajemen Usulan (Awal)
                </a>

                <a href="{{ route('admin.monev.laporan_kemajuan.index') }}"
                    class="block py-2 px-4 rounded-lg flex items-center gap-3 transition duration-150 ease-in-out
                    {{ request()->routeIs('admin.monev.laporan_kemajuan.*') ? $activeSubClass : $defaultSubClass }}">
                    Monev Laporan Kemajuan
                </a>
                
                <a href="{{ route('admin.monev.laporan_akhir.index') }}"
                    class="block py-2 px-4 rounded-lg flex items-center gap-3 transition duration-150 ease-in-out
                    {{ request()->routeIs('admin.monev.laporan_akhir.*') ? $activeSubClass : $defaultSubClass }}">
                    Monev Laporan Akhir
                </a>

            </div>
        </div>

        {{-- ========================================================== --}}
        {{-- ========================================================== --}}
        <div x-data="{ open: openOperasional }" class="mb-2">

            <button @click="open = !open"
                class="w-full py-3 px-4 flex justify-between items-center rounded-lg transition duration-150 ease-in-out 
                {{ request()->routeIs('admin.kelengkapan.*') 
                    || request()->routeIs('admin.penilaian.*') 
                    || request()->routeIs('admin.users.*') 
                    ? $activeClass : $defaultClass }}">

                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    Manajemen Operasional
                </div>

                <svg :class="open ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4 transform transition-transform duration-200"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="open" x-collapse x-cloak class="mt-1 ml-6 space-y-1">

                <a href="{{ route('admin.kelengkapan.index') }}"
                    class="block py-2 px-4 rounded-lg flex items-center gap-3 transition duration-150 ease-in-out
                    {{ request()->routeIs('admin.kelengkapan.*') ? $activeSubClass : $defaultSubClass }}">
                    Manajemen Kelengkapan Administrasi
                </a>

                <a href="{{ route('admin.penilaian.index') }}"
                    class="block py-2 px-4 rounded-lg flex items-center gap-3 transition duration-150 ease-in-out
                    {{ request()->routeIs('admin.penilaian.*') ? $activeSubClass : $defaultSubClass }}">
                    Manajemen Penilaian
                </a>

                <a href="{{ route('admin.users.index') }}"
                    class="block py-2 px-4 rounded-lg flex items-center gap-3 transition duration-150 ease-in-out
                    {{ request()->routeIs('admin.users.*') ? $activeSubClass : $defaultSubClass }}">
                    Manajemen User
                </a>
            </div>
        </div>

    </nav>
</aside>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>