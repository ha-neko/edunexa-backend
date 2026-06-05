
<h2 style="color: #ff4949 !important;">--Masih dalam tahap pengembangan--</h2>
<!-- AXIOS -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<div class="container-fluid">

    <!-- HEADER -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">

        <div>

            <h1 class="h3 mb-1 text-white font-weight-bold">
                Jadwal Pelajaran
            </h1>

            <p class="mb-0 text-muted">
                Management jadwal pelajaran berdasarkan kelas.
            </p>

        </div>

        <!-- ACTION -->
        <button
            class="btn btn-primary"
            data-toggle="modal"
            data-target="#scheduleModal"
        >

            <i class="fas fa-plus mr-2"></i>
            Tambah Jadwal

        </button>

    </div>

    <!-- FILTER -->
    <div class="card border-0 mb-4">

        <div class="card-body">

            <div class="row">

                <!-- FILTER KELAS -->
                <div class="col-md-4 mb-2">

                    <select
                        class="form-control"
                        id="filterClass"
                    >

                        <option value="">
                            Semua Kelas
                        </option>

                    </select>

                </div>

                <!-- FILTER HARI -->
                <div class="col-md-4 mb-2">

                    <select
                        class="form-control"
                        id="filterDay"
                    >

                        <option value="">
                            Semua Hari
                        </option>

                        <option value="Senin">
                            Senin
                        </option>

                        <option value="Selasa">
                            Selasa
                        </option>

                        <option value="Rabu">
                            Rabu
                        </option>

                        <option value="Kamis">
                            Kamis
                        </option>

                        <option value="Jumat">
                            Jumat
                        </option>

                    </select>

                </div>

                <!-- SEARCH -->
                <div class="col-md-4 mb-2">

                    <input
                        type="text"
                        class="form-control"
                        id="searchSchedule"
                        placeholder="Cari mapel / guru..."
                    >

                </div>

            </div>

        </div>

    </div>

    <!-- INFO CARD -->
    <div class="row mb-4">

        <!-- TOTAL -->
        <div class="col-xl-4 col-md-6 mb-3">

            <div class="card border-0 h-100">

                <div class="card-body d-flex align-items-center justify-content-between">

                    <div>

                        <small class="text-uppercase text-muted font-weight-bold">
                            Total Jadwal
                        </small>

                        <h3
                            class="mt-2 text-white font-weight-bold"
                            id="totalSchedule"
                        >
                            0
                        </h3>

                    </div>

                    <div class="icon-box bg-primary">
                        <i class="fas fa-calendar"></i>
                    </div>

                </div>

            </div>

        </div>

        <!-- TOTAL KELAS -->
        <div class="col-xl-4 col-md-6 mb-3">

            <div class="card border-0 h-100">

                <div class="card-body d-flex align-items-center justify-content-between">

                    <div>

                        <small class="text-uppercase text-muted font-weight-bold">
                            Total Kelas
                        </small>

                        <h3
                            class="mt-2 text-white font-weight-bold"
                            id="totalClass"
                        >
                            0
                        </h3>

                    </div>

                    <div class="icon-box bg-success">
                        <i class="fas fa-school"></i>
                    </div>

                </div>

            </div>

        </div>

        <!-- TOTAL MAPEL -->
        <div class="col-xl-4 col-md-6 mb-3">

            <div class="card border-0 h-100">

                <div class="card-body d-flex align-items-center justify-content-between">

                    <div>

                        <small class="text-uppercase text-muted font-weight-bold">
                            Total Mapel
                        </small>

                        <h3
                            class="mt-2 text-white font-weight-bold"
                            id="totalSubject"
                        >
                            0
                        </h3>

                    </div>

                    <div class="icon-box bg-warning">
                        <i class="fas fa-book"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- TABLE -->
    <div class="card border-0">

        <div class="card-header py-3">

            <h6 class="m-0 font-weight-bold text-white">
                Data Jadwal
            </h6>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table align-items-center">

                    <thead>

                        <tr>

                            <th>No</th>
                            <th>Kelas</th>
                            <th>Hari</th>
                            <th>Jam</th>
                            <th>Mata Pelajaran</th>
                            <th>Guru</th>
                            <th>Ruangan</th>
                            <th>Aksi</th>

                        </tr>

                    </thead>

                    <tbody id="scheduleTable">

                        <!-- AUTO RENDER -->

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<!-- MODAL -->
<div
    class="modal fade"
    id="scheduleModal"
    tabindex="-1"
>

    <div class="modal-dialog modal-lg">

        <div class="modal-content border-0">

            <div class="modal-header">

                <h5 class="modal-title text-white font-weight-bold">
                    Tambah Jadwal
                </h5>

                <button
                    type="button"
                    class="close text-white"
                    data-dismiss="modal"
                >
                    <span>&times;</span>
                </button>

            </div>

            <div class="modal-body">

                <form id="scheduleForm">

                    <div class="row">

                        <!-- KELAS -->
                        <div class="col-md-6 mb-3">

                            <label>
                                Kelas
                            </label>

                            <select
                                class="form-control"
                                id="classroom"
                                required
                            >

                            </select>

                        </div>

                        <!-- HARI -->
                        <div class="col-md-6 mb-3">

                            <label>
                                Hari
                            </label>

                            <select
                                class="form-control"
                                id="day"
                                required
                            >

                                <option value="">
                                    Pilih Hari
                                </option>

                                <option value="Senin">
                                    Senin
                                </option>

                                <option value="Selasa">
                                    Selasa
                                </option>

                                <option value="Rabu">
                                    Rabu
                                </option>

                                <option value="Kamis">
                                    Kamis
                                </option>

                                <option value="Jumat">
                                    Jumat
                                </option>

                            </select>

                        </div>

                        <!-- JAM -->
                        <div class="col-md-6 mb-3">

                            <label>
                                Jam
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="time"
                                placeholder="07:00 - 08:30"
                                required
                            >

                        </div>

                        <!-- MAPEL -->
                        <div class="col-md-6 mb-3">

                            <label>
                                Mata Pelajaran
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="subject"
                                placeholder="Masukkan mapel"
                                required
                            >

                        </div>

                        <!-- GURU -->
                        <div class="col-md-6 mb-3">

                            <label>
                                Guru
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="teacher"
                                placeholder="Nama guru"
                                required
                            >

                        </div>

                        <!-- RUANGAN -->
                        <div class="col-md-6 mb-3">

                            <label>
                                Ruangan
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="room"
                                placeholder="Lab 1"
                                required
                            >

                        </div>

                    </div>

                </form>

            </div>

            <div class="modal-footer border-0">

                <button
                    type="button"
                    class="btn btn-outline-light"
                    data-dismiss="modal"
                >
                    Batal
                </button>

                <button
                    type="button"
                    class="btn btn-primary"
                    id="saveSchedule"
                >

                    <i class="fas fa-save mr-2"></i>
                    Simpan

                </button>

            </div>

        </div>

    </div>

</div>

<script>

/* =========================================
   STORAGE
========================================= */

let classroomData = [

    {
        id:1,
        name:'XII RPL 1'
    },

    {
        id:2,
        name:'XI TKJ 2'
    },

    {
        id:3,
        name:'X DKV 1'
    }

];

let scheduleData = JSON.parse(
    localStorage.getItem('scheduleData')
) || [

    {
        id:1,
        classroom:'XII RPL 1',
        day:'Senin',
        time:'07:00 - 08:30',
        subject:'Pemrograman Web',
        teacher:'Budi Santoso',
        room:'Lab 1'
    },

    {
        id:2,
        classroom:'XI TKJ 2',
        day:'Selasa',
        time:'08:30 - 10:00',
        subject:'Jaringan Dasar',
        teacher:'Ahmad Fauzi',
        room:'Lab 2'
    }

];

/* =========================================
   ELEMENT
========================================= */

const scheduleTable =
document.getElementById('scheduleTable');

const classroomSelect =
document.getElementById('classroom');

const filterClass =
document.getElementById('filterClass');

const filterDay =
document.getElementById('filterDay');

const searchSchedule =
document.getElementById('searchSchedule');

/* =========================================
   LOAD CLASSROOM
========================================= */

function loadClassroom(){

    classroomSelect.innerHTML =
    '<option value="">Pilih Kelas</option>';

    filterClass.innerHTML =
    '<option value="">Semua Kelas</option>';

    classroomData.forEach(item=>{

        classroomSelect.innerHTML += `

            <option value="${item.name}">
                ${item.name}
            </option>

        `;

        filterClass.innerHTML += `

            <option value="${item.name}">
                ${item.name}
            </option>

        `;

    });

}

/* =========================================
   SAVE LOCAL STORAGE
========================================= */

function saveStorage(){

    localStorage.setItem(
        'scheduleData',
        JSON.stringify(scheduleData)
    );

}

/* =========================================
   RENDER TABLE
========================================= */

function renderTable(data){

    scheduleTable.innerHTML = '';

    data.forEach((item,index)=>{

        scheduleTable.innerHTML += `

            <tr>

                <td>
                    ${index + 1}
                </td>

                <td class="text-white font-weight-bold">
                    ${item.classroom}
                </td>

                <td>
                    <span class="badge badge-primary px-3 py-2">
                        ${item.day}
                    </span>
                </td>

                <td class="text-white">
                    ${item.time}
                </td>

                <td class="text-white">
                    ${item.subject}
                </td>

                <td class="text-white">
                    ${item.teacher}
                </td>

                <td class="text-white">
                    ${item.room}
                </td>

                <td>

                    <div class="d-flex">

                        <!-- DELETE -->
                        <button
                            class="btn btn-sm btn-danger"
                            onclick="deleteSchedule(${item.id})"
                        >

                            <i class="fas fa-trash"></i>

                        </button>

                    </div>

                </td>

            </tr>

        `;

    });

    document.getElementById(
        'totalSchedule'
    ).innerHTML =
    scheduleData.length;

    document.getElementById(
        'totalClass'
    ).innerHTML =
    classroomData.length;

    document.getElementById(
        'totalSubject'
    ).innerHTML =
    scheduleData.length;

}

/* =========================================
   CREATE
========================================= */

document.getElementById(
'saveSchedule'
).addEventListener(
'click',
()=>{

    const payload = {

        id:
        Date.now(),

        classroom:
        document.getElementById('classroom').value,

        day:
        document.getElementById('day').value,

        time:
        document.getElementById('time').value,

        subject:
        document.getElementById('subject').value,

        teacher:
        document.getElementById('teacher').value,

        room:
        document.getElementById('room').value

    };

    scheduleData.push(payload);

    saveStorage();

    renderTable(scheduleData);

    $('#scheduleModal').modal('hide');

    document.getElementById(
        'scheduleForm'
    ).reset();

});

/* =========================================
   DELETE
========================================= */

function deleteSchedule(id){

    const confirmDelete =
    confirm(
        'Yakin ingin menghapus jadwal?'
    );

    if(!confirmDelete){
        return;
    }

    scheduleData =
    scheduleData.filter(
        item=>item.id !== id
    );

    saveStorage();

    renderTable(scheduleData);

}

/* =========================================
   FILTER
========================================= */

filterClass.addEventListener(
'change',
filterData
);

filterDay.addEventListener(
'change',
filterData
);

searchSchedule.addEventListener(
'keyup',
filterData
);

function filterData(){

    const classValue =
    filterClass.value;

    const dayValue =
    filterDay.value;

    const keyword =
    searchSchedule.value.toLowerCase();

    const filtered =
    scheduleData.filter(item=>{

        const matchClass =

            classValue === ''

            ||

            item.classroom === classValue;

        const matchDay =

            dayValue === ''

            ||

            item.day === dayValue;

        const matchKeyword =

            item.subject
            .toLowerCase()
            .includes(keyword)

            ||

            item.teacher
            .toLowerCase()
            .includes(keyword);

        return (
            matchClass &&
            matchDay &&
            matchKeyword
        );

    });

    renderTable(filtered);

}

/* =========================================
   INIT
========================================= */

loadClassroom();

renderTable(scheduleData);

</script>