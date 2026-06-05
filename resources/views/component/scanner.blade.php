<link href="{{ asset('assets/css/custom_scanner.css') }}" rel="stylesheet">
<script src="https://unpkg.com/html5-qrcode"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://unpkg.com/jsqr@1.4.0/dist/jsQR.js"></script>

<div class="container-fluid">

    <!-- PAGE TITLE -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h3 mb-1 text-gray-100 font-weight-bold">Scanner Absensi</h1>
                <p class="mb-0 text-muted">Scan QR Code siswa untuk melakukan absensi otomatis.</p>
            </div>
            <div class="text-right">
                <span id="scannerClock" class="d-block text-light font-weight-bold" style="font-size:18px;">-</span>
                <small class="text-muted" id="scannerDate"></small>
            </div>
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
                            <span>Mulai Scan Kamera</span>
                        </button>
                        <button class="scanner-btn upload-btn" id="uploadQrBtn">
                            <i class="fas fa-upload mr-2"></i>
                            Upload Foto QR
                        </button>
                        <input type="file" id="qrUploadInput" accept="image/*" style="display:none;">
                        <div id="photoSection" style="display:none;" class="w-100">
                            <button class="scanner-btn btn-info w-100 mb-1" id="capturePhotoBtn">
                                <i class="fas fa-camera-retro mr-2"></i> Ambil Foto Verifikasi
                            </button>
                            <input type="file" id="photoInput" accept="image/*" capture="environment" style="display:none;">
                            <div id="photoPreview" class="mt-2" style="display:none;">
                                <img id="photoPreviewImg" class="rounded shadow-sm" style="width:100%; max-height:150px; object-fit:cover;">
                                <small class="text-muted d-block mt-1">Foto terverifikasi</small>
                            </div>
                        </div>
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
.scanner-action { flex-direction:column; gap:12px; justify-content:center; }
.scanner-btn { border:none; outline:none; padding:12px 22px; border-radius:14px; font-weight:600; font-size:14px; display:flex; align-items:center; justify-content:center; transition:.25s ease; color:#fff; }
.start-btn { background:linear-gradient(135deg, #2563eb, #1d4ed8); }
.scanner-btn:hover    { transform:translateY(-2px); }
.scanner-btn:disabled { opacity:.7; cursor:not-allowed; }
.upload-btn { background:linear-gradient(135deg, #7c3aed, #6d28d9); }
.student-avatar { width:120px; height:120px; object-fit:cover; border-radius:50%; border:3px solid #2563eb; }
</style>

<script>

/* =====================================
   CONFIG
===================================== */
const API_URL     = '{{ rtrim(config("app.url"), "/") }}/api/attendance/scan';
const token       = '{{ session("token") }}';
const csrfToken   = '{{ csrf_token() }}';
const axiosConfig = {
    headers: {
        Authorization : `Bearer ${token}`,
        Accept        : 'application/json',
        'Content-Type': 'application/json'
    }
};

// ── Axios global CSRF config ──
axios.defaults.xsrfCookieName = 'XSRF-TOKEN';
axios.defaults.xsrfHeaderName = 'X-XSRF-TOKEN';
axios.defaults.withCredentials = true;

let scanner       = null;
let totalScan     = 0;
let scannerActive = false;
let lastScanTime  = 0;

/* =====================================
   ATTENDANCE API — load today's list
===================================== */
const scannedIds = new Set();
const API_TODAY  = '{{ rtrim(config("app.url"), "/") }}/api/attendance/today';

(async function loadToday() {
    try {
        const res = await axios.get(API_TODAY, axiosConfig);
        const list = res.data?.data ?? [];
        totalScan = 0;
        list.forEach(function(item, idx) {
            totalScan++;
            if (item.student_id) scannedIds.add(item.student_id);
            renderAttendanceRow(item, idx);
        });
        document.getElementById('attendanceCount').innerHTML = `${totalScan} Siswa Hadir`;
        console.log('[Scanner] loaded', totalScan, 'attendances from API');
    } catch (e) {
        console.warn('[Scanner] load today failed:', e.response?.status);
    }
})();

/* =====================================
   CLOCK
===================================== */
function updateClock() {
    const now = new Date();
    const timeOpts = { hour: '2-digit', minute: '2-digit', second: '2-digit' };
    const dateOpts = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const el = document.getElementById('scannerClock');
    const el2 = document.getElementById('scannerDate');
    if (el) el.textContent = now.toLocaleTimeString('id-ID', timeOpts);
    if (el2) el2.textContent = now.toLocaleDateString('id-ID', dateOpts);
}
setInterval(updateClock, 1000);
updateClock();

/* =====================================
   RENDER ATTENDANCE ROW
===================================== */
function renderAttendanceRow(item, idx) {
    const no       = idx + 1;
    const photoUrl = item.photo || `https://ui-avatars.com/api/?name=${encodeURIComponent(item.name || 'Siswa')}&background=2563eb&color=fff`;
    const $tbody   = document.getElementById('attendanceTable');

    // Check if student already has a row — update it instead of append
    const existing = $tbody.querySelector(`tr[data-student-id="${CSS.escape(item.student_id || '')}"]`);
    if (existing) {
        existing.querySelector('.att-scan-in').textContent    = item.scan_in || '-';
        existing.querySelector('.att-scan-out').innerHTML     = item.scan_out ? `Pulang ${item.scan_out}` : '';
        existing.querySelector('.att-status').textContent     = item.status || 'Hadir';
        return;
    }

    const scanOutHtml = item.scan_out ? `<span class="att-scan-out d-block text-muted" style="font-size:11px;">Pulang ${item.scan_out}</span>` : '';
    $tbody.innerHTML += `
        <tr data-student-id="${CSS.escape(item.student_id || '')}">
            <td>${no}</td>
            <td class="d-flex align-items-center">
                <img src="${photoUrl}"
                    width="45" height="45" class="rounded-circle mr-3" style="object-fit:cover;">
                <div>
                    <strong>${item.name || '-'}</strong><br>
                    <small>${item.nis || '-'}</small>
                </div>
            </td>
            <td>${item.classroom || '-'}</td>
            <td class="att-scan-in">${item.scan_in || '-'}</td>
            <td><span class="att-status badge badge-success px-3 py-2">${item.status || 'Hadir'}</span>${scanOutHtml}</td>
        </tr>`;
}

const startBtn     = document.getElementById('startScanner');
const cameraStatus = document.getElementById('cameraStatus');
const uploadQrBtn  = document.getElementById('uploadQrBtn');
const qrUploadInput = document.getElementById('qrUploadInput');

let verifyPhotoBase64 = null;

// ── Photo capture ──
const photoSection    = document.getElementById('photoSection');
const captureBtn      = document.getElementById('capturePhotoBtn');
const photoInput      = document.getElementById('photoInput');
const photoPreview    = document.getElementById('photoPreview');
const photoPreviewImg = document.getElementById('photoPreviewImg');

captureBtn.addEventListener('click', () => photoInput.click());

photoInput.addEventListener('change', (e) => {
    const file = e.target.files?.[0];
    if (! file) return;

    const reader = new FileReader();
    reader.onload = (ev) => {
        verifyPhotoBase64 = ev.target.result;
        photoPreviewImg.src = verifyPhotoBase64;
        photoPreview.style.display = 'block';
        captureBtn.innerHTML = '<i class="fas fa-redo mr-2"></i> Ulang Foto';
    };
    reader.readAsDataURL(file);
});

// ── QR Upload ──
uploadQrBtn.addEventListener('click', () => qrUploadInput.click());

qrUploadInput.addEventListener('change', async (e) => {
    const file = e.target.files?.[0];
    if (! file) return;

    const img = new Image();
    img.onload = function() {
        const canvas = document.createElement('canvas');
        canvas.width  = img.naturalWidth;
        canvas.height = img.naturalHeight;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(img, 0, 0);
        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        const code = jsQR(imageData.data, imageData.width, imageData.height);

        if (code) {
            processQrCode(code.data);
        } else {
            alert('Tidak dapat membaca QR dari foto. Pastikan foto jelas.');
        }
    };
    img.onerror = function() {
        alert('Gagal memuat gambar.');
    };
    img.src = URL.createObjectURL(file);
    qrUploadInput.value = '';
});

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

// ── Process QR ──
async function processQrCode(decodedText) {
    const now = Date.now();
    if (now - lastScanTime < 3000) return;
    lastScanTime = now;

    try {
        const payload = { qr_token: decodedText };

        if (verifyPhotoBase64) {
            payload.verify_photo = verifyPhotoBase64;
        }

        const response = await axios.post(API_URL, payload, axiosConfig);

        console.log("RESPONSE:", response.data);

        const r = response.data;

        document.getElementById('studentName').innerHTML     = r.student?.name ?? 'Tidak diketahui';
        document.getElementById('studentClass').innerHTML    = r.student?.classroom ?? '-';
        document.getElementById('studentNis').innerHTML      = r.student?.nis ?? '-';
        document.getElementById('studentStatus').innerHTML   = r.attendance?.status ?? 'Hadir';
        document.getElementById('studentJamMasuk').innerHTML = r.attendance?.scan_in ?? '-';
        document.getElementById('studentJamPulang').innerHTML = r.attendance?.scan_out ?? '-';
        document.getElementById('studentTanggal').innerHTML  = r.attendance?.attendance_date ?? '-';

        const photoUrl = r.student?.photo ?? '';
        document.getElementById('studentAvatar').src =
            photoUrl
                ? photoUrl
                : `https://ui-avatars.com/api/?name=${encodeURIComponent(r.student?.name ?? 'Siswa')}&background=2563eb&color=fff`;

        document.getElementById('successBox').style.display = 'flex';
        document.getElementById('successMessage').innerHTML = r.message ?? 'Absensi berhasil';

        photoSection.style.display = 'block';

        totalScan++;
        document.getElementById('attendanceCount').innerHTML = `${totalScan} Siswa Hadir`;

        const idx = totalScan - 1;
        renderAttendanceRow({
            student_id: r.student?.id ?? '',
            name:       r.student?.name ?? '-',
            nis:        r.student?.nis ?? '-',
            classroom:  r.student?.classroom ?? '-',
            photo:      r.student?.photo ?? '',
            scan_in:    r.attendance?.scan_in ?? '-',
            scan_out:   r.attendance?.scan_out ?? null,
            status:     r.attendance?.status ?? 'Hadir',
        }, idx);

        // ── Persist to session ──
        axios.post('/scanner/store-scan', {
            student:   r.student,
            attendance: r.attendance,
        }).catch(function(e) {
            console.warn('[Scanner] store-scan failed:', e.response?.status, e.response?.data);
        });

    } catch(apiError) {
        console.log("ERROR STATUS:", apiError.response?.status);
        console.log("ERROR MESSAGE:", apiError.response?.data);
        alert(apiError.response?.data?.message ?? 'Absensi gagal');
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
                processQrCode(decodedText);
            }
        );

        setScannerStatus(true);

    } catch(err) {
        console.error(err);
        alert('Kamera gagal dibuka');
    }
});

</script>