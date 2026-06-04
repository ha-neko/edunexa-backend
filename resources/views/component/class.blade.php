
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>


{{-- CSS terpisah --}}
<link rel="stylesheet" href="{{ asset('assets/css/style-view/custom_kelas.css') }}">

<div class="container-fluid">

    <!-- HEADER -->
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
                <h1 class="h3 mb-0 font-weight-bold">Master Kelas</h1>
                <p class="mb-0">Management data kelas per jurusan dan tahun ajaran</p>
            </div>

        </div>

        <button class="btn-add-kelas" data-toggle="modal" data-target="#modalClass">
            <i class="fas fa-plus"></i>
            Tambah Kelas
        </button>

    </div>

    <!-- TABLE -->
    <div class="table-card-kelas card border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark">
                    <thead>
                        <tr>
                            <th style="width:46px">No</th>
                            <th>Jurusan</th>
                            <th>Grade</th>
                            <th>Group</th>
                            <th>Tahun Ajaran</th>
                            <th style="width:70px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tableClass">
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="fas fa-spinner fa-spin" style="color:#4f8ef7"></i>
                                    <p>Memuat data kelas...</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- MODAL -->
<div class="modal fade" id="modalClass">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5>
                    <i class="fas fa-door-open mr-2" style="color:#4f8ef7"></i>
                    Tambah Kelas
                </h5>
            </div>

            <div class="modal-body">

                <div class="modal-alert" id="modalAlert"></div>

                <div class="field">
                    <label>Jurusan</label>
                    <select id="major_id" class="form-control">
                        <option value="">Memuat jurusan...</option>
                    </select>
                </div>

                <div class="field">
                    <label>Grade</label>
                    <select id="grade" class="form-control">
                        <option value="">Pilih Grade</option>
                        <option value="X">X</option>
                        <option value="XI">XI</option>
                        <option value="XII">XII</option>
                    </select>
                </div>

                <div class="field">
                    <label>Nomor Group</label>
                    <input id="group_number" class="form-control" placeholder="Contoh: 1">
                </div>

                <div class="field">
                    <label>Tahun Ajaran</label>
                    <input id="academic_year" class="form-control" placeholder="Contoh: 2025/2026">
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button class="btn btn-primary" onclick="save()">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
            </div>

        </div>
    </div>
</div>

<script>

const API       = '{{ rtrim(config("app.url"), "/") }}/api/admin/classrooms';
const MAJOR_API = '{{ rtrim(config("app.url"), "/") }}/api/admin/majors';
const token     = '{{ session("token") }}';

const headers = { Authorization: `Bearer ${token}`, Accept: "application/json" };

/* ── LOAD MAJORS ── */
async function loadMajor() {
    try {
        const res    = await axios.get(MAJOR_API, { headers });
        const majors = res.data.data;

        document.getElementById("major_id").innerHTML =
            `<option value="">-- Pilih Jurusan --</option>` +
            majors.map(m => `<option value="${m.id}">${m.major_name ?? m.name}</option>`).join('');

    } catch (err) {
        console.log(err);
        document.getElementById("major_id").innerHTML = `<option value="">Gagal memuat jurusan</option>`;
    }
}

/* ── LOAD CLASS ── */
async function loadClass() {
    try {
        const res = await axios.get(API, { headers });
        data = res.data.data;
        render();
    } catch (err) {
        console.log(err);
        document.getElementById("tableClass").innerHTML = `
            <tr><td colspan="6">
                <div class="empty-state">
                    <i class="fas fa-exclamation-triangle" style="color:#ef4444"></i>
                    <p>Gagal memuat data kelas. Periksa koneksi server.</p>
                </div>
            </td></tr>`;
    }
}

/* ── RENDER ── */
function render() {

    const table = document.getElementById("tableClass");

    if (data.length === 0) {
        table.innerHTML = `
            <tr><td colspan="6">
                <div class="empty-state">
                    <i class="fas fa-folder-open"></i>
                    <p>Belum ada data kelas.</p>
                </div>
            </td></tr>`;
        return;
    }

    table.innerHTML = data.map((c, i) => `
        <tr>
            <td>${i + 1}</td>

            <td>
                <div class="major-cell-kelas">
                    <div class="major-icon-kelas">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <span class="major-name-kelas">${c.major?.major_name ?? c.major?.name ?? '-'}</span>
                </div>
            </td>

            <td>
                <span class="grade-badge">${c.grade}</span>
            </td>

            <td>
                <span class="group-badge">
                    <i class="fas fa-users" style="font-size:10px;"></i>
                    Grup ${c.group_number}
                </span>
            </td>

            <td>
                <span class="year-badge">
                    <i class="fas fa-calendar-alt"></i>
                    ${c.academic_year}
                </span>
            </td>

            <td>
                <button class="btn-action btn-del-kelas" onclick="hapus('${c.id}')" title="Hapus">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

/* ── SAVE ── */
async function save() {

    const alertEl = document.getElementById("modalAlert");
    alertEl.style.display = "none";

    const payload = {
        major_id:      document.getElementById("major_id").value,
        grade:         document.getElementById("grade").value,
        group_number:  document.getElementById("group_number").value,
        academic_year: document.getElementById("academic_year").value,
    };

    if (!payload.major_id || !payload.grade || !payload.group_number || !payload.academic_year) {
        alertEl.innerText = "Semua field wajib diisi.";
        alertEl.style.display = "block";
        return;
    }

    try {
        await axios.post(API, payload, { headers });
        $("#modalClass").modal("hide");
        loadClass();
    } catch (err) {
        console.log(err.response?.data);
        alertEl.innerText = err.response?.data?.message ?? "Gagal menyimpan data.";
        alertEl.style.display = "block";
    }
}

/* ── DELETE ── */
async function hapus(id) {
    if (!confirm("Hapus kelas ini?")) return;
    try {
        await axios.delete(`${API}/${id}`, { headers });
        loadClass();
    } catch (err) {
        alert(err.response?.data?.message ?? "Gagal menghapus kelas.");
    }
}

/* ── INIT ── */
loadMajor();
loadClass();

</script>