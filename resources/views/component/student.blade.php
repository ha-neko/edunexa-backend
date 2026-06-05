<!-- AXIOS -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<link rel="stylesheet" href="{{ asset('assets/css/style-view/custom_siswa.css') }}">
<script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<style>
.field-error {
    color: #ef4444;
    font-size: 11.5px;
    margin-top: 4px;
    display: none;
}
.field-error.show {
    display: block;
}
.form-control.is-invalid {
    border-color: #ef4444 !important;
    box-shadow: 0 0 0 2px rgba(239,68,68,.15) !important;
}
</style>

<div class="container-fluid">

    <!-- PAGE HEADER -->
    <div class="page-header mb-4">

        <div class="page-header-left">

            <div class="page-icon">
                <svg width="24" height="24" viewBox="0 0 26 26" fill="none">
                    <rect x="2"  y="2"  width="10" height="10" rx="2.5" fill="white" fill-opacity="0.9"/>
                    <rect x="14" y="2"  width="10" height="10" rx="2.5" fill="white" fill-opacity="0.55"/>
                    <rect x="2"  y="14" width="10" height="10" rx="2.5" fill="white" fill-opacity="0.55"/>
                    <rect x="14" y="14" width="10" height="10" rx="2.5" fill="white" fill-opacity="0.25"/>
                </svg>
            </div>

            <div class="page-title-text">
                <h1 class="h3 mb-0 font-weight-bold">Data Siswa</h1>
                <p class="mb-0">Monitoring data siswa, absensi, dan management user siswa EDUNEXA</p>
            </div>

        </div>

        <div class="header-actions">

           <button class="btn-excel" id="btnLaporan" data-toggle="modal" data-target="#reportModal">
                <i class="fas fa-file-pdf"></i>
                Laporan PDF
            </button>

            <button class="btn-add-student" data-toggle="modal" data-target="#createStudentModal">
                <i class="fas fa-plus"></i>
                Tambah Siswa
            </button>

        </div>

    </div>

    <!-- STATISTIC -->
    <div class="row">

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="stat-card s-total">
                <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
                <small>Total Siswa</small>
                <h2 id="totalStudents">0</h2>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="stat-card s-active">
                <div class="stat-icon"><i class="fas fa-user-check"></i></div>
                <small>Siswa Active</small>
                <h2 id="activeStudents">0</h2>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="stat-card s-off">
                <div class="stat-icon"><i class="fas fa-user-times"></i></div>
                <small>Non Active</small>
                <h2 id="inactiveStudents">0</h2>
            </div>
        </div>

    </div>

    <!-- TABLE -->
    <div class="table-card card border-0">

        <div class="table-card-header">
            <h6>
                <i class="fas fa-list mr-2" style="color:#4f8ef7; font-size:13px;"></i>
                List Siswa
            </h6>
            <div class="search-wrap">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="searchInput" placeholder="Cari nama atau NIS...">
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th style="width:46px">No</th>
                            <th>Nama</th>
                            <th>NIS</th>
                            <th>Jurusan</th>
                            <th>Kelas</th>
                            <th>Wali Murid</th>
                            <th>Status</th>
                            <th style="width:130px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="studentTable">
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="fas fa-spinner fa-spin" style="color:#4f8ef7"></i>
                                    <p>Memuat data siswa...</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

<!-- ══════════════════════════════════════════
     MODAL TAMBAH SISWA
     Jurusan dihapus — cukup pilih Kelas saja
     karena Kelas sudah include Jurusan-nya.
══════════════════════════════════════════ -->
<div class="modal fade" id="createStudentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus mr-2" style="color:#4f8ef7"></i>
                    Tambah Siswa
                </h5>
                <button type="button" class="close text-light" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="studentForm">
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label>Nama Lengkap</label>
                            <input type="text" class="form-control" id="name" placeholder="Masukkan nama siswa">
                            <small class="field-error" id="err-name"></small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Email</label>
                            <input type="email" class="form-control" id="email" placeholder="contoh@email.com">
                            <small class="field-error" id="err-email"></small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>NIS</label>
                            <input type="text" class="form-control" id="nis" placeholder="Nomor Induk Siswa">
                            <small class="field-error" id="err-nis"></small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Password</label>
                            <input type="password" class="form-control" id="password" placeholder="Min. 8 karakter">
                            <small class="field-error" id="err-password"></small>
                        </div>

                        {{--
                            Dropdown Kelas langsung — tanpa filter Jurusan.
                            Jurusan sudah include di dalam Kelas (classroom → major).
                            Admin cukup pilih Kelas saja.
                        --}}
                        <div class="col-md-12 mb-3">
                            <label>Kelas</label>
                            <select class="form-control" id="classroom_id">
                                <option value="">-- Pilih Kelas --</option>
                            </select>
                            <small class="field-error" id="err-classroom_id"></small>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Wali Murid <span class="text-muted" style="font-size:12px;">(Opsional)</span></label>
                            <select class="form-control" id="guardian_id">
                                <option value="">-- Tidak Ada --</option>
                            </select>
                            <small class="text-muted">Bisa ditambahkan nanti.</small>
                        </div>

                    </div>
                </form>
            </div>

            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="saveStudentBtn">
                    <i class="fas fa-save mr-2"></i>Simpan Siswa
                </button>
            </div>

        </div>
    </div>
</div>
<!-- MODAL EDIT SISWA -->
<div class="modal fade" id="editStudentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-edit mr-2" style="color:#4f8ef7"></i>
                    Edit Siswa
                </h5>
                <button type="button" class="close text-light" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="editStudentId">
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label>Nama Lengkap</label>
                        <input type="text" class="form-control" id="editName" placeholder="Nama siswa">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Email</label>
                        <input type="email" class="form-control" id="editEmail" placeholder="contoh@email.com">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>NIS</label>
                        <input type="text" class="form-control" id="editNis" placeholder="Nomor Induk Siswa">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Password <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
                        <input type="password" class="form-control" id="editPassword" placeholder="Password baru">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Kelas</label>
                        <select class="form-control" id="editClassroom">
                            <option value="">-- Pilih Kelas --</option>
                        </select>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Wali Murid</label>
                        <select class="form-control" id="editGuardian">
                            <option value="">-- Tidak Ada --</option>
                        </select>
                    </div>

                </div>
            </div>

            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="updateStudentBtn">
                    <i class="fas fa-save mr-2"></i>Update Siswa
                </button>
            </div>

        </div>
    </div>
</div>

<!-- MODAL QR CODE -->
<div class="modal fade" id="barcodeModal" tabindex="-1">
    <div class="modal-dialog" style="max-width:480px;">
        <div class="modal-content border-0" style="border-radius:16px; overflow:hidden;">

            <!-- HEADER -->
            <div style="background:#2563eb; padding:1.25rem 1.5rem; display:flex; align-items:center; justify-content:space-between;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <i class="fas fa-qrcode" style="color:#fff; font-size:18px;"></i>
                    <span style="font-size:15px; font-weight:500; color:#fff;">QR Code Siswa</span>
                </div>
                <button type="button" class="close" data-dismiss="modal" style="color:rgba(255,255,255,0.7); opacity:1;">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body" style="padding:1.5rem; background:#0f172a;">

                <!-- AVATAR + INFO -->
                <div style="display:flex; align-items:center; gap:1rem; margin-bottom:1.25rem;">
                    <div id="qrAvatar" style="width:52px; height:52px; border-radius:50%; background:#1e3a8a; display:flex; align-items:center; justify-content:center; font-size:18px; font-weight:500; color:#93c5fd; flex-shrink:0;"></div>
                    <div style="text-align:left;">
                        <p id="qrStudentName" style="font-size:15px; font-weight:500; color:#f1f5f9; margin:0 0 2px;"></p>
                        <p id="qrStudentNis"  style="font-size:13px; color:#64748b; margin:0;"></p>
                    </div>
                </div>

                <!-- QR + TOKEN side by side di layar lebar, stack di mobile -->
                <div style="display:flex; flex-wrap:wrap; gap:1rem; margin-bottom:1.25rem; align-items:flex-start;">

                    <!-- QR IMAGE -->
                    <div style="flex:0 0 auto; background:#fff; border:0.5px solid #e2e8f0; border-radius:12px; padding:0.75rem; display:inline-flex;">
                        <img id="qrImage" src="" alt="QR Code" style="display:block; width:160px; height:160px;">
                    </div>

                    <!-- TOKEN + KETERANGAN -->
                    <div style="flex:1; min-width:160px; display:flex; flex-direction:column; gap:10px;">

                        <div style="background:#1e293b; border-radius:8px; padding:10px 12px;">
                            <p style="font-size:11px; color:#64748b; margin:0 0 6px;">Token QR</p>
                            <span id="qrTokenDisplay" style="font-size:11px; font-family:monospace; color:#94a3b8; word-break:break-all; display:block;"></span>
                        </div>

                        <button onclick="copyToken()" style="width:100%; display:flex; align-items:center; justify-content:center; gap:6px; padding:8px; border-radius:8px; border:1px solid #334155; background:transparent; color:#cbd5e1; font-size:13px; cursor:pointer;">
                            <i class="fas fa-copy" style="font-size:12px;"></i>
                            Salin Token
                        </button>

                        <div style="background:#1e293b; border-radius:8px; padding:10px 12px;">
                            <p style="font-size:11px; color:#64748b; margin:0 0 4px;">Info</p>
                            <p style="font-size:11px; color:#94a3b8; margin:0;">QR dapat digunakan kapan saja selama tidak di-regenerate.</p>
                        </div>

                    </div>

                </div>

                <!-- ACTIONS -->
                <div style="display:flex; flex-wrap:wrap; gap:8px;">

                    <button onclick="downloadQr()" style="flex:1; min-width:100px; display:flex; align-items:center; justify-content:center; gap:6px; padding:10px; border-radius:10px; border:1px solid #334155; background:transparent; color:#cbd5e1; font-size:13px; cursor:pointer;">
                        <i class="fas fa-download" style="font-size:13px;"></i>
                        Download
                    </button>

                    <button onclick="printQr()" style="flex:1; min-width:100px; display:flex; align-items:center; justify-content:center; gap:6px; padding:10px; border-radius:10px; border:1px solid #334155; background:transparent; color:#cbd5e1; font-size:13px; cursor:pointer;">
                        <i class="fas fa-print" style="font-size:13px;"></i>
                        Print
                    </button>

                    <button id="regenerateQrBtn" style="flex:1; min-width:100px; display:flex; align-items:center; justify-content:center; gap:6px; padding:10px; border-radius:10px; border:1px solid #ef4444; background:transparent; color:#ef4444; font-size:13px; cursor:pointer;">
                        <i class="fas fa-sync" style="font-size:13px;"></i>
                        Regenerate
                    </button>

                </div>

            </div>

        </div>
    </div>
</div>
<!-- MODAL REPORT PDF -->
<div class="modal fade" id="reportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-file-pdf mr-2" style="color:#ef4444;"></i>
                    Laporan Absensi Siswa
                </h5>
                <button type="button" class="close text-light" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label>Kelas <small class="text-muted">(Opsional)</small></label>
                    <select class="form-control" id="reportClassroom">
                        <option value="">-- Semua Kelas --</option>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Tanggal Dari</label>
                        <input type="date" class="form-control" id="reportDateFrom">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Tanggal Sampai</label>
                        <input type="date" class="form-control" id="reportDateTo">
                    </div>
                </div>

                <div class="mb-3">
                    <label>Status <small class="text-muted">(Opsional)</small></label>
                    <select class="form-control" id="reportStatus">
                        <option value="">-- Semua Status --</option>
                        <option value="hadir">Hadir</option>
                        <option value="izin">Izin</option>
                        <option value="sakit">Sakit</option>
                        <option value="alpha">Alpha</option>
                    </select>
                </div>

                <!-- SUMMARY PREVIEW -->
                <div id="reportSummary" style="display:none; background:#1e293b; border-radius:8px; padding:1rem; margin-top:1rem;">
                    <p style="color:#94a3b8; font-size:12px; margin:0 0 10px;">
                        <i class="fas fa-chart-bar mr-1"></i> Summary Periode
                    </p>
                    <div style="display:flex; gap:8px; flex-wrap:wrap;">
                        <span class="badge badge-success px-2 py-1" style="color:#fff !important;">
                            Hadir: <strong id="sumHadir">0</strong>
                        </span>
                        <span class="badge badge-info px-2 py-1" style="color:#fff !important;">
                            Izin: <strong id="sumIzin">0</strong>
                        </span>
                        <span class="badge badge-warning px-2 py-1" style="color:#fff !important;">
                            Sakit: <strong id="sumSakit">0</strong>
                        </span>
                        <span class="badge badge-danger px-2 py-1" style="color:#fff !important;">
                            Alpha: <strong id="sumAlpha">0</strong>
                        </span>
                        <span class="badge badge-primary px-2 py-1" style="color:#fff !important;">
                            Total: <strong id="sumTotal">0</strong>
                        </span>
                    </div>
                    <p style="color:#64748b; font-size:11px; margin:8px 0 0;">
                        <i class="fas fa-info-circle mr-1"></i>
                        <span id="reportPeriodInfo"></span>
                    </p>
                </div>

            </div>

            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-secondary" id="previewReportBtn">
                    <i class="fas fa-eye mr-2"></i>Preview
                </button>
                <button type="button" class="btn btn-danger" id="downloadPdfBtn">
                    <i class="fas fa-file-pdf mr-2"></i>Download PDF
                </button>
            </div>

        </div>
    </div>
</div>

<script>

const BASE_URL      = '{{ rtrim(config("app.url"), "/") }}/api/admin';
const STUDENT_URL   = `${BASE_URL}/students`;
const CLASSROOM_URL = `${BASE_URL}/classrooms`;
const GUARDIAN_URL  = `${BASE_URL}/guardians`;

const token = '{{ session("token") }}';

const studentTable     = document.getElementById('studentTable');
const totalStudents    = document.getElementById('totalStudents');
const activeStudents   = document.getElementById('activeStudents');
const inactiveStudents = document.getElementById('inactiveStudents');
const searchInput      = document.getElementById('searchInput');
const classroomSelect  = document.getElementById('classroom_id');
const guardianSelect   = document.getElementById('guardian_id');

let allStudents = [];

const axiosConfig = {
    headers: {
        Authorization: `Bearer ${token}`,
        Accept: 'application/json'
    }
};

/* ══════════════════════════════════════════
   HELPER — INLINE VALIDATION
══════════════════════════════════════════ */

function showFieldError(fieldId, message) {
    const input = document.getElementById(fieldId);
    const errEl = document.getElementById(`err-${fieldId}`);
    if (input) input.classList.add('is-invalid');
    if (errEl) { errEl.textContent = message; errEl.classList.add('show'); }
}

function clearFieldError(fieldId) {
    const input = document.getElementById(fieldId);
    const errEl = document.getElementById(`err-${fieldId}`);
    if (input) input.classList.remove('is-invalid');
    if (errEl) { errEl.textContent = ''; errEl.classList.remove('show'); }
}

function clearAllErrors() {
    ['name', 'email', 'nis', 'password', 'classroom_id'].forEach(clearFieldError);
}

// Client-side validation — return true jika semua lolos
function validateStudentForm() {
    clearAllErrors();
    let valid = true;

    const name      = document.getElementById('name').value.trim();
    const email     = document.getElementById('email').value.trim();
    const nis       = document.getElementById('nis').value.trim();
    const password  = document.getElementById('password').value;
    const classroom = document.getElementById('classroom_id').value;

    if (!name) {
        showFieldError('name', 'Nama lengkap wajib diisi.');
        valid = false;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email) {
        showFieldError('email', 'Email wajib diisi.');
        valid = false;
    } else if (!emailRegex.test(email)) {
        showFieldError('email', 'Format email tidak valid.');
        valid = false;
    }

    if (!nis) {
        showFieldError('nis', 'NIS wajib diisi.');
        valid = false;
    } else if (nis.length > 20) {
        showFieldError('nis', 'NIS maksimal 20 karakter.');
        valid = false;
    }

    if (!password) {
        showFieldError('password', 'Password wajib diisi.');
        valid = false;
    } else if (password.length < 8) {
        showFieldError('password', 'Password minimal 8 karakter.');
        valid = false;
    }

    if (!classroom) {
        showFieldError('classroom_id', 'Kelas wajib dipilih.');
        valid = false;
    }

    return valid;
}

/* ══════════════════════════════════════════
   LOAD DROPDOWNS
══════════════════════════════════════════ */

// Load semua kelas langsung — label: "Jurusan · Grade Kelompok"
// supaya admin tahu jurusan dari nama kelas tanpa perlu dropdown jurusan terpisah.
async function loadClassrooms() {
    try {
        const res = await axios.get(`${CLASSROOM_URL}?per_page=100`, axiosConfig);
        classroomSelect.innerHTML = '<option value="">-- Pilih Kelas --</option>';
        res.data.data.forEach(item => {
            const majorName = item.major?.major_name ?? '';
            const label     = majorName
                ? `${majorName} · ${item.grade} ${item.group_number}`
                : `${item.grade} ${item.group_number}`;
            classroomSelect.innerHTML += `<option value="${item.id}">${label}</option>`;
        });
    } catch(e) {
        console.error('Gagal memuat kelas:', e);
    }
}

async function loadGuardians() {
    try {
        const res = await axios.get(`${GUARDIAN_URL}?per_page=100`, axiosConfig);
        guardianSelect.innerHTML = '<option value="">-- Tidak Ada --</option>';
        res.data.data.forEach(g => {
            guardianSelect.innerHTML += `<option value="${g.id}">${g.user?.name ?? '-'}</option>`;
        });
    } catch(e) {
        console.error('Gagal memuat wali murid:', e);
    }
}

/* ══════════════════════════════════════════
   GET & RENDER STUDENTS
══════════════════════════════════════════ */

async function getStudents() {
    try {
        const res = await axios.get(`${STUDENT_URL}?per_page=100`, axiosConfig);
        allStudents = res.data.data;
        renderStudents(allStudents);
    } catch(e) {
        console.error(e);
        studentTable.innerHTML = `
            <tr><td colspan="8">
                <div class="empty-state">
                    <i class="fas fa-exclamation-triangle" style="color:#ef4444"></i>
                    <p>Gagal memuat data siswa. Periksa koneksi server.</p>
                </div>
            </td></tr>`;
    }
}

function renderStudents(students) {

    studentTable.innerHTML = '';

    if (students.length === 0) {
        studentTable.innerHTML = `
            <tr><td colspan="8">
                <div class="empty-state">
                    <i class="fas fa-users-slash"></i>
                    <p>Tidak ada data yang sesuai.</p>
                </div>
            </td></tr>`;
        totalStudents.innerText    = 0;
        activeStudents.innerText   = 0;
        inactiveStudents.innerText = 0;
        return;
    }

    let active = 0, inactive = 0;

    students.forEach((student, index) => {

        const name          = student.user?.name  ?? '-';
        const email         = student.user?.email ?? '-';
        const majorName     = student.classroom?.major?.major_name ?? '-';
        const classroomName = `${student.classroom?.grade ?? ''} ${student.classroom?.group_number ?? ''}`.trim();
        const guardianName  = student.guardian?.user?.name ?? '-';
        const status        = student.deleted_at ? 'inactive' : 'active';

        if (status === 'active') active++; else inactive++;

        const avatarUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=2563eb&color=fff&size=64`;
        const statusCls = status === 'active' ? 'st-active' : 'st-inactive';

        studentTable.innerHTML += `
        <tr>
            <td>${index + 1}</td>

            <td>
                <div class="student-cell">
                    <img src="${avatarUrl}" alt="${name}">
                    <div>
                        <div class="student-name">${name}</div>
                        <div class="student-email">${email}</div>
                    </div>
                </div>
            </td>

            <td style="font-family:monospace; font-size:12.5px; color:#94a3b8;">${student.nis ?? '-'}</td>
            <td style="color:#94a3b8; font-size:13px;">${majorName}</td>
            <td style="color:#94a3b8; font-size:13px;">${classroomName || '-'}</td>
            <td style="color:#94a3b8; font-size:13px;">${guardianName}</td>

            <td>
                <span class="status-badge ${statusCls}">
                    <span style="font-size:7px;">●</span>
                    ${status}
                </span>
            </td>

            <td>
                <button class="btn-action btn-view"    onclick="showStudent('${student.id}')"   title="Detail"><i class="fas fa-eye"></i></button>
                <button class="btn-action btn-barcode" onclick="showBarcode('${student.id}')"   title="Barcode/QR"><i class="fas fa-qrcode"></i></button>
                <button class="btn-action btn-edit"    onclick="editStudent('${student.id}')"   title="Edit"><i class="fas fa-edit"></i></button>
                <button class="btn-action btn-del"     onclick="deleteStudent('${student.id}')" title="Hapus"><i class="fas fa-trash"></i></button>
            </td>
        </tr>`;
    });

    totalStudents.innerText    = students.length;
    activeStudents.innerText   = active;
    inactiveStudents.innerText = inactive;
}

/* ══════════════════════════════════════════
   CREATE SISWA
══════════════════════════════════════════ */

const saveBtn = document.getElementById('saveStudentBtn');

saveBtn.addEventListener('click', async () => {

    // 1. Client-side validation dulu
    if (!validateStudentForm()) return;

    // 2. Loading state
    saveBtn.disabled  = true;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';

    try {
        const payload = {
            name:         document.getElementById('name').value.trim(),
            email:        document.getElementById('email').value.trim(),
            password:     document.getElementById('password').value,
            nis:          document.getElementById('nis').value.trim(),
            classroom_id: document.getElementById('classroom_id').value,
            guardian_id:  document.getElementById('guardian_id').value || null,
        };

        const res = await axios.post(STUDENT_URL, payload, axiosConfig);

        $('#createStudentModal').modal('hide');
        getStudents();
        alert(res.data.message ?? 'Siswa berhasil ditambahkan.');

    } catch (e) {
        const errors = e.response?.data?.errors;

        if (errors) {
            // Map error dari Laravel validation response ke field masing-masing
            const fieldMap = {
                name:         'name',
                email:        'email',
                nis:          'nis',
                password:     'password',
                classroom_id: 'classroom_id',
            };
            Object.entries(fieldMap).forEach(([apiKey, domId]) => {
                if (errors[apiKey]) showFieldError(domId, errors[apiKey][0]);
            });
        } else {
            alert(e.response?.data?.message ?? 'Gagal menambahkan siswa. Coba lagi.');
        }

    } finally {
        saveBtn.disabled  = false;
        saveBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Simpan Siswa';
    }
});

// Reset form & error saat modal dibuka ulang
$('#createStudentModal').on('show.bs.modal', function () {
    document.getElementById('studentForm').reset();
    clearAllErrors();
});

/* ══════════════════════════════════════════
   DELETE SISWA
══════════════════════════════════════════ */

async function deleteStudent(id) {
    if (!confirm('Yakin ingin menghapus siswa ini?')) return;
    try {
        const res = await axios.delete(`${STUDENT_URL}/${id}`, axiosConfig);
        alert(res.data.message ?? 'Berhasil dihapus.');
        getStudents();
    } catch(e) {
        alert(e.response?.data?.message ?? 'Gagal menghapus siswa.');
    }
}

/* ══════════════════════════════════════════
   DETAIL SISWA
══════════════════════════════════════════ */

async function showStudent(id) {
    try {
        const res     = await axios.get(`${STUDENT_URL}/${id}`, axiosConfig);
        const student = res.data.data;
        alert(
`Nama     : ${student.user?.name    ?? '-'}
Email    : ${student.user?.email   ?? '-'}
NIS      : ${student.nis            ?? '-'}
Jurusan  : ${student.classroom?.major?.major_name ?? '-'}
Kelas    : ${student.classroom?.grade ?? ''} ${student.classroom?.group_number ?? ''}
Wali     : ${student.guardian?.user?.name ?? '-'}`
        );
    } catch(e) {
        console.error(e);
    }
}

/* ══════════════════════════════════════════
   SEARCH
══════════════════════════════════════════ */

searchInput.addEventListener('keyup', function () {
    const q = this.value.toLowerCase();
    renderStudents(allStudents.filter(s =>
        (s.user?.name?.toLowerCase() ?? '').includes(q) ||
        (s.nis?.toLowerCase()        ?? '').includes(q)
    ));
});

/* ══════════════════════════════════════════
   EDIT SISWA
══════════════════════════════════════════ */

async function editStudent(id) {
    try {
        const res     = await axios.get(`${STUDENT_URL}/${id}`, axiosConfig);
        const student = res.data.data;

        document.getElementById('editStudentId').value = id;
        document.getElementById('editName').value      = student.user?.name  ?? '';
        document.getElementById('editEmail').value     = student.user?.email ?? '';
        document.getElementById('editNis').value       = student.nis         ?? '';
        document.getElementById('editPassword').value  = '';

        // Isi dropdown kelas
        const editClassroom = document.getElementById('editClassroom');
        const resClass      = await axios.get(`${CLASSROOM_URL}?per_page=100`, axiosConfig);

        editClassroom.innerHTML = '<option value="">-- Pilih Kelas --</option>';
        resClass.data.data.forEach(item => {
            const majorName = item.major?.major_name ?? '';
            const label     = majorName
                ? `${majorName} · ${item.grade} ${item.group_number}`
                : `${item.grade} ${item.group_number}`;
            const selected  = item.id == student.classroom?.id ? 'selected' : '';
            editClassroom.innerHTML += `<option value="${item.id}" ${selected}>${label}</option>`;
        });

        // Isi dropdown wali murid
        const editGuardian = document.getElementById('editGuardian');
        const resGuardian  = await axios.get(`${GUARDIAN_URL}?per_page=100`, axiosConfig);

        editGuardian.innerHTML = '<option value="">-- Tidak Ada --</option>';
        resGuardian.data.data.forEach(g => {
            const selected = g.id == student.guardian?.id ? 'selected' : '';
            editGuardian.innerHTML += `<option value="${g.id}" ${selected}>${g.user?.name ?? '-'}</option>`;
        });

        $('#editStudentModal').modal('show');

    } catch(e) {
        console.error(e);
        alert('Gagal memuat data siswa.');
    }
}

/* ══════════════════════════════════════════
   UPDATE SISWA
══════════════════════════════════════════ */

document.getElementById('updateStudentBtn').addEventListener('click', async () => {

    const id  = document.getElementById('editStudentId').value;
    const btn = document.getElementById('updateStudentBtn');

    btn.disabled  = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';

    const payload = {
        name:         document.getElementById('editName').value.trim(),
        email:        document.getElementById('editEmail').value.trim(),
        nis:          document.getElementById('editNis').value.trim(),
        classroom_id: document.getElementById('editClassroom').value,
        guardian_id:  document.getElementById('editGuardian').value || null,
    };

    // Hanya kirim password jika diisi
    const password = document.getElementById('editPassword').value;
    if (password) payload.password = password;

    try {
        const res = await axios.put(`${STUDENT_URL}/${id}`, payload, axiosConfig);
        alert(res.data.message ?? 'Siswa berhasil diupdate.');
        $('#editStudentModal').modal('hide');
        getStudents();
    } catch(e) {
        if (e.response?.data?.errors) {
            const msgs = Object.values(e.response.data.errors).map(err => err[0]).join('\n');
            alert(msgs);
        } else {
            alert(e.response?.data?.message ?? 'Gagal mengupdate siswa.');
        }
    } finally {
        btn.disabled  = false;
        btn.innerHTML = '<i class="fas fa-save mr-2"></i>Update Siswa';
    }
});

/* ══════════════════════════════════════════
   QR CODE
══════════════════════════════════════════ */

let currentQrToken = '';
let currentQrStudentId = '';

async function showBarcode(id) {
    try {
        const res     = await axios.get(`${STUDENT_URL}/${id}`, axiosConfig);
        const student = res.data.data;

        const name    = student.user?.name  ?? 'Siswa';
        const nis     = student.nis         ?? '-';
        const token   = student.qr_token    ?? '';

        currentQrToken     = token;
        currentQrStudentId = id;

        // Avatar inisial
        const initials = name.split(' ').map(w => w[0]).join('').substring(0, 2).toUpperCase();
        document.getElementById('qrAvatar').innerText      = initials;
        document.getElementById('qrStudentName').innerText = name;
        document.getElementById('qrStudentNis').innerText  = `NIS: ${nis}`;

        // Token display (potong)
        document.getElementById('qrTokenDisplay').innerText =
            token.length > 30 ? token.substring(0, 30) + '...' : token;

        // QR Image
        document.getElementById('qrImage').src =
            `https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=${encodeURIComponent(token)}`;

        // Regenerate button
        document.getElementById('regenerateQrBtn').onclick = () => regenerateQr(id);

        $('#barcodeModal').modal('show');

    } catch(e) {
        console.error(e);
        alert('Gagal memuat QR Code.');
    }
}

/* ── COPY TOKEN ── */
function copyToken() {
    navigator.clipboard.writeText(currentQrToken).then(() => {
        alert('Token berhasil disalin!');
    });
}

/* ── DOWNLOAD QR ── */
function downloadQr() {
    const name = document.getElementById('qrStudentName').innerText;
    const nis  = document.getElementById('qrStudentNis').innerText.replace('NIS: ', '');
    const url  = `https://api.qrserver.com/v1/create-qr-code/?size=400x400&data=${encodeURIComponent(currentQrToken)}`;

    const a    = document.createElement('a');
    a.href     = url;
    a.download = `QR_${name}_${nis}.png`;
    a.target   = '_blank';
    a.click();
}

/* ── PRINT QR ── */
function printQr() {
    const name  = document.getElementById('qrStudentName').innerText;
    const nis   = document.getElementById('qrStudentNis').innerText;
    const url   = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(currentQrToken)}`;

    const win   = window.open('', '_blank');
    win.document.write(`
        <html>
        <head>
            <title>QR Code - ${name}</title>
            <style>
                body { font-family: Arial, sans-serif; text-align: center; padding: 40px; }
                img  { display: block; margin: 20px auto; }
                h2   { margin: 0 0 8px; }
                p    { color: #64748b; margin: 0; }
            </style>
        </head>
        <body>
            <h2>${name}</h2>
            <p>${nis}</p>
            <img src="${url}" width="300" height="300">
            <p style="margin-top:16px; font-size:12px; font-family:monospace;">${currentQrToken}</p>
        </body>
        </html>
    `);
    win.document.close();
    win.print();
}

/* ── REGENERATE QR ── */
async function regenerateQr(id) {
    if (!confirm('Regenerate QR Code? QR lama tidak bisa digunakan lagi.')) return;

    const btn = document.getElementById('regenerateQrBtn');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    btn.disabled  = true;

    try {
        const res = await axios.post(
            `${BASE_URL}/students/${id}/regenerate-qr`,
            {},
            axiosConfig
        );
        alert(res.data.message ?? 'QR berhasil diperbarui.');
        $('#barcodeModal').modal('hide');
        getStudents();
    } catch(e) {
        alert(e.response?.data?.message ?? 'Gagal regenerate QR.');
    } finally {
        btn.innerHTML = '<i class="fas fa-sync"></i> Regenerate';
        btn.disabled  = false;
    }
}
/* ══════════════════════════════════════════
   REPORT PDF
══════════════════════════════════════════ */

const REPORT_URL = `${BASE_URL}/reports/attendance`;

// Set default tanggal (awal bulan - hari ini)
function setDefaultDates() {
    const today    = new Date();
    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
    const fmt      = d => d.toISOString().split('T')[0];
    document.getElementById('reportDateFrom').value = fmt(firstDay);
    document.getElementById('reportDateTo').value   = fmt(today);
}

// Load kelas ke dropdown report
async function loadReportClassrooms() {
    try {
        const res    = await axios.get(`${CLASSROOM_URL}?per_page=100`, axiosConfig);
        const select = document.getElementById('reportClassroom');
        select.innerHTML = '<option value="">-- Semua Kelas --</option>';
        res.data.data.forEach(item => {
            const majorName = item.major?.major_name ?? '';
            const label     = majorName
                ? `${majorName} · ${item.grade} ${item.group_number}`
                : `${item.grade} ${item.group_number}`;
            select.innerHTML += `<option value="${item.id}">${label}</option>`;
        });
    } catch(e) {
        console.error('Gagal memuat kelas report:', e);
    }
}

// Fetch data report dari API
async function fetchReport() {
    const params      = new URLSearchParams();
    const classroomId = document.getElementById('reportClassroom').value;
    const dateFrom    = document.getElementById('reportDateFrom').value;
    const dateTo      = document.getElementById('reportDateTo').value;
    const status      = document.getElementById('reportStatus').value;

    if (classroomId) params.append('classroom_id', classroomId);
    if (dateFrom)    params.append('date_from', dateFrom);
    if (dateTo)      params.append('date_to', dateTo);
    if (status)      params.append('status', status);
    params.append('per_page', 200);

    const res = await axios.get(`${REPORT_URL}?${params.toString()}`, axiosConfig);
    return res.data;
}

// Preview summary
document.getElementById('previewReportBtn').addEventListener('click', async () => {
    const btn = document.getElementById('previewReportBtn');
    btn.disabled  = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading...';

    try {
        const res     = await fetchReport();
        const summary = res.summary;
        const period  = res.period;

        document.getElementById('sumHadir').innerText = summary.hadir ?? 0;
        document.getElementById('sumIzin').innerText  = summary.izin  ?? 0;
        document.getElementById('sumSakit').innerText = summary.sakit ?? 0;
        document.getElementById('sumAlpha').innerText = summary.alpha ?? 0;
        document.getElementById('sumTotal').innerText = summary.total ?? 0;
        document.getElementById('reportPeriodInfo').innerText =
            `Periode: ${period.from} s/d ${period.to}`;

        document.getElementById('reportSummary').style.display = 'block';

    } catch(e) {
        alert(e.response?.data?.message ?? 'Gagal memuat data report.');
    } finally {
        btn.disabled  = false;
        btn.innerHTML = '<i class="fas fa-eye mr-2"></i>Preview';
    }
});

// Download PDF
document.getElementById('downloadPdfBtn').addEventListener('click', async () => {
    const btn = document.getElementById('downloadPdfBtn');
    btn.disabled  = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Generating...';

    try {
        const res          = await fetchReport();
        const attendances  = res.data.data ?? [];
        const summary      = res.summary;
        const period       = res.period;
        const classLabel   = document.getElementById('reportClassroom').selectedOptions[0]?.text ?? 'Semua Kelas';
        const statusLabel  = document.getElementById('reportStatus').value || 'Semua Status';
        const printDate    = new Date().toLocaleDateString('id-ID', { day:'2-digit', month:'long', year:'numeric' });

        const win = window.open('', '_blank');
        win.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Laporan Absensi - EDUNEXA</title>
                <style>
                    * { margin:0; padding:0; box-sizing:border-box; }
                    body { font-family: Arial, sans-serif; font-size:12px; color:#1e293b; padding:30px; }

                    .header { text-align:center; border-bottom:2.5px solid #1e3a5f; padding-bottom:12px; margin-bottom:16px; }
                    .header h1 { font-size:16px; font-weight:bold; text-transform:uppercase; color:#1e3a5f; letter-spacing:1px; }
                    .header h2 { font-size:13px; font-weight:bold; color:#2563eb; margin-top:4px; }
                    .header p  { font-size:11px; color:#64748b; margin-top:2px; }

                    .info-grid { display:flex; gap:12px; margin-bottom:16px; flex-wrap:wrap; }
                    .info-item { flex:1; min-width:140px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:6px; padding:8px 12px; }
                    .info-item label { font-size:10px; color:#64748b; display:block; margin-bottom:2px; }
                    .info-item span  { font-size:12px; font-weight:bold; color:#1e293b; }

                    .summary { display:flex; gap:10px; margin-bottom:16px; flex-wrap:wrap; }
                    .sum-card { flex:1; min-width:80px; text-align:center; padding:10px 8px; border-radius:8px; }
                    .sum-card h3 { font-size:20px; font-weight:bold; margin-bottom:2px; }
                    .sum-card p  { font-size:10px; }
                    .s-hadir { background:#f0fff4; color:#276749; }
                    .s-izin  { background:#fffaf0; color:#c05621; }
                    .s-sakit { background:#ebf8ff; color:#2b6cb0; }
                    .s-alpha { background:#fff5f5; color:#c53030; }
                    .s-total { background:#f3e8ff; color:#6b21a8; }

                    table { width:100%; border-collapse:collapse; font-size:11px; margin-bottom:16px; }
                    thead tr { background:#1e3a5f; color:#fff; }
                    thead th { padding:8px 10px; text-align:left; font-size:10.5px; }
                    tbody tr:nth-child(even) { background:#f8fafc; }
                    tbody td { padding:7px 10px; border-bottom:1px solid #e2e8f0; }

                    .badge { padding:3px 8px; border-radius:20px; font-size:10px; font-weight:bold; }
                    .b-hadir { background:#dcfce7; color:#166534; }
                    .b-izin  { background:#dbeafe; color:#1e40af; }
                    .b-sakit { background:#fef9c3; color:#854d0e; }
                    .b-alpha { background:#fee2e2; color:#991b1b; }
                    .b-default { background:#f1f5f9; color:#475569; }

                    .empty { text-align:center; padding:30px; color:#94a3b8; }

                    .footer { margin-top:20px; display:flex; justify-content:space-between; align-items:flex-end; }
                    .ttd { text-align:center; }
                    .ttd p { margin-bottom:50px; font-size:11px; }
                    .ttd-line { border-top:1px solid #1a1a1a; width:160px; margin:0 auto; }
                    .ttd-name { font-weight:bold; font-size:11px; margin-top:3px; }

                    .generated { font-size:9px; color:#a0aec0; text-align:right; margin-top:12px; }

                    @media print {
                        body { padding: 15px; }
                    }
                </style>
            </head>
            <body>

                <div class="header">
                    <h1>SMK Computer Science</h1>
                    <h2>Laporan Absensi Siswa</h2>
                    <p>Periode: ${period.from} s/d ${period.to}</p>
                </div>

                <div class="info-grid">
                    <div class="info-item">
                        <label>Kelas</label>
                        <span>${classLabel}</span>
                    </div>
                    <div class="info-item">
                        <label>Status Filter</label>
                        <span>${statusLabel}</span>
                    </div>
                    <div class="info-item">
                        <label>Total Data</label>
                        <span>${attendances.length} record</span>
                    </div>
                    <div class="info-item">
                        <label>Dicetak</label>
                        <span>${printDate}</span>
                    </div>
                </div>

                <div class="summary">
                    <div class="sum-card s-hadir">
                        <h3>${summary.hadir}</h3><p>Hadir</p>
                    </div>
                    <div class="sum-card s-izin">
                        <h3>${summary.izin}</h3><p>Izin</p>
                    </div>
                    <div class="sum-card s-sakit">
                        <h3>${summary.sakit}</h3><p>Sakit</p>
                    </div>
                    <div class="sum-card s-alpha">
                        <h3>${summary.alpha}</h3><p>Alpha</p>
                    </div>
                    <div class="sum-card s-total">
                        <h3>${summary.total}</h3><p>Total</p>
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th style="width:30px">No</th>
                            <th>Nama Siswa</th>
                            <th style="width:80px">NIS</th>
                            <th>Kelas</th>
                            <th>Jurusan</th>
                            <th style="width:80px">Tanggal</th>
                            <th style="width:70px">Jam Masuk</th>
                            <th style="width:70px">Jam Pulang</th>
                            <th style="width:60px">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${attendances.length === 0
                            ? `<tr><td colspan="9" class="empty">
                                <p>Tidak ada data absensi pada periode ini.</p>
                               </td></tr>`
                            : attendances.map((a, i) => `
                                <tr>
                                    <td style="text-align:center">${i + 1}</td>
                                    <td>${a.student?.user?.name ?? '-'}</td>
                                    <td style="font-family:monospace; font-size:10px;">${a.student?.nis ?? '-'}</td>
                                    <td>${a.student?.classroom?.grade ?? ''} ${a.student?.classroom?.group_number ?? ''}</td>
                                    <td>${a.student?.classroom?.major?.major_name ?? '-'}</td>
                                    <td>${a.attendance_date ?? '-'}</td>
                                    <td>${a.check_in ?? '-'}</td>
                                    <td>${a.check_out ?? '-'}</td>
                                    <td>
                                        <span class="badge b-${a.status ?? 'default'}">
                                            ${a.status ?? '-'}
                                        </span>
                                    </td>
                                </tr>
                            `).join('')
                        }
                    </tbody>
                </table>

                <div class="footer">
                    <div style="font-size:10px; color:#64748b;">
                        <b>Keterangan:</b><br>
                        H = Hadir &nbsp;|&nbsp; I = Izin &nbsp;|&nbsp; S = Sakit &nbsp;|&nbsp; A = Alpha
                    </div>
                    <div class="ttd">
                        <p>Mengetahui, Wali Kelas</p>
                        <div class="ttd-line"></div>
                        <div class="ttd-name">______________________</div>
                        <div style="font-size:10px; color:#64748b;">NIP. -</div>
                    </div>
                </div>

                <div class="generated">
                    Dicetak oleh sistem EDUNEXA &bull; ${new Date().toLocaleString('id-ID')} WIB
                </div>

                <script>
                    window.onload = function() { window.print(); }
                <\/script>

            </body>
            </html>
        `);
        win.document.close();

    } catch(e) {
        console.error(e);
        alert(e.response?.data?.message ?? 'Gagal generate PDF.');
    } finally {
        btn.disabled  = false;
        btn.innerHTML = '<i class="fas fa-file-pdf mr-2"></i>Download PDF';
    }
});

// Init modal report
$('#reportModal').on('show.bs.modal', function() {
    setDefaultDates();
    loadReportClassrooms();
    document.getElementById('reportSummary').style.display = 'none';
});

/* ══════════════════════════════════════════
   INIT
══════════════════════════════════════════ */

(async () => {
    await loadClassrooms();
    await loadGuardians();
    await getStudents();
})();

</script>