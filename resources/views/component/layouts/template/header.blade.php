<!-- =========================================
     TOPBAR / HEADER MODERN DARK
========================================= -->

<nav class="navbar navbar-expand topbar static-top">

    <!-- LEFT -->
    <div class="d-flex align-items-center">

        <!-- MOBILE TOGGLE -->
        <button id="sidebarToggleTop"
            class="btn btn-toggle d-md-none mr-3">

            <i class="fas fa-bars"></i>

        </button>

        <!-- SEARCH -->
         <h3>@yield('title')</h3>
     

    </div>

    <!-- RIGHT -->
    <ul class="navbar-nav ml-auto align-items-center">

        <!-- SEARCH MOBILE -->
        <li class="nav-item dropdown no-arrow d-sm-none">

            <a class="nav-link icon-btn"
                href="#"
                id="searchDropdown"
                role="button"
                data-toggle="dropdown">

                <i class="fas fa-search"></i>

            </a>

            <div class="dropdown-menu dropdown-menu-right p-3 shadow">

                <form class="navbar-search w-100">

                    <div class="input-group">

                        <input type="text"
                            class="form-control"
                            placeholder="Cari sesuatu...">

                        <div class="input-group-append">

                            <button class="btn btn-search" type="button">
                                <i class="fas fa-search"></i>
                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </li>

       
       

        <!-- DIVIDER -->
        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- USER -->
        <li class="nav-item dropdown no-arrow">

            <a class="nav-link user-dropdown"
                href="#"
                id="userDropdown"
                role="button"
                data-toggle="dropdown">

                <div class="user-info">

                    <span class="user-name">
                        Admin
                    </span>

                    <span class="user-role">
                        Super Admin
                    </span>

                </div>

                <img class="img-profile"
                    src="{{ asset('img/undraw_profile.svg') }}">

            </a>

            <!-- USER MENU -->
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-dark shadow">

                <div class="dropdown-header-custom">
                    Akun
                </div>

                <a class="dropdown-item-custom" href="#">
                    <i class="fas fa-user"></i>
                    Profile
                </a>

                <a class="dropdown-item-custom" href="/webconfig">
                    <i class="fas fa-cog"></i>
                    Settings
                </a>

                <a class="dropdown-item-custom" href="#">
                    <i class="fas fa-history"></i>
                    Activity Log
                </a>

                <div class="dropdown-divider"></div>

                <a class="dropdown-item-custom text-danger"
                    href="#"
                    data-toggle="modal"
                    data-target="#logoutModal">

                    <i class="fas fa-sign-out-alt"></i>
                    Logout

                </a>

            </div>

        </li>

    </ul>

</nav>

<!-- =========================================
     LOGOUT MODAL
========================================= -->

<div class="modal fade"
    id="logoutModal"
    tabindex="-1"
    role="dialog">

    <div class="modal-dialog modal-dialog-centered"
        role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Konfirmasi Logout
                </h5>

                <button class="close"
                    type="button"
                    data-dismiss="modal">

                    <span>&times;</span>

                </button>

            </div>

            <div class="modal-body">

                Apakah kamu yakin ingin keluar dari sistem?

            </div>

            <div class="modal-footer">

                <button class="btn btn-secondary"
                    type="button"
                    data-dismiss="modal">

                    Batal

                </button>

                <a class="btn btn-primary"
                    href="/">

                    Logout

                </a>

            </div>

        </div>

    </div>

</div>