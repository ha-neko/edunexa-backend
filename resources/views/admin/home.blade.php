@extends('component.layouts.admin.main')
@section('title', 'Dashboard')

@section('content')

<!-- AXIOS -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<div class="container-fluid">

    <!-- HEADER -->
    <div class="dashboard-header mb-4">

        <div>

            <h1 class="dashboard-title">
                Dashboard Admin
            </h1>
            <h4>V2.1.0</h4>

            <p class="dashboard-subtitle">
                Selamat datang kembali di sistem EDUNEXA Absensi
            </p>

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

                <h2>
                    Monitoring Sistem Absensi
                </h2>

                <p>
                    Kelola data siswa, kelas,
                    jurusan, dan aktivitas absensi
                    secara realtime.
                </p>

                <a
                    href="/scanabsen"
                    class="btn btn-primary hero-btn"
                >

                    <i class="fas fa-qrcode mr-2"></i>
                    Mulai Scan Absensi

                </a>

            </div>

            <div class="hero-icon">
                <i class="fas fa-fingerprint"></i>
            </div>

        </div>

    </div>

    <!-- STATISTIC -->
    <div class="row">

        <!-- TOTAL SISWA -->
        <div class="col-xl-3 col-md-6 mb-4">

            <div
                class="dashboard-card blue dashboard-link"
                onclick="goToPage('/mastersiswa')"
            >

                <div>

                    <div class="card-label">
                        Total Siswa
                    </div>

                    <div
                        class="card-value"
                        id="totalStudents"
                    >
                        0
                    </div>

                    <div class="card-growth">

                        <i class="fas fa-arrow-up"></i>
                        Klik untuk lihat siswa

                    </div>

                </div>

                <div class="card-icon">
                    <i class="fas fa-users"></i>
                </div>

            </div>

        </div>

        <!-- TOTAL KELAS -->
        <div class="col-xl-3 col-md-6 mb-4">

            <div
                class="dashboard-card green dashboard-link"
                onclick="goToPage('/masterkelas')"
            >

                <div>

                    <div class="card-label">
                        Total Kelas
                    </div>

                    <div
                        class="card-value"
                        id="totalClasses"
                    >
                        0
                    </div>

                    <div class="card-growth">

                        <i class="fas fa-school"></i>
                        Klik untuk lihat kelas

                    </div>

                </div>

                <div class="card-icon">
                    <i class="fas fa-school"></i>
                </div>

            </div>

        </div>

        <!-- TOTAL JURUSAN -->
        <div class="col-xl-3 col-md-6 mb-4">

            <div
                class="dashboard-card orange dashboard-link"
                onclick="goToPage('/masterjurusan')"
            >

                <div>

                    <div class="card-label">
                        Total Jurusan
                    </div>

                    <div
                        class="card-value"
                        id="totalMajors"
                    >
                        0
                    </div>

                    <div class="card-growth">

                        <i class="fas fa-layer-group"></i>
                        Klik untuk lihat jurusan

                    </div>

                </div>

                <div class="card-icon">
                    <i class="fas fa-layer-group"></i>
                </div>

            </div>

        </div>

        <!-- ABSENSI -->
        <div class="col-xl-3 col-md-6 mb-4">

            <div
                class="dashboard-card purple dashboard-link"
                onclick="goToPage('/scanabsen')"
            >

                <div>

                    <div class="card-label">
                        Absensi Hari Ini
                    </div>

                    <div
                        class="card-value"
                        id="todayAttendance"
                    >
                        0
                    </div>

                    <div class="card-growth">

                        <i class="fas fa-check-circle"></i>
                        Klik untuk scan absensi

                    </div>

                </div>

                <div class="card-icon">
                    <i class="fas fa-qrcode"></i>
                </div>

            </div>

        </div>

    </div>

    <!-- CONTENT -->
    <div class="row">

        <!-- QUICK ACTION -->
        <div class="col-lg-7 mb-4">

            <div class="modern-card">

                <div class="modern-card-header">

                    <h5>
                        Quick Action
                    </h5>

                </div>

                <div class="modern-card-body">

                    <div class="action-grid">

                        <!-- SISWA -->
                        <a
                            href="/mastersiswa"
                            class="action-item"
                        >

                            <div class="action-icon blue">

                                <i class="fas fa-user-graduate"></i>

                            </div>

                            <div>

                                <h6>
                                    Data Siswa
                                </h6>

                                <p>
                                    Kelola seluruh siswa
                                </p>

                            </div>

                        </a>

                        <!-- KELAS -->
                        <a
                            href="/masterkelas"
                            class="action-item"
                        >

                            <div class="action-icon green">

                                <i class="fas fa-school"></i>

                            </div>

                            <div>

                                <h6>
                                    Master Kelas
                                </h6>

                                <p>
                                    Management data kelas
                                </p>

                            </div>

                        </a>

                        <!-- JURUSAN -->
                        <a
                            href="/masterjurusan"
                            class="action-item"
                        >

                            <div class="action-icon orange">

                                <i class="fas fa-layer-group"></i>

                            </div>

                            <div>

                                <h6>
                                    Master Jurusan
                                </h6>

                                <p>
                                    Management data jurusan
                                </p>

                            </div>

                        </a>

                        <!-- SCANNER -->
                        <a
                            href="/scanabsen"
                            class="action-item"
                        >

                            <div class="action-icon purple">

                                <i class="fas fa-qrcode"></i>

                            </div>

                            <div>

                                <h6>
                                    Scanner QR
                                </h6>

                                <p>
                                    Scan absensi siswa
                                </p>

                            </div>

                        </a>

                    </div>

                </div>

            </div>

        </div>

        <!-- SYSTEM STATUS -->
        <div class="col-lg-5 mb-4">

            <div class="modern-card">

                <div class="modern-card-header">

                    <h5>
                        System Status
                    </h5>

                </div>

                <div class="modern-card-body">

                    <div class="status-item">

                        <span>
                            Server Status
                        </span>

                        <span class="badge badge-success">
                            Online
                        </span>

                    </div>

                    <div class="status-item">

                        <span>
                            Database
                        </span>

                        <span class="badge badge-primary">
                            Connected
                        </span>

                    </div>

                    <div class="status-item">

                        <span>
                            QR Scanner
                        </span>

                        <span class="badge badge-info">
                            Ready
                        </span>

                    </div>

                    <div class="status-item">

                        <span>
                            API Endpoint
                        </span>

                        <span
                            class="badge badge-success"
                            id="apiStatus"
                        >
                            Connected
                        </span>

                    </div>

                </div>

            </div>

            <!-- RECENT ACTIVITY -->
            <div class="modern-card mt-4">

                <div class="modern-card-header">

                    <h5>
                        Aktivitas Terbaru
                    </h5>

                </div>

                <div
                    class="modern-card-body"
                    id="recentActivity"
                >

                    <!-- AUTO RENDER -->

                </div>

            </div>

        </div>

    </div>

</div>

<style>

.dashboard-link{

    cursor:pointer;
    transition:0.3s;
}

.dashboard-link:hover{

    transform:translateY(-5px);
    opacity:0.95;
}

.activity-item{

    display:flex;
    align-items:flex-start;
    margin-bottom:18px;
}

.activity-dot{

    width:12px;
    height:12px;
    border-radius:50%;
    background:#4e73df;
    margin-right:12px;
    margin-top:6px;
}

</style>

<script>

/* =========================================
   CONFIG
========================================= */

const API_BASE =
'http://10.6.160.79:8081/api';

const token =
localStorage.getItem('token');

/* =========================================
   DATE
========================================= */

const today =
new Date().toLocaleDateString(
'id-ID',
{
    day:'2-digit',
    month:'long',
    year:'numeric'
});

document.getElementById(
'todayDate'
).innerHTML = today;

/* =========================================
   REDIRECT
========================================= */

function goToPage(url){

    window.location.href = url;

}

/* =========================================
   DASHBOARD DATA
========================================= */

async function loadDashboard(){

    try{

        const headers = {

            Authorization:
            `Bearer ${token}`,

            Accept:
            'application/json'
        };

        /* =========================
           STUDENTS
        ========================= */

        const studentRes =
        await axios.get(

            `${API_BASE}/admin/students`,
            { headers }

        );

        const students =
        studentRes.data.data ?? [];

        /* =========================
           CLASSES
        ========================= */

        const classRes =
        await axios.get(

            `${API_BASE}/admin/classrooms`,
            { headers }

        );

        const classes =
        classRes.data.data ?? [];

        /* =========================
           MAJORS
        ========================= */

        const majorRes =
        await axios.get(

            `${API_BASE}/admin/majors`,
            { headers }

        );

        const majors =
        majorRes.data.data ?? [];

        /* =========================
           ATTENDANCE
        ========================= */

        let attendance = [];

        try{

            const attendanceRes =
            await axios.get(

                `${API_BASE}/admin/attendance/today`,
                { headers }

            );

            attendance =
            attendanceRes.data.data ?? [];

        }catch(err){

            console.log(
                'Endpoint attendance tidak tersedia'
            );

        }

        /* =========================
           TOTAL DATA
        ========================= */

        document.getElementById(
            'totalStudents'
        ).innerHTML =
        students.length;

        document.getElementById(
            'totalClasses'
        ).innerHTML =
        classes.length;

        document.getElementById(
            'totalMajors'
        ).innerHTML =
        majors.length;

        document.getElementById(
            'todayAttendance'
        ).innerHTML =
        attendance.length;

        /* =========================
           ACTIVITY
        ========================= */

        renderActivity(
            students,
            classes,
            majors
        );

    }catch(error){

        console.log(error);

        document.getElementById(
            'apiStatus'
        ).classList.remove(
            'badge-success'
        );

        document.getElementById(
            'apiStatus'
        ).classList.add(
            'badge-danger'
        );

        document.getElementById(
            'apiStatus'
        ).innerHTML =
        'Disconnected';

    }

}

/* =========================================
   RECENT ACTIVITY
========================================= */

function renderActivity(
    students,
    classes,
    majors
){

    const activity =
    document.getElementById(
        'recentActivity'
    );

    activity.innerHTML = '';

    students.slice(0,2).forEach(student=>{

        activity.innerHTML += `

            <div class="activity-item">

                <div class="activity-dot"></div>

                <div>

                    <strong>
                        ${student.name}
                    </strong>

                    <p class="mb-0 text-muted">

                        Siswa aktif pada sistem

                    </p>

                </div>

            </div>

        `;

    });

    classes.slice(0,1).forEach(item=>{

        activity.innerHTML += `

            <div class="activity-item">

                <div class="activity-dot"></div>

                <div>

                    <strong>
                        ${item.class_name ?? item.name}
                    </strong>

                    <p class="mb-0 text-muted">

                        Kelas tersedia

                    </p>

                </div>

            </div>

        `;

    });

    majors.slice(0,1).forEach(item=>{

        activity.innerHTML += `

            <div class="activity-item">

                <div class="activity-dot"></div>

                <div>

                    <strong>
                        ${item.name}
                    </strong>

                    <p class="mb-0 text-muted">

                        Jurusan aktif

                    </p>

                </div>

            </div>

        `;

    });

}

/* =========================================
   INIT
========================================= */

loadDashboard();

</script>

@endsection