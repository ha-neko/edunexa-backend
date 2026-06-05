
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<link rel="stylesheet" href="{{ asset('assets/css/style-view/custom_user.css') }}">

<div class="container-fluid">

    <!-- HEADER -->
    <div class="page-header mb-4">

        <div class="page-header-left">

            <!-- BAYN Logo Icon -->
            <div class="page-icon">
                <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="2" y="2" width="10" height="10" rx="2.5" fill="white" fill-opacity="0.9"/>
                    <rect x="14" y="2" width="10" height="10" rx="2.5" fill="white" fill-opacity="0.55"/>
                    <rect x="2" y="14" width="10" height="10" rx="2.5" fill="white" fill-opacity="0.55"/>
                    <rect x="14" y="14" width="10" height="10" rx="2.5" fill="white" fill-opacity="0.25"/>
                </svg>
            </div>

            <div class="page-title-text">
                <h1 class="h3 mb-0 font-weight-bold">Master User</h1>
                <p class="mb-0">Monitoring data siswa &amp; guru EDUNEXA</p>
            </div>

        </div>

        <button class="btn-add-user" data-toggle="modal" data-target="#createUserModal">
            <i class="fas fa-plus"></i>
            Tambah User
        </button>

    </div>

    <!-- STAT -->
    <div class="row mb-4">

        <div class="col-md-3 mb-3 mb-md-0">
            <div class="stat-card stat-total">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <small>Total User</small>
                <h2 id="totalUsers">0</h2>
            </div>
        </div>

        <div class="col-md-3 mb-3 mb-md-0">
            <div class="stat-card stat-siswa">
                <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
                <small>Siswa</small>
                <h2 id="totalStudents">0</h2>
            </div>
        </div>

        <div class="col-md-3 mb-3 mb-md-0">
            <div class="stat-card stat-guru">
                <div class="stat-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                <small>Guru</small>
                <h2 id="totalTeachers">0</h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card stat-active">
                <div class="stat-icon"><i class="fas fa-circle"></i></div>
                <small>Active</small>
                <h2 id="activeUsers">0</h2>
            </div>
        </div>

    </div>

    <!-- SEARCH & FILTER -->
    <div class="filter-bar mb-3">

        <div class="search-wrapper">
            <i class="fas fa-search search-icon"></i>
            <input id="searchInput" class="form-control" placeholder="Cari user berdasarkan nama atau email...">
        </div>

        <select id="roleFilter" class="form-control" style="width:180px; flex-shrink:0;">
            <option value="all">Semua Role</option>
            <option value="student">Siswa</option>
            <option value="teacher">Guru</option>
        </select>

    </div>

    <!-- TABLE -->
    <div class="table-card card border-0">
        <div class="card-body table-responsive p-0">

            <table class="table table-dark mb-0">
                <thead>
                    <tr>
                        <th style="width:50px">No</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th style="width:90px">Aksi</th>
                    </tr>
                </thead>

                <tbody id="userTable">
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="fas fa-spinner fa-spin"></i>
                                <p>Memuat data...</p>
                            </div>
                        </td>
                    </tr>
                </tbody>

            </table>

        </div>
    </div>

</div>

<!-- MODAL -->
<div class="modal fade" id="createUserModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5>
                    <i class="fas fa-user-plus mr-2" style="color:#4f8ef7"></i>
                    Tambah User
                </h5>
            </div>

            <div class="modal-body">

                <input id="name"     class="form-control mb-2" placeholder="Nama Lengkap">
                <input id="email"    class="form-control mb-2" placeholder="Email">
                <input id="password" class="form-control mb-2" placeholder="Password" type="password">

                <select id="role" class="form-control">
                    <option value="">Pilih Role</option>
                    <option value="student">Siswa</option>
                    <option value="teacher">Guru</option>
                </select>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button class="btn btn-primary" id="saveUserBtn">Simpan</button>
            </div>

        </div>
    </div>
</div>

<script>
    window._token = '{{ session("token") }}';
</script>
<script src="{{ asset('assets/js/asset/user.js') }}"></script>