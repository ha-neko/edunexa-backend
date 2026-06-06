<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-white font-weight-bold">Atur Sesi Kelas</h1>
            <p class="mb-0 text-muted">Kelola sesi/shift dan tentukan kelas yang menggunakan setiap sesi.</p>
        </div>
    </div>

    {{-- ═══ SESSION MANAGER ═══ --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Sesi / Shift</h6>
            <button class="btn btn-sm btn-primary" id="addShiftBtn" data-toggle="modal" data-target="#shiftModal">
                <i class="fas fa-plus mr-1"></i> Tambah Sesi
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm align-items-center">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Mulai</th>
                            <th>Toleransi</th>
                            <th>Selesai</th>
                            <th>Kelas Terdaftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="shiftTable"></tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ═══ ASSIGN SHIFT TO CLASS ═══ --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Atur Sesi per Kelas</h6>
        </div>
        <div class="card-body">
            <p class="text-muted small">Pilih sesi untuk setiap kelas. Satu sesi bisa dipakai banyak kelas.</p>
            <div class="table-responsive">
                <table class="table table-sm align-items-center">
                    <thead>
                        <tr>
                            <th style="width:40px;">No</th>
                            <th>Kelas</th>
                            <th>Jurusan</th>
                            <th style="width:250px;">Sesi</th>
                        </tr>
                    </thead>
                    <tbody id="assignTable"></tbody>
                </table>
            </div>
            <button class="btn btn-primary mt-3" id="saveAssignBtn">
                <i class="fas fa-save mr-2"></i> Simpan Semua
            </button>
            <span id="assignStatus" class="ml-3 small text-success"></span>
        </div>
    </div>

</div>

{{-- ═══ SHIFT MODAL ═══ --}}
<div class="modal fade" id="shiftModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title text-white font-weight-bold" id="shiftModalTitle">Tambah Sesi</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="shiftForm">
                    <input type="hidden" id="shiftId">
                    <div class="form-group">
                        <label>Nama Sesi</label>
                        <input type="text" class="form-control" id="shiftName" placeholder="Shift Pagi">
                    </div>
                    <div class="form-group">
                        <label>Jam Mulai</label>
                        <input type="time" class="form-control" id="shiftStart">
                    </div>
                    <div class="form-group">
                        <label>Batas Toleransi Terlambat</label>
                        <input type="time" class="form-control" id="shiftLate">
                    </div>
                    <div class="form-group">
                        <label>Jam Selesai</label>
                        <input type="time" class="form-control" id="shiftEnd">
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="saveShiftBtn">Simpan</button>
            </div>
        </div>
    </div>
</div>

<style>
.modal-content { background:#2d3748; color:#e2e8f0; }
.modal-header { border-bottom:1px solid #4a5568; }
.modal-footer { border-top:1px solid #4a5568; }
.form-control { background:#1a202c; border:1px solid #4a5568; color:#e2e8f0; }
.form-control:focus { background:#1a202c; color:#e2e8f0; }
.table td, .table th { vertical-align:middle; }
select.form-control option { background:#1a202c; color:#e2e8f0; }
</style>

<script>
const API_BASE = '{{ rtrim(config("app.url"), "/") }}/api/admin';
const token    = '{{ session("token") }}';
const axiosCfg = { headers: { Authorization: `Bearer ${token}`, Accept: 'application/json', 'Content-Type': 'application/json' } };

const WEEKDAYS = [1,2,3,4,5];
let shifts = [];
let classrooms = [];
let loaded = { shifts: false, classrooms: false };

// ── Shifts ──────────────────────────────────────────────────────────

async function loadShifts() {
    try {
        const res = await axios.get(`${API_BASE}/shifts`, axiosCfg);
        shifts = res.data.data ?? [];
        loaded.shifts = true;
        renderShifts();
        if (loaded.classrooms) renderAssignTable();
    } catch (e) {
        console.error('Gagal memuat sesi. Pastikan sudah login.', e);
        document.getElementById('shiftTable').innerHTML = '<tr><td colspan="6" class="text-muted">Gagal memuat data.</td></tr>';
    }
}

function renderShifts() {
    const tbody = document.getElementById('shiftTable');
    if (!shifts.length) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-muted text-center">Belum ada sesi. Tambah sesi baru.</td></tr>';
        return;
    }
    tbody.innerHTML = shifts.map(s => {
        const classCount = s.classroom_count ?? 0;
        return `<tr>
            <td class="text-white font-weight-bold">${e(s.name)}</td>
            <td>${s.start_time?.substring(0,5)}</td>
            <td>${s.late_tolerance?.substring(0,5)}</td>
            <td>${s.end_time?.substring(0,5)}</td>
            <td>${classCount} kelas</td>
            <td>
                <button class="btn btn-sm btn-warning mr-1" onclick="editShift('${s.id}')"><i class="fas fa-edit"></i></button>
                <button class="btn btn-sm btn-danger" onclick="deleteShift('${s.id}')"><i class="fas fa-trash"></i></button>
            </td>
        </tr>`;
    }).join('');
}

function e(str) { return str ?? ''; }

document.getElementById('addShiftBtn').addEventListener('click', () => {
    document.getElementById('shiftModalTitle').textContent = 'Tambah Sesi';
    document.getElementById('shiftForm').reset();
    document.getElementById('shiftId').value = '';
});

document.getElementById('saveShiftBtn').addEventListener('click', async () => {
    const id = document.getElementById('shiftId').value;
    const payload = {
        name: document.getElementById('shiftName').value,
        start_time: document.getElementById('shiftStart').value,
        late_tolerance: document.getElementById('shiftLate').value,
        end_time: document.getElementById('shiftEnd').value,
    };
    if (!payload.name || !payload.start_time || !payload.late_tolerance || !payload.end_time) {
        alert('Harap isi semua field.');
        return;
    }
    try {
        if (id) {
            await axios.put(`${API_BASE}/shifts/${id}`, payload, axiosCfg);
        } else {
            await axios.post(`${API_BASE}/shifts`, payload, axiosCfg);
        }
        $('#shiftModal').modal('hide');
        await loadShifts();
        if (loaded.classrooms) renderAssignTable();
    } catch (e) {
        alert(e.response?.data?.message ?? 'Gagal menyimpan sesi.');
    }
});

window.editShift = (id) => {
    const s = shifts.find(x => x.id === id);
    if (!s) return;
    document.getElementById('shiftModalTitle').textContent = 'Edit Sesi';
    document.getElementById('shiftId').value = id;
    document.getElementById('shiftName').value = s.name;
    document.getElementById('shiftStart').value = s.start_time?.substring(0,5);
    document.getElementById('shiftLate').value = s.late_tolerance?.substring(0,5);
    document.getElementById('shiftEnd').value = s.end_time?.substring(0,5);
    $('#shiftModal').modal('show');
};

window.deleteShift = async (id) => {
    if (!confirm('Yakin ingin menghapus sesi ini?')) return;
    try {
        await axios.delete(`${API_BASE}/shifts/${id}`, axiosCfg);
        await loadShifts();
        if (loaded.classrooms) renderAssignTable();
    } catch (e) {
        alert(e.response?.data?.message ?? 'Gagal menghapus.');
    }
};

// ── Classrooms & Assignment ─────────────────────────────────────────

async function loadClassrooms() {
    try {
        const res = await axios.get(`${API_BASE}/classrooms?per_page=100`, axiosCfg);
        classrooms = res.data.data ?? [];
        loaded.classrooms = true;
        if (loaded.shifts) renderAssignTable();
    } catch (e) {
        console.error('Gagal memuat kelas.', e);
    }
}

async function renderAssignTable() {
    const tbody = document.getElementById('assignTable');
    const rows = [];

    for (const c of classrooms) {
        let currentShiftId = '';
        try {
            const sRes = await axios.get(`${API_BASE}/classrooms/${c.id}/shift-schedule`, axiosCfg);
            const scheds = sRes.data.schedules ?? [];
            const first = scheds.find(s => WEEKDAYS.includes(s.day_of_week));
            currentShiftId = first?.shift?.id ?? '';
        } catch (e) {}

        const label = `${c.grade} ${c.major?.major_code ?? ''} ${c.group_number}`;
        const jurusan = c.major?.major_name ?? '-';
        const opts = shifts.map(s => `<option value="${s.id}" ${s.id === currentShiftId ? 'selected' : ''}>${e(s.name)} (${s.start_time?.substring(0,5)}-${s.end_time?.substring(0,5)})</option>`).join('');

        rows.push(`<tr>
            <td>${rows.length + 1}</td>
            <td class="text-white font-weight-bold">${label}</td>
            <td>${jurusan}</td>
            <td>
                <select class="form-control shift-select" data-classroom-id="${c.id}">
                    <option value="">— Tidak Ada —</option>
                    ${opts}
                </select>
            </td>
        </tr>`);
    }

    tbody.innerHTML = rows.join('');
}

document.getElementById('saveAssignBtn').addEventListener('click', async () => {
    const selects = document.querySelectorAll('.shift-select');
    const btn = document.getElementById('saveAssignBtn');
    const status = document.getElementById('assignStatus');
    btn.disabled = true;
    status.textContent = 'Menyimpan...';

    let success = 0;
    let failed = 0;

    for (const sel of selects) {
        const classroomId = sel.dataset.classroomId;
        const shiftId = sel.value;

        if (!shiftId) {
            // Hapus semua jadwal untuk kelas ini
            try {
                const sRes = await axios.get(`${API_BASE}/classrooms/${classroomId}/shift-schedule`, axiosCfg);
                for (const s of (sRes.data.schedules ?? [])) {
                    await axios.delete(`${API_BASE}/classrooms/${classroomId}/shift-schedule/${s.id}`, axiosCfg);
                }
                success++;
            } catch (e) {
                console.error('Gagal hapus jadwal kelas', classroomId, e.response?.data);
                failed++;
            }
            continue;
        }

        const schedules = WEEKDAYS.map(d => ({ day_of_week: d, shift_id: shiftId }));

        try {
            await axios.post(`${API_BASE}/classrooms/${classroomId}/shift-schedule`, { schedules }, axiosCfg);
            success++;
        } catch (e) {
            console.error('Gagal simpan kelas', classroomId, e.response?.data);
            failed++;
        }
    }

    btn.disabled = false;
    status.textContent = `${success} kelas berhasil disimpan${failed ? `, ${failed} gagal (cek console)` : ''}.`;
    setTimeout(() => status.textContent = '', 5000);

    // Refresh "Kelas Terdaftar" count
    await loadShifts();
});

// ── Init ────────────────────────────────────────────────────────────
loadShifts();
loadClassrooms();
</script>
