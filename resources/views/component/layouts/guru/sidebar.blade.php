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

    <!-- SIDEBAR BRAND -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/home">

        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-graduation-cap"></i>
        </div>

        <div class="sidebar-brand-text mx-3">
            EDUNEXA GURU
            <sup>V2.1.0</sup>
        </div>

    </a>

    <!-- DIVIDER -->
    <hr class="sidebar-divider my-0">

    <!-- DASHBOARD -->
    <li class="nav-item active">

        <a class="nav-link" href="#">

            <i class="fas fa-fw fa-tachometer-alt"></i>

            <span style="color:#fff !important;">
                Dashboard
            </span>

        </a>

    </li>

    <!-- DIVIDER -->
    <hr class="sidebar-divider">

    <!-- HEADING -->
    <div class="sidebar-heading">
        Control Panel
    </div>

    <!-- MASTER USER -->
    <li class="nav-item">

        <a class="nav-link" href="/siswaguru">

            <i class="fas fa-user"></i>

            <span>
                Siswa
            </span>

        </a>

    </li>
   


    <!-- JADWAL -->
    <li class="nav-item">

        <a class="nav-link" href="/jadwalguru">

            <i class="fas fa-calendar-check"></i>

            <span style="color:#ff4949 !important;">
                Jadwal
            </span>

        </a>

    </li>

    <!-- UPDATE LOG -->
    <li class="nav-item">

        <a class="nav-link" href="/updatelog">

            <i class="fas fa-history"></i>

            <span style="color:#ff4949 !important;">
                Update Log
            </span>

        </a>

    </li>

    <!-- DIVIDER -->
    <hr class="sidebar-divider">

    <!-- HEADING -->
    <div class="sidebar-heading">
        Operational
    </div>

    <!-- SCANNER -->
    <li class="nav-item">

        <a class="nav-link" href="/scannerguru">

            <i class="fas fa-qrcode"></i>

            <span>
                Scanner Absensi
            </span>

        </a>

    </li>

    <!-- DIVIDER -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- SIDEBAR TOGGLER -->
    <div class="text-center d-none d-md-inline mb-4">

        <button
            class="sidebar-toggle-btn"
            id="sidebarToggleCustom"
        >

            <i class="fas fa-angle-left"></i>

        </button>

    </div>

    <!-- LOGOUT -->
    <li class="nav-item logout-item mt-auto">

        <a
            class="nav-link logout-btn"
            href="#"
            data-toggle="modal"
            data-target="#logoutModal"
        >

            <i class="fas fa-sign-out-alt"></i>

            <span>
                Logout
            </span>

        </a>

    </li>

</ul>