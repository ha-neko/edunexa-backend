
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

{{-- CSS terpisah --}}
<link rel="stylesheet" href="{{ asset('assets/css/style-view/custom_jurusan.css') }}">

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
                <h1 class="h3 mb-0 font-weight-bold">Daftar Jurusan</h1>
                <p class="mb-0">Kelola data jurusan secara realtime dari API</p>
            </div>

        </div>

        <button class="btn-add-major" onclick="openModal()">
            <i class="fas fa-plus"></i>
            Tambah Jurusan
        </button>

    </div>

    <!-- STAT -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stat-card-jurusan">
                <div class="stat-icon"><i class="fas fa-layer-group"></i></div>
                <small>Total Jurusan</small>
                <h2 id="totalMajors">0</h2>
            </div>
        </div>
    </div>

    <!-- SEARCH -->
    <div class="search-wrap-jurusan">
        <i class="fas fa-search search-icon"></i>
        <input type="text" id="search" placeholder="Cari nama atau kode jurusan...">
    </div>

    <!-- TABLE -->
    <div class="table-card-jurusan card border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jurusan</th>
                            <th>Kode</th>
                            <th>Kelas</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="table">
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="fas fa-spinner fa-spin" style="color:#4f8ef7"></i>
                                    <p>Memuat data jurusan...</p>
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
<div class="modal fade" id="majorModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5>
                    <i class="fas fa-layer-group mr-2" style="color:#4f8ef7"></i>
                    <span id="modalTitle">Tambah Jurusan</span>
                </h5>
            </div>

            <div class="modal-body">

                <div class="modal-alert" id="modalAlert"></div>

                <div class="mb-3">
                    <label>Nama Jurusan</label>
                    <input id="major_name" class="form-control" placeholder="Contoh: Teknik Komputer dan Jaringan">
                </div>

                <div>
                    <label>Kode Jurusan</label>
                    <input id="major_code" class="form-control" placeholder="Contoh: TKJ">
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button class="btn btn-primary" onclick="save()">
                    <i class="fas fa-save mr-2"></i>
                    Simpan
                </button>
            </div>

        </div>
    </div>
</div>

<script>

const API   = '{{ rtrim(config("app.url"), "/") }}/api/admin/majors';
const token = '{{ session("token") }}';

// Tambahkan ini setelah deklarasi token
const axiosConfig = {
    headers: {
        Authorization: `Bearer ${token}`,
        Accept: 'application/json'
    }
};

let data   = [];
let editId = null;

/* ── LOAD ── */
async function load() {
    try {
        const res = await axios.get(API, axiosConfig);
        data = res.data.data;
        render(data);
    } catch (err) {
        console.log(err);
        document.getElementById("table").innerHTML = `
            <tr><td colspan="6">
                <div class="empty-state">
                    <i class="fas fa-exclamation-triangle" style="color:#ef4444"></i>
                    <p>Gagal memuat data jurusan. Periksa koneksi server.</p>
                </div>
            </td></tr>`;
    }
}  

/* ── RENDER ── */
function render(list) {

    const table = document.getElementById("table");
    const total = document.getElementById("totalMajors");

    total.innerText = list.length;

    if (list.length === 0) {
        table.innerHTML = `
            <tr><td colspan="6">
                <div class="empty-state">
                    <i class="fas fa-folder-open"></i>
                    <p>Tidak ada jurusan yang ditemukan.</p>
                </div>
            </td></tr>`;
        return;
    }

    table.innerHTML = "";

    list.forEach((m, i) => {

        const isActive  = !m.deleted_at;
        const statusCls = isActive ? 'st-active' : 'st-inactive';
        const statusLbl = isActive ? 'Active' : 'Inactive';

        table.innerHTML += `
        <tr>
            <td>${i + 1}</td>

            <td>
                <div class="major-cell">
                    <div class="major-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <span class="major-name">${m.major_name}</span>
                </div>
            </td>

            <td>
                <span class="code-badge">${m.major_code}</span>
            </td>

            <td>
                <span class="classroom-count">
                    <i class="fas fa-door-open"></i>
                    ${m.classrooms_count ?? 0} kelas
                </span>
            </td>

            <td>
                <span class="status-badge ${statusCls}">
                    <span style="font-size:7px;">●</span>
                    ${statusLbl}
                </span>
            </td>

            <td>
                <button class="btn-action btn-edit" onclick="edit('${m.id}')" title="Edit">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn-action btn-del" onclick="hapus('${m.id}')" title="Hapus">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>`;
    });
}

/* ── SEARCH ── */
document.getElementById("search").addEventListener("input", function () {
    const key = this.value.toLowerCase();
    render(data.filter(m =>
        m.major_name.toLowerCase().includes(key) ||
        m.major_code.toLowerCase().includes(key)
    ));
});

/* ── OPEN MODAL ── */
function openModal() {
    editId = null;
    document.getElementById("modalTitle").innerText  = "Tambah Jurusan";
    document.getElementById("major_name").value      = "";
    document.getElementById("major_code").value      = "";
    document.getElementById("modalAlert").style.display = "none";
    $("#majorModal").modal("show");
}

/* ── SAVE ── */
async function save() {

    const alertEl = document.getElementById("modalAlert");
    alertEl.style.display = "none";

    const name = document.getElementById("major_name").value.trim();
    const code = document.getElementById("major_code").value.trim();

    if (!name || !code) {
        alertEl.innerText      = "Nama dan kode jurusan wajib diisi.";
        alertEl.style.display  = "block";
        return;
    }

    const payload = { major_name: name, major_code: code };

    try {
        if (editId) {
            await axios.put(`${API}/${editId}`, payload, axiosConfig);
        } else {
            await axios.post(API, payload, axiosConfig);
        }
        $("#majorModal").modal("hide");
        load();
    } catch (err) {
        alertEl.innerText     = err.response?.data?.message ?? "Gagal menyimpan data.";
        alertEl.style.display = "block";
    }
}

/* ── EDIT ── */
function edit(id) {
    const m = data.find(x => x.id == id);
    editId  = id;
    document.getElementById("modalTitle").innerText  = "Edit Jurusan";
    document.getElementById("major_name").value      = m.major_name;
    document.getElementById("major_code").value      = m.major_code;
    document.getElementById("modalAlert").style.display = "none";
    $("#majorModal").modal("show");
}

/* ── DELETE ── */
async function hapus(id) {
    if (!confirm("Hapus jurusan ini?")) return;
    try {
        await axios.delete(`${API}/${id}`, axiosConfig);
        load();
    } catch (err) {
        alert(err.response?.data?.message ?? "Gagal menghapus jurusan.");
    }
}

/* ── INIT ── */
load();

</script>