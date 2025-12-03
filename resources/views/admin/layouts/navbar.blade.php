<header class="flex items-center justify-between bg-white shadow-lg px-6 py-4 sticky top-0 z-20 w-full border-b border-gray-200">

    <!-- Placeholder untuk mengimbangi sidebar (Asumsi sidebar 64px) -->
    <div class="w-64"></div>

    <!-- Search -->
    <div class="relative w-1/3 ml-4">
        <input type="text" placeholder="Cari di sini..."
               class="pl-10 pr-4 py-2 border border-gray-300 rounded-xl w-full text-sm text-gray-700
                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 shadow-sm">
        <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
    </div>

    <!-- Icons & Profile -->
    <div class="flex items-center space-x-6">

        <!-- Notifications -->
        <button class="relative p-2 rounded-full text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <span class="absolute top-0.5 right-0.5 inline-block w-2.5 h-2.5 bg-red-600 rounded-full border-2 border-white"></span>
        </button>

        <!-- Profile Dropdown (Menggunakan Alpine.js) -->
        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
            <button @click="open = !open" class="flex items-center p-1 rounded-full hover:bg-gray-100 transition focus:outline-none">
                <img class="w-9 h-9 rounded-full object-cover" src="https://placehold.co/40x40/4f46e5/ffffff?text=AD" alt="Profil Admin">
                <span class="ml-2 text-gray-800 font-semibold text-sm hidden sm:inline">Admin P3M</span>
                <svg class="w-4 h-4 ml-1 text-gray-600" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24" transition duration-200>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="open" x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform scale-90"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-90"
                 class="absolute right-0 mt-3 w-48 bg-white border border-gray-200 rounded-lg shadow-xl py-1 z-30 origin-top-right">

                <div class="px-4 py-2 text-sm text-gray-700 border-b border-gray-100">
                    Masuk sebagai **Admin**
                </div>

                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-50 transition text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Profil Saya
                </a>


                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left flex items-center px-4 py-2 text-red-600 hover:bg-red-50 transition text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3v-1a3 3 0 013-3h1m4 4h4a2 2 0 002-2v-3"></path></svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
<!-- Pastikan Alpine.js dimuat di layout utama jika belum ada -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
