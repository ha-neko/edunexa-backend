<link href="{{ asset('assets/css/custom_main.css') }}" rel="stylesheet">

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- BRAND -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('guru.home') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <div class="sidebar-brand-text mx-3">
            EDUNEXA <sup>V2.1.0</sup>
        </div>
    </a>

    <hr class="sidebar-divider my-0">

    <!-- DASHBOARD -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('guru.home') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">
    <div class="sidebar-heading">Menu Guru</div>

    <!-- KELAS -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('guru.kelas') }}">
            <i class="fas fa-school"></i>
            <span>Kelas Saya</span>
        </a>
    </li>

    <!-- ABSENSI -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('guru.absensi') }}">
            <i class="fas fa-clipboard-list"></i>
            <span>Data Absensi</span>
        </a>
    </li>

    <hr class="sidebar-divider">
    <div class="sidebar-heading">Operational</div>

    <!-- SCANNER -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('guru.scanner') }}">
            <i class="fas fa-qrcode"></i>
            <span>Scanner Absensi</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline mb-4">
        <button class="sidebar-toggle-btn" id="sidebarToggleCustom">
            <i class="fas fa-angle-left"></i>
        </button>
    </div>

    <!-- LOGOUT -->
    <form action="{{ route('logout') }}" method="POST" class="nav-item mt-auto">
        @csrf
        <button type="submit" class="nav-link logout-btn" style="background:rgb(37,37,37) !important;">
            <i class="fas fa-sign-out-alt"></i>
            Logout
        </button>
    </form>

</ul>