<h2 style="color: #ff4949 !important;">--Masih dalam tahap pengembangan--</h2>

<div class="container-fluid">

    <!-- HEADER -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">

        <div>

            <h1 class="h3 mb-1 text-white font-weight-bold">
                Update Log
            </h1>

            <p class="mb-0 text-muted">
                Riwayat perkembangan dan pembaruan sistem EDUNEXA hari ini.
            </p>

        </div>

        <div class="badge badge-primary px-4 py-2 shadow-sm">

            <i class="fas fa-code-branch mr-2"></i>
            Version 2.1.0

        </div>

    </div>

    <!-- TIMELINE -->
    <div class="row">

        <div class="col-lg-12">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <!-- ITEM -->
                    <div class="update-item">

                        <div class="update-icon bg-primary">

                            <i class="fas fa-home"></i>

                        </div>

                        <div class="update-content">

                            <div class="d-flex justify-content-between flex-wrap">

                                <h5 class="text-white font-weight-bold">
                                    Dashboard Integration Update
                                </h5>

                                <small class="text-muted">
                                    Hari Ini
                                </small>

                            </div>

                            <p class="text-muted mb-2">

                                Widget dashboard sekarang sudah terhubung
                                langsung dengan halaman terkait.

                            </p>

                            <ul class="text-light pl-3">

                                <li>
                                    Widget Total Siswa → <strong>/mastersiswa</strong>
                                </li>

                                <li>
                                    Widget Total Kelas → <strong>/masterkelas</strong>
                                </li>

                                <li>
                                    Widget Total Jurusan → <strong>/masterjurusan</strong>
                                </li>

                                <li>
                                    Widget Absensi → <strong>/scanabsen</strong>
                                </li>

                            </ul>

                        </div>

                    </div>

                    <!-- ITEM -->
                    <div class="update-item">

                        <div class="update-icon bg-success">

                            <i class="fas fa-school"></i>

                        </div>

                        <div class="update-content">

                            <div class="d-flex justify-content-between flex-wrap">

                                <h5 class="text-white font-weight-bold">
                                    Master Kelas Improvement
                                </h5>

                                <small class="text-muted">
                                    Hari Ini
                                </small>

                            </div>

                            <p class="text-muted mb-2">

                                Halaman Master Kelas telah diperbaiki
                                agar terpisah dengan Master Jurusan.

                            </p>

                            <ul class="text-light pl-3">

                                <li>
                                    Classroom dan Major dipisahkan
                                </li>

                                <li>
                                    Data kelas menggunakan endpoint classroom
                                </li>

                                <li>
                                    Filter jurusan diperbaiki
                                </li>

                                <li>
                                    Statistik kelas aktif & nonaktif ditambahkan
                                </li>

                            </ul>

                        </div>

                    </div>

                    <!-- ITEM -->
                    <div class="update-item">

                        <div class="update-icon bg-warning">

                            <i class="fas fa-calendar-alt"></i>

                        </div>

                        <div class="update-content">

                            <div class="d-flex justify-content-between flex-wrap">

                                <h5 class="text-white font-weight-bold">
                                    Jadwal Pelajaran Dynamic UI
                                </h5>

                                <small class="text-muted">
                                    Hari Ini
                                </small>

                            </div>

                            <p class="text-muted mb-2">

                                Sistem jadwal pelajaran kini dirancang
                                agar dapat digunakan untuk masing-masing kelas.

                            </p>

                            <ul class="text-light pl-3">

                                <li>
                                    Jadwal dipisahkan berdasarkan kelas
                                </li>

                                <li>
                                    Struktur frontend siap integrasi API
                                </li>

                                <li>
                                    Filter jurusan & angkatan diperjelas
                                </li>

                                <li>
                                    Card jadwal diperbaiki agar lebih modern
                                </li>

                            </ul>

                        </div>

                    </div>

                    <!-- ITEM -->
                    <div class="update-item">

                        <div class="update-icon bg-danger">

                            <i class="fas fa-plug"></i>

                        </div>

                        <div class="update-content">

                            <div class="d-flex justify-content-between flex-wrap">

                                <h5 class="text-white font-weight-bold">
                                    API & Frontend Synchronization
                                </h5>

                                <small class="text-muted">
                                    Hari Ini
                                </small>

                            </div>

                            <p class="text-muted mb-2">

                                Frontend sekarang lebih sinkron
                                dengan endpoint backend.

                            </p>

                            <ul class="text-light pl-3">

                                <li>
                                    Axios request diperbaiki
                                </li>

                                <li>
                                    Authorization Bearer Token digunakan
                                </li>

                                <li>
                                    Endpoint dashboard disesuaikan
                                </li>

                                <li>
                                    Status API otomatis berubah saat disconnect
                                </li>

                            </ul>

                        </div>

                    </div>

                    <!-- ITEM -->
                    <div class="update-item mb-0">

                        <div class="update-icon bg-info">

                            <i class="fas fa-palette"></i>

                        </div>

                        <div class="update-content">

                            <div class="d-flex justify-content-between flex-wrap">

                                <h5 class="text-white font-weight-bold">
                                    UI Enhancement
                                </h5>

                                <small class="text-muted">
                                    Hari Ini
                                </small>

                            </div>

                            <p class="text-muted mb-2">

                                Tampilan admin panel dibuat lebih modern
                                dan interaktif.

                            </p>

                            <ul class="text-light pl-3">

                                <li>
                                    Hover animation pada dashboard widget
                                </li>

                                <li>
                                    Activity timeline ditambahkan
                                </li>

                                <li>
                                    Card modern style diperbaiki
                                </li>

                                <li>
                                    Responsive layout lebih stabil
                                </li>

                            </ul>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<style>

.update-item{

    display:flex;
    gap:20px;
    position:relative;
    padding-bottom:35px;
    margin-bottom:35px;
    border-bottom:1px solid rgba(255,255,255,0.06);
}

.update-item:last-child{

    border-bottom:none;
}

.update-icon{

    min-width:60px;
    height:60px;
    border-radius:18px;

    display:flex;
    align-items:center;
    justify-content:center;

    font-size:22px;
    color:white;

    box-shadow:0 10px 25px rgba(0,0,0,0.2);
}

.update-content{

    width:100%;
}

.update-content p{

    line-height:1.7;
}

.update-content ul li{

    margin-bottom:8px;
}

.card{

    background:rgba(20,20,35,0.95);
    border-radius:24px;
}

</style>