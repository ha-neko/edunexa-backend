<link href="{{ asset('assets/css/custom_scanner.css') }}" rel="stylesheet">
<script src="https://unpkg.com/html5-qrcode"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<div class="container-fluid">

    <!-- PAGE TITLE -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-100 font-weight-bold">Scanner Absensi</h1>
            <p class="mb-0 text-muted">Scan QR Code siswa untuk melakukan absensi otomatis.</p>
        </div>
        <span class="badge badge-primary p-2 shadow-sm" style="color:#fff !important;">
            Sistem Absensi Digital
        </span>
    </div>

    <div class="row">

        <!-- LEFT - SCANNER -->
        <div class="col-lg-3 mb-4">
            <div class="card shadow-lg border-0 scanner-card">

                <div class="card-header py-3 d-flex align-items-center justify-content-between">
                    <h5 class="m-0 font-weight-bold text-primary">Scanner</h5>
                    <span class="badge badge-danger px-3 py-2" id="cameraStatus"
                        style="color:#fff !important; width:140px;">
                        Kamera Tidak Aktif
                    </span>
                </div>

                <div class="card-body text-center">
                    <div class="scanner-box">
                        <div id="reader"></div>
                        <div class="scanner-overlay"></div>
                        <div class="scanner-line"></div>
                    </div>

                    <div class="scanner-action mt-4">
                        <button class="scanner-btn start-btn" id="startScanner">
                            <i class="fas fa-camera mr-2"></i>
                            <span>Mulai Scan</span>
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <!-- RIGHT - DATA SISWA -->
        <div class="col-lg-9">
            <div class="card shadow-lg border-0 student-card">

                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Data Siswa</h6>
                </div>

                <div class="card-body">

                    <div class="student-profile text-center">
                        <img id="studentAvatar"
                            src="https://ui-avatars.com/api/?name=Siswa&background=2563eb&color=fff"
                            class="student-avatar mb-3" alt="Student">
                        <h4 class="student-name" id="studentName">Menunggu Scan...</h4>
                        <p class="student-class" id="studentClass">-</p>
                    </div>

                    <hr>

                    <div class="student-info">
                        <div class="info-item d-flex justify-content-between py-2">
                            <span>NIS</span>
                            <strong id="studentNis">-</strong>
                        </div>
                        <div class="info-item d-flex justify-content-between py-2">
                            <span>Status</span>
                            <strong class="text-success" id="studentStatus">Belum Scan</strong>
                        </div>
                        <div class="info-item d-flex justify-content-between py-2">
                            <span>Jam Masuk</span>
                            <strong id="studentJamMasuk">-</strong>
                        </div>
                        <div class="info-item d-flex justify-content-between py-2">
                            <span>Jam Pulang</span>
                            <strong id="studentJamPulang">-</strong>
                        </div>
                        <div class="info-item d-flex justify-content-between py-2">
                            <span>Tanggal</span>
                            <strong id="studentTanggal">-</strong>
                        </div>
                    </div>

                    <div class="attendance-success mt-4 d-flex align-items-center"
                        id="successBox" style="display:none !important;">
                        <i class="fas fa-check-circle text-success mr-3" style="font-size:35px;"></i>
                        <div>
                            <h5 class="mb-1">Absensi Berhasil</h5>
                            <p class="mb-0" id="successMessage">QR Code berhasil dipindai</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <!-- TABLE -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-lg border-0">

                <div class="card-header py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="m-0 font-weight-bold text-primary">Absensi Hari Ini</h5>
                        <small class="text-muted">Daftar siswa yang sudah scan QR</small>
                    </div>
                    <span class="badge badge-success px-3 py-2" id="attendanceCount"
                        style="color:#fff !important;">
                        0 Siswa Hadir
                    </span>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless align-items-center">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Siswa</th>
                                    <th>Kelas</th>
                                    <th>Jam Masuk</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="attendanceTable"></tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

<style>
.scanner-action { display:flex; gap:12px; justify-content:center; }
.scanner-btn { border:none; outline:none; padding:12px 22px; border-radius:14px; font-weight:600; font-size:14px; display:flex; align-items:center; justify-content:center; transition:.25s ease; color:#fff; min-width:130px; }
.start-btn { background:linear-gradient(135deg, #2563eb, #1d4ed8); }
.scanner-btn:hover    { transform:translateY(-2px); }
.scanner-btn:disabled { opacity:.7; cursor:not-allowed; }
</style>

<script>

/* =====================================
   CONFIG
===================================== */
const API_URL     = '{{ rtrim(config("app.url"), "/") }}/api/attendance/scan';
const token       = '{{ session("token") }}';
const axiosConfig = {
    headers: {
        Authorization : `Bearer ${token}`,
        Accept        : 'application/json',
        'Content-Type': 'application/json'
    }
};

let scanner       = null;
let totalScan     = 0;
let scannerActive = false;
let lastScanTime  = 0;

const startBtn     = document.getElementById('startScanner');
const cameraStatus = document.getElementById('cameraStatus');

/* =====================================
   STATUS UI
===================================== */
function setScannerStatus(active) {
    scannerActive     = active;
    if (active) {
        cameraStatus.classList.replace('badge-danger', 'badge-success');
        cameraStatus.innerHTML = 'Scanner Aktif';
        startBtn.disabled      = true;
    } else {
        cameraStatus.classList.replace('badge-success', 'badge-danger');
        cameraStatus.innerHTML = 'Kamera Tidak Aktif';
        startBtn.disabled      = false;
    }
}

/* =====================================
   START SCANNER
===================================== */
startBtn.addEventListener('click', async () => {

    if (scannerActive) return;

    scanner = new Html5Qrcode("reader");

    try {

        await scanner.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: { width: 220, height: 220 } },

            async function(decodedText) {

                // DEBUG
                console.log("QR TERBACA:", decodedText);
                alert("QR TERBACA: " + decodedText);

                // Cegah scan berulang dalam 3 detik
                const now = Date.now();
                if (now - lastScanTime < 3000) return;
                lastScanTime = now;

                try {
                    console.log("HIT API:", API_URL);
                    console.log("TOKEN:", token);

                    const response = await axios.post(
                        API_URL,
                        { qr_token: decodedText },
                        axiosConfig
                    );

                    console.log("RESPONSE:", response.data);

                    const data = response.data.data;

                    // UPDATE UI
                    document.getElementById('studentName').innerHTML     = data.student?.name ?? 'Tidak diketahui';
                    document.getElementById('studentClass').innerHTML    = data.student?.classroom?.name ?? '-';
                    document.getElementById('studentNis').innerHTML      = data.student?.nis ?? '-';
                    document.getElementById('studentStatus').innerHTML   = data.status ?? 'Hadir';
                    document.getElementById('studentJamMasuk').innerHTML = data.check_in ?? '-';
                    document.getElementById('studentJamPulang').innerHTML = data.check_out ?? '-';
                    document.getElementById('studentTanggal').innerHTML  = data.date ?? '-';

                    document.getElementById('studentAvatar').src =
                        `https://ui-avatars.com/api/?name=${encodeURIComponent(data.student?.name ?? 'Siswa')}&background=2563eb&color=fff`;

                    document.getElementById('successBox').style.display = 'flex';
                    document.getElementById('successMessage').innerHTML = response.data.message ?? 'Absensi berhasil';

                    // TABLE
                    totalScan++;
                    document.getElementById('attendanceCount').innerHTML = `${totalScan} Siswa Hadir`;
                    document.getElementById('attendanceTable').innerHTML += `
                        <tr>
                            <td>${totalScan}</td>
                            <td class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(data.student?.name ?? 'Siswa')}&background=2563eb&color=fff"
                                    width="45" class="rounded-circle mr-3">
                                <div>
                                    <strong>${data.student?.name ?? '-'}</strong><br>
                                    <small>${data.student?.nis ?? '-'}</small>
                                </div>
                            </td>
                            <td>${data.student?.classroom?.name ?? '-'}</td>
                            <td>${data.check_in ?? '-'}</td>
                            <td><span class="badge badge-success px-3 py-2">${data.status ?? 'Hadir'}</span></td>
                        </tr>`;

                } catch(apiError) {
                    console.log("ERROR STATUS:", apiError.response?.status);
                    console.log("ERROR MESSAGE:", apiError.response?.data);
                    alert(apiError.response?.data?.message ?? 'Absensi gagal');
                }
            }
        );

        setScannerStatus(true);

    } catch(err) {
        console.error(err);
        alert('Kamera gagal dibuka');
    }
});

</script>