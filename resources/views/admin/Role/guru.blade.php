@extends('component.layouts.admin.main')
@section('title', 'Master Guru')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<link rel="stylesheet" href="{{ asset('assets/css/style-view/custom_siswa.css') }}">

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
                <h1 class="h3 mb-0 font-weight-bold">Master Guru</h1>
                <p class="mb-0">Management data guru dan monitoring pengajar EDUNEXA</p>
            </div>
        </div>

        <div class="header-actions">
            <button class="btn-add-student" data-toggle="modal" data-target="#createTeacherModal">
                <i class="fas fa-plus"></i>
                Tambah Guru
            </button>
        </div>

    </div>

    <!-- STATISTIC -->
    <div class="row">

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="stat-card s-total">
                <div class="stat-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                <small>Total Guru</small>
                <h2 id="totalTeacher">
                    <i class="fas fa-spinner fa-spin"></i>
                </h2>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="stat-card s-active">
                <div class="stat-icon"><i class="fas fa-user-check"></i></div>
                <small>Guru Active</small>
                <h2 id="activeTeacher">
                    <i class="fas fa-spinner fa-spin"></i>
                </h2>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="stat-card s-off">
                <div class="stat-icon"><i class="fas fa-user-times"></i></div>
                <small>Non Active</small>
                <h2 id="inactiveTeacher">
                    <i class="fas fa-spinner fa-spin"></i>
                </h2>
            </div>
        </div>

    </div>

    <!-- TABLE -->
    <div class="table-card card border-0">

        <div class="table-card-header">
            <h6>
                <i class="fas fa-list mr-2" style="color:#4f8ef7; font-size:13px;"></i>
                List Guru
            </h6>
            <div class="search-wrap">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="searchTeacher" placeholder="Cari nama atau NIP...">
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th style="width:46px">No</th>
                            <th>Nama</th>
                            <th>NIP</th>
                            <th>No HP</th>
                            <th>Status</th>
                            <th style="width:100px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="teacherTable">
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="fas fa-spinner fa-spin" style="color:#4f8ef7"></i>
                                    <p>Memuat data guru...</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

<!-- MODAL TAMBAH GURU -->
<div class="modal fade" id="createTeacherModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-chalkboard-teacher mr-2" style="color:#4f8ef7"></i>
                    Tambah Guru
                </h5>
                <button type="button" class="close text-light" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="teacherForm">
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label>Nama Guru</label>
                            <input type="text" class="form-control" id="teacher_name" placeholder="Masukkan nama guru">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Email</label>
                            <input type="email" class="form-control" id="teacher_email" placeholder="contoh@email.com">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Password</label>
                            <input type="password" class="form-control" id="teacher_password" placeholder="Min. 8 karakter">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>NIP</label>
                            <input type="text" class="form-control" id="teacher_nip" placeholder="Nomor Induk Pegawai">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>No Handphone</label>
                            <input type="text" class="form-control" id="teacher_phone" placeholder="08xxxxxxxxxx">
                        </div>

                    </div>
                </form>
            </div>

            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="saveTeacherBtn">
                    <i class="fas fa-save mr-2"></i>Simpan Guru
                </button>
            </div>

        </div>
    </div>
</div>

<!-- MODAL EDIT GURU -->
<div class="modal fade" id="editTeacherModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-edit mr-2" style="color:#4f8ef7"></i>
                    Edit Guru
                </h5>
                <button type="button" class="close text-light" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="editTeacherId">
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label>Nama Guru</label>
                        <input type="text" class="form-control" id="editTeacherName" placeholder="Nama guru">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Email</label>
                        <input type="email" class="form-control" id="editTeacherEmail" placeholder="contoh@email.com">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Password <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
                        <input type="password" class="form-control" id="editTeacherPassword" placeholder="Password baru">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>NIP</label>
                        <input type="text" class="form-control" id="editTeacherNip" placeholder="NIP">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label>No Handphone</label>
                        <input type="text" class="form-control" id="editTeacherPhone" placeholder="08xxxxxxxxxx">
                    </div>

                </div>
            </div>

            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="updateTeacherBtn">
                    <i class="fas fa-save mr-2"></i>Update Guru
                </button>
            </div>

        </div>
    </div>
</div>

<script>

const BASE_URL    = '{{ rtrim(config("app.url"), "/") }}/api/admin';
const TEACHER_URL = `${BASE_URL}/teachers`;
const token       = '{{ session("token") }}';
const axiosConfig = {
    headers: {
        Authorization: `Bearer ${token}`,
        Accept: 'application/json'
    }
};

const teacherTable    = document.getElementById('teacherTable');
const totalTeacher    = document.getElementById('totalTeacher');
const activeTeacher   = document.getElementById('activeTeacher');
const inactiveTeacher = document.getElementById('inactiveTeacher');
const searchTeacher   = document.getElementById('searchTeacher');

let allTeachers = [];

/* ══════════════════════════════════════════
   GET TEACHERS
══════════════════════════════════════════ */
async function getTeachers() {
    try {
        const res   = await axios.get(`${TEACHER_URL}?per_page=100`, axiosConfig);
        allTeachers = res.data.data;
        renderTeachers(allTeachers);
    } catch(e) {
        console.error(e);
        teacherTable.innerHTML = `
            <tr><td colspan="6">
                <div class="empty-state">
                    <i class="fas fa-exclamation-triangle" style="color:#ef4444"></i>
                    <p>Gagal memuat data guru. Periksa koneksi server.</p>
                </div>
            </td></tr>`;
    }
}

/* ══════════════════════════════════════════
   RENDER
══════════════════════════════════════════ */
function renderTeachers(teachers) {
    teacherTable.innerHTML = '';

    if (teachers.length === 0) {
        teacherTable.innerHTML = `
            <tr><td colspan="6">
                <div class="empty-state">
                    <i class="fas fa-users-slash"></i>
                    <p>Tidak ada data guru.</p>
                </div>
            </td></tr>`;
        totalTeacher.innerText    = 0;
        activeTeacher.innerText   = 0;
        inactiveTeacher.innerText = 0;
        return;
    }

    let active = 0, inactive = 0;

    teachers.forEach((teacher, index) => {
        const name   = teacher.user?.name  ?? '-';
        const email  = teacher.user?.email ?? '-';
        const nip    = teacher.nip         ?? '-';
        const phone  = teacher.phone       ?? '-';
        const status = teacher.deleted_at ? 'inactive' : 'active';

        if (status === 'active') active++; else inactive++;

        const avatarUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=2563eb&color=fff&size=64`;
        const statusCls = status === 'active' ? 'st-active' : 'st-inactive';

        teacherTable.innerHTML += `
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

            <td style="font-family:monospace; font-size:12.5px; color:#94a3b8;">${nip}</td>
            <td style="color:#94a3b8; font-size:13px;">${phone}</td>

            <td>
                <span class="status-badge ${statusCls}">
                    <span style="font-size:7px;">●</span>
                    ${status}
                </span>
            </td>

            <td>
                <button class="btn-action btn-edit" onclick="editTeacher('${teacher.id}')" title="Edit">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn-action btn-del" onclick="deleteTeacher('${teacher.id}')" title="Hapus">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>`;
    });

    totalTeacher.innerText    = teachers.length;
    activeTeacher.innerText   = active;
    inactiveTeacher.innerText = inactive;
}

/* ══════════════════════════════════════════
   CREATE
══════════════════════════════════════════ */
const saveBtn = document.getElementById('saveTeacherBtn');

saveBtn.addEventListener('click', async () => {
    saveBtn.disabled  = true;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';

    try {
        const payload = {
            name:     document.getElementById('teacher_name').value.trim(),
            email:    document.getElementById('teacher_email').value.trim(),
            password: document.getElementById('teacher_password').value,
            nip:      document.getElementById('teacher_nip').value.trim(),
            phone:    document.getElementById('teacher_phone').value.trim(),
        };

        const res = await axios.post(TEACHER_URL, payload, axiosConfig);
        alert(res.data.message ?? 'Guru berhasil ditambahkan.');
        $('#createTeacherModal').modal('hide');
        document.getElementById('teacherForm').reset();
        getTeachers();
    } catch(e) {
        if (e.response?.data?.errors) {
            alert(Object.values(e.response.data.errors).map(err => err[0]).join('\n'));
        } else {
            alert(e.response?.data?.message ?? 'Gagal menambahkan guru.');
        }
    } finally {
        saveBtn.disabled  = false;
        saveBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Simpan Guru';
    }
});

$('#createTeacherModal').on('show.bs.modal', function() {
    document.getElementById('teacherForm').reset();
});

/* ══════════════════════════════════════════
   EDIT
══════════════════════════════════════════ */
async function editTeacher(id) {
    try {
        const res     = await axios.get(`${TEACHER_URL}/${id}`, axiosConfig);
        const teacher = res.data.data;

        document.getElementById('editTeacherId').value       = id;
        document.getElementById('editTeacherName').value     = teacher.user?.name  ?? '';
        document.getElementById('editTeacherEmail').value    = teacher.user?.email ?? '';
        document.getElementById('editTeacherNip').value      = teacher.nip         ?? '';
        document.getElementById('editTeacherPhone').value    = teacher.phone       ?? '';
        document.getElementById('editTeacherPassword').value = '';

        $('#editTeacherModal').modal('show');
    } catch(e) {
        alert('Gagal memuat data guru.');
    }
}

/* ══════════════════════════════════════════
   UPDATE
══════════════════════════════════════════ */
document.getElementById('updateTeacherBtn').addEventListener('click', async () => {
    const id  = document.getElementById('editTeacherId').value;
    const btn = document.getElementById('updateTeacherBtn');
    btn.disabled  = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';

    const payload = {
        name:  document.getElementById('editTeacherName').value.trim(),
        email: document.getElementById('editTeacherEmail').value.trim(),
        nip:   document.getElementById('editTeacherNip').value.trim(),
        phone: document.getElementById('editTeacherPhone').value.trim(),
    };

    const password = document.getElementById('editTeacherPassword').value;
    if (password) payload.password = password;

    try {
        const res = await axios.put(`${TEACHER_URL}/${id}`, payload, axiosConfig);
        alert(res.data.message ?? 'Guru berhasil diupdate.');
        $('#editTeacherModal').modal('hide');
        getTeachers();
    } catch(e) {
        if (e.response?.data?.errors) {
            alert(Object.values(e.response.data.errors).map(err => err[0]).join('\n'));
        } else {
            alert(e.response?.data?.message ?? 'Gagal mengupdate guru.');
        }
    } finally {
        btn.disabled  = false;
        btn.innerHTML = '<i class="fas fa-save mr-2"></i>Update Guru';
    }
});

/* ══════════════════════════════════════════
   DELETE
══════════════════════════════════════════ */
async function deleteTeacher(id) {
    if (!confirm('Yakin ingin menghapus guru ini?')) return;
    try {
        const res = await axios.delete(`${TEACHER_URL}/${id}`, axiosConfig);
        alert(res.data.message ?? 'Guru berhasil dihapus.');
        getTeachers();
    } catch(e) {
        alert(e.response?.data?.message ?? 'Gagal menghapus guru.');
    }
}

/* ══════════════════════════════════════════
   SEARCH
══════════════════════════════════════════ */
searchTeacher.addEventListener('keyup', function() {
    const q = this.value.toLowerCase();
    renderTeachers(allTeachers.filter(t =>
        (t.user?.name?.toLowerCase() ?? '').includes(q) ||
        (t.nip?.toLowerCase()        ?? '').includes(q)
    ));
});

/* ══════════════════════════════════════════
   INIT
══════════════════════════════════════════ */
getTeachers();

</script>

@endsection