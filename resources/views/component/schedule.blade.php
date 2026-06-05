<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-white font-weight-bold">Atur Sesi Kelas</h1>
            <p class="mb-0 text-muted">Kelola shift/sesi dan jadwalkan ke kelas.</p>
        </div>
    </div>

    {{-- ── ROW: Shift Manager + Class Schedule ── --}}
    <div class="row">

        {{-- ═══ SHIFT / SESI MANAGER ═══ --}}
        <div class="col-lg-5 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header py-3 d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Sesi / Shift</h6>
                    <button class="btn btn-sm btn-primary" id="addShiftBtn" data-toggle="modal" data-target="#shiftModal">
                        <i class="fas fa-plus"></i>
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
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="shiftTable"></tbody>
                        </table>
                    </div>
                    <p class="text-muted small mb-0 mt-2" id="shiftCount">0 sesi</p>
                </div>
            </div>
        </div>

        {{-- ═══ CLASS SCHEDULE ASSIGNMENT ═══ --}}
        <div class="col-lg-7 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Jadwalkan Sesi ke Kelas</h6>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Pilih Kelas</label>
                        <select class="form-control" id="classroomSelect">
                            <option value="">— Pilih Kelas —</option>
                        </select>
                    </div>

                    <div id="scheduleGrid" style="display:none;">
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-sm align-items-center">
                                <thead>
                                    <tr>
                                        <th>Hari</th>
                                        <th>Sesi</th>
                                        <th style="width:80px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="scheduleTableBody"></tbody>
                            </table>
                        </div>
                        <p class="text-muted small" id="scheduleInfo">Pilih sesi untuk setiap hari, lalu simpan.</p>
                        <button class="btn btn-primary" id="saveScheduleBtn">
                            <i class="fas fa-save mr-2"></i> Simpan Jadwal
                        </button>
                    </div>
                </div>
            </div>
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
                        <input type="text" class="form-control" id="shiftName" placeholder="Shift Pagi" required>
                    </div>
                    <div class="form-group">
                        <label>Jam Mulai</label>
                        <input type="time" class="form-control" id="shiftStart" required>
                    </div>
                    <div class="form-group">
                        <label>Batas Toleransi Terlambat</label>
                        <input type="time" class="form-control" id="shiftLate" required>
                    </div>
                    <div class="form-group">
                        <label>Jam Selesai</label>
                        <input type="time" class="form-control" id="shiftEnd" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="saveShiftBtn">
                    <i class="fas fa-save mr-2"></i> Simpan
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ═══ ASSIGN SHIFT MODAL ═══ --}}
<div class="modal fade" id="assignModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title text-white font-weight-bold">Atur Sesi</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p id="assignDayText" class="text-muted"></p>
                <input type="hidden" id="assignDayOfWeek">
                <input type="hidden" id="assignScheduleId">
                <div class="form-group">
                    <label>Pilih Sesi</label>
                    <select class="form-control" id="assignShiftSelect">
                        <option value="">— Tidak Ada —</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="assignSaveBtn">Simpan</button>
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
.btn-outline-light:hover { color:#1a202c; }
</style>

<script>
const API_BASE = '{{ rtrim(config("app.url"), "/") }}/api/admin';
const token    = '{{ session("token") }}';
const axiosCfg = { headers: { Authorization: `Bearer ${token}`, Accept: 'application/json', 'Content-Type': 'application/json' } };

const DAYS = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

let shifts = [];
let classrooms = [];
let currentSchedules = [];

// ── Shift CRUD ──────────────────────────────────────────────────────

async function loadShifts() {
    try {
        const res = await axios.get(`${API_BASE}/shifts`, axiosCfg);
        shifts = res.data.data ?? [];
        renderShifts();
    } catch (e) {
        console.error(e);
    }
}

function renderShifts() {
    const tbody = document.getElementById('shiftTable');
    tbody.innerHTML = shifts.map((s, i) => `
        <tr>
            <td class="text-white font-weight-bold">${s.name}</td>
            <td>${s.start_time?.substring(0,5)}</td>
            <td>${s.late_tolerance?.substring(0,5)}</td>
            <td>${s.end_time?.substring(0,5)}</td>
            <td>
                <button class="btn btn-sm btn-warning mr-1" onclick="editShift('${s.id}')"><i class="fas fa-edit"></i></button>
                <button class="btn btn-sm btn-danger" onclick="deleteShift('${s.id}')"><i class="fas fa-trash"></i></button>
            </td>
        </tr>
    `).join('');
    document.getElementById('shiftCount').textContent = `${shifts.length} sesi`;
}

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

    try {
        if (id) {
            await axios.put(`${API_BASE}/shifts/${id}`, payload, axiosCfg);
        } else {
            await axios.post(`${API_BASE}/shifts`, payload, axiosCfg);
        }
        $('#shiftModal').modal('hide');
        loadShifts();
        populateShiftSelect();
    } catch (e) {
        alert(e.response?.data?.message ?? 'Gagal menyimpan sesi.');
    }
});

window.editShift = async (id) => {
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
        loadShifts();
        populateShiftSelect();
    } catch (e) {
        alert(e.response?.data?.message ?? 'Gagal menghapus.');
    }
};

// ── Classroom Schedule ───────────────────────────────────────────────

async function loadClassrooms() {
    try {
        const res = await axios.get(`${API_BASE}/classrooms?per_page=100`, axiosCfg);
            classrooms = res.data.data ?? [];
            const sel = document.getElementById('classroomSelect');
            sel.innerHTML = '<option value="">— Pilih Kelas —</option>' +
                classrooms.map(c => `<option value="${c.id}">${c.grade} ${c.major?.major_code} ${c.group_number} (${c.academic_year})</option>`).join('');
    } catch (e) {
        console.error(e);
    }
}

function populateShiftSelect() {
    const sel = document.getElementById('assignShiftSelect');
    sel.innerHTML = '<option value="">— Tidak Ada —</option>' +
        shifts.map(s => `<option value="${s.id}">${s.name} (${s.start_time?.substring(0,5)}-${s.end_time?.substring(0,5)})</option>`).join('');
}

document.getElementById('classroomSelect').addEventListener('change', async (e) => {
    const classroomId = e.target.value;
    const grid = document.getElementById('scheduleGrid');
    if (!classroomId) { grid.style.display = 'none'; return; }
    await loadSchedule(classroomId);
    grid.style.display = 'block';
});

async function loadSchedule(classroomId) {
    try {
        const res = await axios.get(`${API_BASE}/classrooms/${classroomId}/shift-schedule`, axiosCfg);
        currentSchedules = res.data.schedules ?? [];
        renderScheduleGrid(classroomId);
    } catch (e) {
        console.error(e);
        currentSchedules = [];
        renderScheduleGrid(classroomId);
    }
}

function renderScheduleGrid(classroomId) {
    const tbody = document.getElementById('scheduleTableBody');
    tbody.innerHTML = DAYS.map((dayName, dayIdx) => {
        const sched = currentSchedules.find(s => s.day_of_week === dayIdx);
        const shiftName = sched?.shift ? `${sched.shift.name}` : '<span class="text-muted">—</span>';
        return `
            <tr>
                <td class="text-white font-weight-bold">${dayName}</td>
                <td id="dayShift-${dayIdx}">${shiftName}</td>
                <td>
                    <button class="btn btn-sm btn-info" onclick="openAssign(${dayIdx}, '${classroomId}')">
                        <i class="fas fa-pen"></i>
                    </button>
                    ${sched ? `<button class="btn btn-sm btn-danger ml-1" onclick="removeSchedule('${sched.id}', '${classroomId}')"><i class="fas fa-times"></i></button>` : ''}
                </td>
            </tr>
        `;
    }).join('');
}

window.openAssign = (dayOfWeek, classroomId) => {
    const sched = currentSchedules.find(s => s.day_of_week === dayOfWeek);
    document.getElementById('assignDayText').textContent = `Atur sesi untuk hari ${DAYS[dayOfWeek]}`;
    document.getElementById('assignDayOfWeek').value = dayOfWeek;
    document.getElementById('assignScheduleId').value = sched?.id ?? '';
    document.getElementById('assignShiftSelect').value = sched?.shift?.id ?? '';
    $('#assignModal').modal('show');
};

document.getElementById('assignSaveBtn').addEventListener('click', async () => {
    const dayOfWeek = parseInt(document.getElementById('assignDayOfWeek').value);
    const shiftId = document.getElementById('assignShiftSelect').value;
    const classroomId = document.getElementById('classroomSelect').value;
    const scheduleId = document.getElementById('assignScheduleId').value;

    if (!shiftId) {
        // Remove if exists
        if (scheduleId) {
            try {
                await axios.delete(`${API_BASE}/classrooms/${classroomId}/shift-schedule/${scheduleId}`, axiosCfg);
            } catch (e) { console.error(e); }
        }
    } else {
        // Remove old schedule for this day and re-save all
        const updated = currentSchedules.filter(s => s.day_of_week !== dayOfWeek);
        updated.push({ day_of_week: dayOfWeek, shift_id: shiftId });
        try {
            await axios.post(`${API_BASE}/classrooms/${classroomId}/shift-schedule`, { schedules: updated.map(s => ({ day_of_week: s.day_of_week, shift_id: s.shift_id ?? s.shift?.id })) }, axiosCfg);
        } catch (e) {
            alert(e.response?.data?.message ?? 'Gagal menyimpan.');
            $('#assignModal').modal('hide');
            return;
        }
    }

    $('#assignModal').modal('hide');
    await loadSchedule(classroomId);
});

window.removeSchedule = async (scheduleId, classroomId) => {
    if (!confirm('Hapus jadwal hari ini?')) return;
    try {
        await axios.delete(`${API_BASE}/classrooms/${classroomId}/shift-schedule/${scheduleId}`, axiosCfg);
        await loadSchedule(classroomId);
    } catch (e) {
        alert('Gagal menghapus.');
    }
};

// ── Bulk Save ────────────────────────────────────────────────────────
document.getElementById('saveScheduleBtn').addEventListener('click', async () => {
    const classroomId = document.getElementById('classroomSelect').value;
    if (!classroomId) return;

    const schedules = [];
    for (let d = 0; d < 7; d++) {
        const sched = currentSchedules.find(s => s.day_of_week === d);
        if (sched?.shift?.id) {
            schedules.push({ day_of_week: d, shift_id: sched.shift.id });
        }
    }

    try {
        await axios.post(`${API_BASE}/classrooms/${classroomId}/shift-schedule`, { schedules }, axiosCfg);
        alert('Jadwal berhasil disimpan!');
        await loadSchedule(classroomId);
    } catch (e) {
        alert(e.response?.data?.message ?? 'Gagal menyimpan.');
    }
});

// ── Init ─────────────────────────────────────────────────────────────
loadShifts();
loadClassrooms();
setTimeout(populateShiftSelect, 500);
</script>
