<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item navbar-brand-mini-wrapper">
      <a class="nav-link navbar-brand brand-logo-mini" href="{{ route('admin.dashboard') }}">
        <img src="{{ asset('assets/images/logo.svg') }}" alt="logo" /></a>
    </li>

    <li class="nav-item nav-category">
      <span class="nav-link">Dashboard</span>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.dashboard') }}">
        <span class="menu-title">Dashboard</span>
        <i class="icon-screen-desktop menu-icon"></i>
      </a>
    </li>

    <li class="nav-item nav-category"><span class="nav-link">Manajemen</span></li>

    <!-- Manajemen Pengumuman -->
    <li class="nav-item">
      <a class="nav-link menu-toggle" href="javascript:void(0);" onclick="toggleMenu(this, 'attention')">
        <span class="menu-title">Manajemen Pengumuman</span>
        <i class="icon-layers menu-icon"></i>
      </a>
      <div class="collapse-menu" id="attention" style="display: none;">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{ route('admin.pengumuman.index') }}">Daftar Pengumuman</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{ route('admin.kategori-pengumuman.index') }}">Daftar Kategori</a></li>
        </ul>
      </div>
    </li>

    <!-- Penelitian & Pengabdian -->
    <li class="nav-item">
      <a class="nav-link menu-toggle" href="javascript:void(0);" onclick="toggleMenu(this, 'pp')">
        <span class="menu-title">Penelitian & Pengabdian</span>
        <i class="icon-globe menu-icon"></i>
      </a>
      <div class="collapse-menu" id="pp" style="display: none;">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{ route('admin.usulan.index') }}">Daftar Penelitian</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{ route('admin.usulan.index') }}">Daftar Pengabdian</a></li>
        </ul>
      </div>
    </li>

    <!-- Manajemen Operasional -->
    <li class="nav-item">
      <a class="nav-link menu-toggle" href="javascript:void(0);" onclick="toggleMenu(this, 'operasional')">
        <span class="menu-title">Manajemen Operasional</span>
        <i class="icon-book-open menu-icon"></i>
      </a>
      <div class="collapse-menu" id="operasional" style="display: none;">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{ route('admin.usulan.index') }}">Manajemen Usulan</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{ route('admin.kelengkapan.index') }}">Manajemen Kelengkapan Administrasi</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{ route('admin.penilaian.index') }}">Manajemen Penilaian</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{ route('admin.users.index') }}">Manajemen User</a></li>
        </ul>
      </div>
    </li>
  </ul>
</nav>
