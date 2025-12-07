<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin P3M')</title>
    
    {{-- Tailwind CSS (Dipanggil lebih dulu) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="stylesheet" href="{{ asset('assets/vendors/simple-line-icons/css/simple-line-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/flag-icon-css/css/flag-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/jvectormap/jquery-jvectormap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/chartist/chartist.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vertical-light-layout/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sidebar-menu.css') }}">
    
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
    @yield('extra-css')
</head>

{{-- Menggunakan class bawaan template 'container-scroller' dan 'sidebar-fixed' untuk layout --}}
<body class="bg-gray-100 container-scroller sidebar-fixed"> 

    {{-- BARU: CONTAINER UTAMA --}}
    <div id="wrapper" class="flex min-h-screen"> 

        {{-- Sidebar --}}
        @include('admin.layouts.sidebar')

        {{-- Main Wrapper for Navbar and Content (Tailwind structure is kept, but adjusted) --}}
        <div class="main-panel flex-1 flex flex-col"> 

            {{-- Navbar --}}
            @include('admin.layouts.navbar')

            {{-- Konten Utama --}}
            <div class="content-wrapper flex-grow p-6"> 
                @yield('content')
            </div>

            {{-- Footer --}}
            @include('admin.layouts.footer')

        </div>
    </div>


    {{-- Vendor JS (Harus diletakkan di akhir body) --}}
    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    
    <script src="{{ asset('assets/vendors/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('assets/vendors/jvectormap/jquery-jvectormap.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
    <script src="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/vendors/chartist/chartist.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/progressbar.js/progressbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.cookie.js') }}"></script>
    
    <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('assets/js/misc.js') }}"></script>
    <script src="{{ asset('assets/js/settings.js') }}"></script>
    <script src="{{ asset('assets/js/todolist.js') }}"></script>
    
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    
    <script src="{{ asset('assets/js/sidebar-menu.js') }}"></script>
    
    @stack('scripts')
    @yield('extra-js')
</body>
</html>