<!-- Sidebar -->
<link href="{{ asset('assets/css/custom_main.css') }}" rel="stylesheet">
<style>

    .nav-item h6{
        color:#fff !important;
        padding:4px 12px;
        border-radius:8px;
        box-shadow:0 4px 6px rgba(37,99,255,.3) !important;
    }

</style>
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- BRAND -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.home') }}">
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
        <a class="nav-link" href="{{ route('admin.home') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">
    <div class="sidebar-heading">Control Panel</div>

    <!-- MASTER USER -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.master-user') }}">
            <i class="fas fa-user"></i>
            <span>Master User</span>
        </a>
    </li>

    <!-- MASTER ROLE -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapserole">
            <i class="fas fa-building"></i>
            <span>Master Role</span>
        </a>
        <div id="collapserole" class="collapse" data-parent="#accordionSidebar">
            <div class="collapse-inner rounded">
                <h6 class="collapse-header">Role Panel:</h6>
                <hr class="sidebar-divider">
                <a class="collapse-item" href="{{ route('admin.master-guru') }}">Guru</a>
                <a class="collapse-item" href="{{ route('admin.master-siswa') }}">Siswa</a>
            </div>
        </div>
    </li>

    <!-- MASTER JURUSAN -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.master-jurusan') }}">
            <i class="fas fa-layer-group"></i>
            <span>Master Jurusan</span>
        </a>
    </li>

    <!-- MASTER KELAS -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.master-kelas') }}">
            <i class="fas fa-door-open"></i>
            <span>Master Kelas</span>
        </a>
    </li>

    <!-- JADWAL -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.jadwal') }}">
            <i class="fas fa-calendar-check"></i>
            <span>Jadwal</span>
        </a>
    </li>

    <!-- SHIFT / SESI -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.shift') }}">
            <i class="fas fa-clock"></i>
            <span>Shift</span>
        </a>
    </li>

    <!-- UPDATE LOG -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.update-log') }}">
            <i class="fas fa-history"></i>
            <span>Update Log</span>
        </a>
    </li>

    <hr class="sidebar-divider">
    <div class="sidebar-heading">Operational</div>

    <!-- SCANNER -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.scanner') }}">
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
        <button type="submit" class="nav-link logout-btn" style="background: rgb(37,37,37) !important;">
            <i class="fas fa-sign-out-alt"></i>
            Logout
        </button>
    </form>

</ul>