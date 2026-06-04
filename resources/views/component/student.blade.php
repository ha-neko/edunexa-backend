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

            <button class="btn-excel" id="downloadExcel">
                <i class="fas fa-file-excel"></i>
                Download Excel
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
    <div class="modal-dialog modal-sm">
        <div class="modal-content border-0 text-center">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-qrcode mr-2" style="color:#4f8ef7"></i>
                    QR Code Siswa
                </h5>
                <button type="button" class="close text-light" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p class="mb-2" id="qrStudentName" style="color:#fff; font-weight:600;"></p>
                <img id="qrImage" src="" alt="QR Code" style="width:100%; border-radius:12px;">
                <p class="mt-3 mb-0" style="color:#94a3b8; font-size:12px;" id="qrCode"></p>
            </div>

            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-outline-light btn-sm" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary btn-sm" id="regenerateQrBtn">
                    <i class="fas fa-sync mr-1"></i>Regenerate QR
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

async function showBarcode(id) {
    try {
        const res     = await axios.get(`${STUDENT_URL}/${id}`, axiosConfig);
        const student = res.data.data;
        const name    = student.user?.name ?? 'Siswa';
        const qr      = student.qr_code   ?? '';

        document.getElementById('qrStudentName').innerText = name;
        document.getElementById('qrCode').innerText        = qr;
        document.getElementById('qrImage').src             =
            `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(qr)}`;

        document.getElementById('regenerateQrBtn').onclick = () => regenerateQr(id);

        $('#barcodeModal').modal('show');

    } catch(e) {
        console.error(e);
        alert('Gagal memuat QR Code.');
    }
}

async function regenerateQr(id) {
    if (!confirm('Regenerate QR Code? QR lama tidak bisa digunakan lagi.')) return;
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
    }
}

/* ══════════════════════════════════════════
   DOWNLOAD EXCEL
══════════════════════════════════════════ */

document.getElementById('downloadExcel').addEventListener('click', () => {
    const rows = [['No','Nama','Email','NIS','Jurusan','Kelas','Wali Murid','Status']];
    allStudents.forEach((s, i) => {
        rows.push([
            i + 1,
            s.user?.name  ?? '-',
            s.user?.email ?? '-',
            s.nis         ?? '-',
            s.classroom?.major?.major_name ?? '-',
            `${s.classroom?.grade ?? ''} ${s.classroom?.group_number ?? ''}`.trim() || '-',
            s.guardian?.user?.name ?? '-',
            s.deleted_at ? 'Non Active' : 'Active'
        ]);
    });

    const csv  = rows.map(r => r.join(',')).join('\n');
    const blob = new Blob([csv], { type: 'text/csv' });
    const url  = URL.createObjectURL(blob);
    const a    = document.createElement('a');
    a.href     = url;
    a.download = 'data-siswa.csv';
    a.click();
    URL.revokeObjectURL(url);
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