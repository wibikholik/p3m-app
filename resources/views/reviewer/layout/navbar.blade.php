<header class="flex items-center justify-between bg-white shadow px-6 py-4 sticky top-4 z-10 ml-5">
    <!-- Search -->
    <div class="relative w-1/3">
        <input type="text" placeholder="Search..." class="pl-10 pr-4 py-2 border rounded-lg w-full focus:outline-none focus:ring focus:border-blue-300">
        <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
    </div>

    <!-- Icons -->
    <div class="flex items-center space-x-6">

        <!-- Notifications -->
        <button class="relative focus:outline-none">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <span class="absolute top-0 right-0 inline-block w-2 h-2 bg-red-600 rounded-full"></span>
        </button>

        <!-- Profile Dropdown -->
        <div class="relative">
            <button onclick="document.getElementById('dropdown').classList.toggle('hidden')" class="flex items-center focus:outline-none">
                <img class="w-8 h-8 rounded-full" src="https://wallpapers.com/images/hd/cool-profile-picture-qej7j2ekuor93ss7.jpg" alt="Profil Admin">
                <span class="ml-2 text-gray-700 font-medium">{{ Auth::user()->name }}</span>
                <svg class="w-4 h-4 ml-1 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div id="dropdown" class="absolute right-0 mt-2 w-44 bg-white border rounded shadow-lg hidden">

                <!-- HAK ISTIMEWA: Jika user punya role reviewer -->
                @if(Auth::user()->hasRole('dosen'))
                    <a href="{{ route('dosen.dashboard') }}"
                       class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                       Masuk sebagai Dosen
                    </a>
                @endif

                <a href="" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                    Profile
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
