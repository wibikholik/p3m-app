<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
  <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
    <a class="navbar-brand brand-logo" href="{{ route('admin.dashboard') }}">
      <img src="{{ asset('assets/images/logo.svg') }}" alt="logo" class="logo-dark" />
      <img src="{{ asset('assets/images/logo-light.svg') }}" alt="logo-light" class="logo-light">
    </a>
    <a class="navbar-brand brand-logo-mini" href="{{ route('admin.dashboard') }}"><img src="{{ asset('assets/images/logo-mini.svg') }}" alt="logo" /></a>
    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
      <span class="icon-menu"></span>
    </button>
  </div>
  <div class="navbar-menu-wrapper d-flex align-items-center">
    <h5 class="mb-0 font-weight-medium d-none d-lg-flex">P3M POLSUB</h5>
    <ul class="navbar-nav navbar-nav-right">
      <form class="search-form d-none d-md-block" action="#">
        <i class="icon-magnifier"></i>
        <input type="search" class="form-control" placeholder="Search Here" title="Search here">
      </form>
      <li class="nav-item dropdown">
        <a class="nav-link count-indicator message-dropdown" id="messageDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="icon-speech"></i>
          <span class="count">7</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0" aria-labelledby="messageDropdown">
          <a class="dropdown-item py-3">
            <p class="mb-0 font-weight-medium float-start me-2">You have 7 unread mails </p>
            <span class="badge badge-pill badge-primary float-end">View all</span>
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item preview-item">
            <div class="preview-thumbnail">
              <img src="{{ asset('assets/images/faces/face10.jpg') }}" alt="image" class="img-sm profile-pic">
            </div>
            <div class="preview-item-content flex-grow py-2">
              <p class="preview-subject ellipsis font-weight-medium text-dark">Marian Garner </p>
              <p class="font-weight-light small-text"> The meeting is cancelled </p>
            </div>
          </a>
          <a class="dropdown-item preview-item">
            <div class="preview-thumbnail">
              <img src="{{ asset('assets/images/faces/face12.jpg') }}" alt="image" class="img-sm profile-pic">
            </div>
            <div class="preview-item-content flex-grow py-2">
              <p class="preview-subject ellipsis font-weight-medium text-dark">David Grey </p>
              <p class="font-weight-light small-text"> The meeting is cancelled </p>
            </div>
          </a>
          <a class="dropdown-item preview-item">
            <div class="preview-thumbnail">
              <img src="{{ asset('assets/images/faces/face1.jpg') }}" alt="image" class="img-sm profile-pic">
            </div>
            <div class="preview-item-content flex-grow py-2">
              <p class="preview-subject ellipsis font-weight-medium text-dark">Travis Jenkins </p>
              <p class="font-weight-light small-text"> The meeting is cancelled </p>
            </div>
          </a>
        </div>
      </li>
      <li class="nav-item dropdown d-none d-xl-inline-flex user-dropdown">
        <a class="nav-link dropdown-toggle" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
          <img class="img-xs rounded-circle ms-2" src="{{ asset('assets/images/faces/face8.jpg') }}" alt="Profile image"> <span class="font-weight-normal">{{ auth()->user()->name ?? 'User' }}</span></a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
          <div class="dropdown-header text-center">
            <img class="img-md rounded-circle" src="{{ asset('assets/images/faces/face8.jpg') }}" alt="Profile image">
            <p class="mb-1 mt-3">{{ auth()->user()->name ?? 'User' }}</p>
            <p class="font-weight-light text-muted mb-0">{{ auth()->user()->email ?? 'user@example.com' }}</p>
          </div>
          <a class="dropdown-item"><i class="dropdown-item-icon icon-user text-primary"></i> My Profile</a>
          <form method="POST" action="{{ route('logout') }}" class="d-inline">
            @csrf
            <button type="submit" class="dropdown-item"><i class="dropdown-item-icon icon-power text-primary"></i> Sign Out</button>
          </form>
        </div>
      </li>
    </ul>
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
      <span class="icon-menu"></span>
    </button>
  </div>
</nav>
