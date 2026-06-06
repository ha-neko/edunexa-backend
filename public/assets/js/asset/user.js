
const API   = window.location.origin + '/api/admin';
const token = window._token || localStorage.getItem('token');

let users = [];

/* Inisial avatar dari nama */
function getInitials(name) {
    if (!name) return '?';
    return name.split(' ').map(w => w[0]).join('').substring(0, 2).toUpperCase();
}

/* LOAD DATA */
async function getUsers() {
    try {
        const [s, t] = await Promise.all([
            axios.get(`${API}/students`, { headers: { Authorization: `Bearer ${token}` } }),
            axios.get(`${API}/teachers`, { headers: { Authorization: `Bearer ${token}` } })
        ]);

        const students = (s.data.data || []).map(x => ({ ...x, role: 'student' }));
        const teachers = (t.data.data || []).map(x => ({ ...x, role: 'teacher' }));

        users = [...students, ...teachers];
        render(users);

    } catch (e) {
        document.getElementById('userTable').innerHTML = `
            <tr><td colspan="7">
                <div class="empty-state">
                    <i class="fas fa-exclamation-triangle" style="color:#ef4444"></i>
                    <p>Gagal memuat data. Periksa koneksi server.</p>
                </div>
            </td></tr>`;
    }
}

/* RENDER */
function render(data) {

    const table = document.getElementById('userTable');

    let totalStudent = 0;
    let totalTeacher = 0;
    let active = 0;

    if (data.length === 0) {
        table.innerHTML = `
            <tr><td colspan="7">
                <div class="empty-state">
                    <i class="fas fa-users-slash"></i>
                    <p>Tidak ada data yang sesuai.</p>
                </div>
            </td></tr>`;
        document.getElementById('totalUsers').innerText    = 0;
        document.getElementById('totalStudents').innerText = 0;
        document.getElementById('totalTeachers').innerText = 0;
        document.getElementById('activeUsers').innerText   = 0;
        return;
    }

    table.innerHTML = '';

    data.forEach((u, i) => {

        if (u.role === 'student') totalStudent++;
        if (u.role === 'teacher') totalTeacher++;
        if (!u.status || u.status === 'active') active++;

        const isActive   = !u.status || u.status === 'active';
        const initials   = getInitials(u.name);
        const avatarCls  = u.role === 'student' ? 'avatar-student' : 'avatar-teacher';
        const roleCls    = u.role === 'student' ? 'role-student' : 'role-teacher';
        const roleLabel  = u.role === 'student' ? 'Siswa' : 'Guru';
        const statusCls  = isActive ? 'status-active' : 'status-inactive';
        const statusLbl  = u.status ?? 'active';

        table.innerHTML += `
        <tr>
            <td>${i + 1}</td>

            <td>
                <div class="user-cell">
                    <div class="user-avatar ${avatarCls}">${initials}</div>
                    <span class="user-cell-name">${u.name ?? '-'}</span>
                </div>
            </td>

            <td style="color:#94a3b8; font-size:13px;">${u.email ?? '-'}</td>

            <td>
                <span class="role-badge ${roleCls}">${roleLabel}</span>
            </td>

            <td>
                <span class="status-badge ${statusCls}">
                    <i class="fas fa-circle" style="font-size:6px;"></i>
                    ${statusLbl}
                </span>
            </td>

            <td style="color:#64748b; font-size:12.5px;">${u.last_login ?? '-'}</td>

            <td>
                <button class="btn-action btn-view" onclick="show(${u.id},'${u.role}')" title="Lihat Detail">
                    <i class="fas fa-eye"></i>
                </button>
                <button class="btn-action btn-delete ml-1" onclick="remove(${u.id},'${u.role}')" title="Hapus">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>`;
    });

    document.getElementById('totalUsers').innerText    = data.length;
    document.getElementById('totalStudents').innerText = totalStudent;
    document.getElementById('totalTeachers').innerText = totalTeacher;
    document.getElementById('activeUsers').innerText   = active;
}

/* SEARCH */
document.getElementById('searchInput').addEventListener('input', function () {
    const q = this.value.toLowerCase();
    render(users.filter(u =>
        u.name?.toLowerCase().includes(q) ||
        u.email?.toLowerCase().includes(q)
    ));
});

/* FILTER */
document.getElementById('roleFilter').addEventListener('change', function () {
    if (this.value === 'all') return render(users);
    render(users.filter(u => u.role === this.value));
});

/* CREATE */
document.getElementById('saveUserBtn').onclick = async () => {

    const role = document.getElementById('role').value;

    const payload = {
        name:     document.getElementById('name').value,
        email:    document.getElementById('email').value,
        password: document.getElementById('password').value
    };

    try {
        await axios.post(`${API}/${role}s`, payload, {
            headers: { Authorization: `Bearer ${token}` }
        });
        $('#createUserModal').modal('hide');
        getUsers();
    } catch (e) {
        alert('Gagal simpan');
    }
};

/* DETAIL */
async function show(id, role) {
    const res = await axios.get(`${API}/${role}s/${id}`, {
        headers: { Authorization: `Bearer ${token}` }
    });
    alert(JSON.stringify(res.data.data, null, 2));
}

/* DELETE */
async function remove(id, role) {
    if (!confirm('Hapus user ini?')) return;
    await axios.delete(`${API}/${role}s/${id}`, {
        headers: { Authorization: `Bearer ${token}` }
    });
    getUsers();
}

getUsers();