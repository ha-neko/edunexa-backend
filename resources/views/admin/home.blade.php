@extends('component.layouts.admin.main')
@section('title', 'Dashboard')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<div class="container-fluid">

    <!-- HEADER -->
    <div class="dashboard-header mb-4">
        <div>
            <h1 class="dashboard-title">Dashboard Admin</h1>
            <p class="dashboard-subtitle">Selamat datang kembali di sistem EDUNEXA Absensi</p>
        </div>
        <div class="dashboard-date">
            <i class="fas fa-calendar-alt mr-2"></i>
            <span id="todayDate"></span>
        </div>
    </div>

    <!-- HERO -->
    <div class="hero-banner mb-4">
        <div class="hero-content">
            <div>
                <h2>Monitoring Sistem Absensi</h2>
                <p>Kelola data siswa, kelas, jurusan, dan aktivitas absensi secara realtime.</p>
                <a href="{{ route('admin.scanner') }}" class="btn btn-primary hero-btn">
                    <i class="fas fa-qrcode mr-2"></i>Mulai Scan Absensi
                </a>
            </div>
            <div class="hero-icon">
                <i class="fas fa-fingerprint"></i>
            </div>
        </div>
    </div>

    <!-- STATISTIC -->
    <div class="row">

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-card blue dashboard-link" onclick="window.location.href='{{ route('admin.master-siswa') }}'">
                <div>
                    <div class="card-label">Total Siswa</div>
                    <div class="card-value" id="totalStudents">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                    <div class="card-growth">
                        <i class="fas fa-arrow-up"></i> Klik untuk lihat siswa
                    </div>
                </div>
                <div class="card-icon"><i class="fas fa-users"></i></div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-card green dashboard-link" onclick="window.location.href='{{ route('admin.master-kelas') }}'">
                <div>
                    <div class="card-label">Total Kelas</div>
                    <div class="card-value" id="totalClasses">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                    <div class="card-growth">
                        <i class="fas fa-school"></i> Klik untuk lihat kelas
                    </div>
                </div>
                <div class="card-icon"><i class="fas fa-school"></i></div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-card orange dashboard-link" onclick="window.location.href='{{ route('admin.master-jurusan') }}'">
                <div>
                    <div class="card-label">Total Jurusan</div>
                    <div class="card-value" id="totalMajors">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                    <div class="card-growth">
                        <i class="fas fa-layer-group"></i> Klik untuk lihat jurusan
                    </div>
                </div>
                <div class="card-icon"><i class="fas fa-layer-group"></i></div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-card purple dashboard-link" onclick="window.location.href='{{ route('admin.scanner') }}'">
                <div>
                    <div class="card-label">Absensi Hari Ini</div>
                    <div class="card-value" id="todayAttendance">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                    <div class="card-growth">
                        <i class="fas fa-check-circle"></i> Klik untuk scan absensi
                    </div>
                </div>
                <div class="card-icon"><i class="fas fa-qrcode"></i></div>
            </div>
        </div>

    </div>

    <!-- CONTENT ROW -->
    <div class="row">

        <!-- QUICK ACTION -->
        <div class="col-lg-7 mb-4">
            <div class="modern-card">
                <div class="modern-card-header"><h5>Quick Action</h5></div>
                <div class="modern-card-body">
                    <div class="action-grid">

                        <a href="{{ route('admin.master-siswa') }}" class="action-item">
                            <div class="action-icon blue"><i class="fas fa-user-graduate"></i></div>
                            <div><h6>Data Siswa</h6><p>Kelola seluruh siswa</p></div>
                        </a>

                        <a href="{{ route('admin.master-kelas') }}" class="action-item">
                            <div class="action-icon green"><i class="fas fa-school"></i></div>
                            <div><h6>Master Kelas</h6><p>Management data kelas</p></div>
                        </a>

                        <a href="{{ route('admin.master-jurusan') }}" class="action-item">
                            <div class="action-icon orange"><i class="fas fa-layer-group"></i></div>
                            <div><h6>Master Jurusan</h6><p>Management data jurusan</p></div>
                        </a>

                        <a href="{{ route('admin.scanner') }}" class="action-item">
                            <div class="action-icon purple"><i class="fas fa-qrcode"></i></div>
                            <div><h6>Scanner QR</h6><p>Scan absensi siswa</p></div>
                        </a>

                        <a href="{{ route('admin.master-guru') }}" class="action-item">
                            <div class="action-icon blue"><i class="fas fa-chalkboard-teacher"></i></div>
                            <div><h6>Data Guru</h6><p>Kelola seluruh guru</p></div>
                        </a>

                        <a href="{{ route('admin.master-user') }}" class="action-item">
                            <div class="action-icon green"><i class="fas fa-users-cog"></i></div>
                            <div><h6>Master User</h6><p>Kelola akun pengguna</p></div>
                        </a>

                    </div>
                </div>
            </div>
        </div>

        <!-- SYSTEM STATUS + ACTIVITY -->
        <div class="col-lg-5 mb-4">

            <!-- SYSTEM STATUS -->
            <div class="modern-card mb-4">
                <div class="modern-card-header"><h5>System Status</h5></div>
                <div class="modern-card-body">

                    <div class="status-item">
                        <span>Server Status</span>
                        <span class="badge badge-success">Online</span>
                    </div>

                    <div class="status-item">
                        <span>Database</span>
                        <span class="badge badge-primary">Connected</span>
                    </div>

                    <div class="status-item">
                        <span>QR Scanner</span>
                        <span class="badge badge-info">Ready</span>
                    </div>

                    <div class="status-item">
                        <span>API Endpoint</span>
                        <span class="badge badge-success" id="apiStatus">Checking...</span>
                    </div>

                    <div class="status-item">
                        <span>Login Sebagai</span>
                        <span class="badge badge-warning" id="loginAs">-</span>
                    </div>

                </div>
            </div>

            <!-- RECENT ACTIVITY -->
            <div class="modern-card">
                <div class="modern-card-header"><h5>Aktivitas Terbaru</h5></div>
                <div class="modern-card-body" id="recentActivity">
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-spinner fa-spin"></i> Memuat...
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<style>
.dashboard-link { cursor:pointer; transition:0.3s; }
.dashboard-link:hover { transform:translateY(-5px); opacity:0.95; }
.activity-item { display:flex; align-items:flex-start; margin-bottom:18px; }
.activity-dot { width:12px; height:12px; border-radius:50%; background:#4e73df; margin-right:12px; margin-top:6px; flex-shrink:0; }
</style>

<script>

const API_BASE    = '{{ rtrim(config("app.url"), "/") }}/api';
const token       = '{{ session("token") }}';
const axiosConfig = { headers: { Authorization: `Bearer ${token}`, Accept: 'application/json' } };

// DATE
document.getElementById('todayDate').innerHTML =
    new Date().toLocaleDateString('id-ID', { day:'2-digit', month:'long', year:'numeric' });

// LOGIN AS
document.getElementById('loginAs').innerHTML = '{{ session("role") ?? "admin" }}';

/* ── DASHBOARD ── */
async function loadDashboard() {
    try {

        const [studentRes, classRes, majorRes] = await Promise.all([
            axios.get(`${API_BASE}/admin/students?per_page=100`,   axiosConfig),
            axios.get(`${API_BASE}/admin/classrooms?per_page=100`, axiosConfig),
            axios.get(`${API_BASE}/admin/majors?per_page=100`,     axiosConfig),
        ]);

        const students = studentRes.data.data ?? [];
        const classes  = classRes.data.data   ?? [];
        const majors   = majorRes.data.data   ?? [];

        document.getElementById('totalStudents').innerHTML  = students.length;
        document.getElementById('totalClasses').innerHTML   = classes.length;
        document.getElementById('totalMajors').innerHTML    = majors.length;

        // Attendance
        try {
            const attendanceRes = await axios.get(`${API_BASE}/admin/attendances`, axiosConfig);
            document.getElementById('todayAttendance').innerHTML = attendanceRes.data.data?.length ?? 0;
        } catch(e) {
            document.getElementById('todayAttendance').innerHTML = '0';
        }

        // API Status
        document.getElementById('apiStatus').className   = 'badge badge-success';
        document.getElementById('apiStatus').innerHTML   = 'Connected';

        renderActivity(students, classes, majors);

    } catch(error) {
        console.error(error);
        ['totalStudents','totalClasses','totalMajors','todayAttendance'].forEach(id => {
            document.getElementById(id).innerHTML = '-';
        });
        document.getElementById('apiStatus').className = 'badge badge-danger';
        document.getElementById('apiStatus').innerHTML = 'Disconnected';
        document.getElementById('recentActivity').innerHTML =
            '<p class="text-muted text-center">Gagal memuat data.</p>';
    }
}

/* ── ACTIVITY ── */
function renderActivity(students, classes, majors) {
    const el = document.getElementById('recentActivity');
    el.innerHTML = '';

    if (students.length === 0 && classes.length === 0 && majors.length === 0) {
        el.innerHTML = '<p class="text-muted text-center">Belum ada aktivitas.</p>';
        return;
    }

    students.slice(0, 2).forEach(s => {
        el.innerHTML += `
        <div class="activity-item">
            <div class="activity-dot" style="background:#4e73df"></div>
            <div>
                <strong>${s.user?.name ?? '-'}</strong>
                <p class="mb-0 text-muted" style="font-size:12px;">Siswa terdaftar</p>
            </div>
        </div>`;
    });

    classes.slice(0, 1).forEach(c => {
        el.innerHTML += `
        <div class="activity-item">
            <div class="activity-dot" style="background:#1cc88a"></div>
            <div>
                <strong>${c.grade} ${c.group_number}</strong>
                <p class="mb-0 text-muted" style="font-size:12px;">Kelas tersedia</p>
            </div>
        </div>`;
    });

    majors.slice(0, 1).forEach(m => {
        el.innerHTML += `
        <div class="activity-item">
            <div class="activity-dot" style="background:#f6c23e"></div>
            <div>
                <strong>${m.major_name}</strong>
                <p class="mb-0 text-muted" style="font-size:12px;">Jurusan aktif</p>
            </div>
        </div>`;
    });
}

loadDashboard();

</script>

@endsection