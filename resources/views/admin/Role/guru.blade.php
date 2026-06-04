@extends('component.layouts.admin.main')
@section('title', 'Master Guru')

@section('content')

<!-- AXIOS -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<div class="container-fluid">

    <!-- HEADER -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">

        <div>

            <h1 class="h3 mb-1 font-weight-bold text-light">
                Master Guru
            </h1>

            <p class="mb-0 text-muted">
                Management data guru dan monitoring pengajar EDUNEXA
            </p>

        </div>

        <button
            class="btn btn-primary shadow-sm"
            data-toggle="modal"
            data-target="#createTeacherModal"
        >

            <i class="fas fa-plus mr-2"></i>
            Tambah Guru

        </button>

    </div>

    <!-- STATISTIC -->
    <div class="row">

        <!-- TOTAL -->
        <div class="col-xl-4 col-md-6 mb-4">

            <div class="card border-0 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-uppercase text-muted font-weight-bold">
                                Total Guru
                            </small>

                            <h2
                                class="mt-2 font-weight-bold text-light"
                                id="totalTeacher"
                            >
                                0
                            </h2>

                        </div>

                        <div class="icon-box bg-primary">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- ACTIVE -->
        <div class="col-xl-4 col-md-6 mb-4">

            <div class="card border-0 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-uppercase text-muted font-weight-bold">
                                Guru Active
                            </small>

                            <h2
                                class="mt-2 font-weight-bold text-light"
                                id="activeTeacher"
                            >
                                0
                            </h2>

                        </div>

                        <div class="icon-box bg-success">
                            <i class="fas fa-user-check"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- NON ACTIVE -->
        <div class="col-xl-4 col-md-6 mb-4">

            <div class="card border-0 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-uppercase text-muted font-weight-bold">
                                Non Active
                            </small>

                            <h2
                                class="mt-2 font-weight-bold text-light"
                                id="inactiveTeacher"
                            >
                                0
                            </h2>

                        </div>

                        <div class="icon-box bg-danger">
                            <i class="fas fa-user-times"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- TABLE -->
    <div class="card border-0">

        <div
            class="card-header py-3 d-flex justify-content-between align-items-center"
        >

            <div>

                <h6 class="m-0 font-weight-bold text-light">
                    List Guru
                </h6>

            </div>

            <div class="d-flex">

                <!-- SEARCH -->
                <input
                    type="text"
                    class="form-control mr-2"
                    id="searchTeacher"
                    placeholder="Cari guru..."
                    style="width:220px;"
                >

                <!-- FILTER -->
                <select
                    class="form-control"
                    id="filterSubject"
                >

                    <option value="">
                        Semua Mapel
                    </option>

                    <option value="Informatika">
                        Informatika
                    </option>

                    <option value="Matematika">
                        Matematika
                    </option>

                    <option value="Bahasa Inggris">
                        Bahasa Inggris
                    </option>

                </select>

            </div>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table align-items-center">

                    <thead>

                        <tr>

                            <th>No</th>
                            <th>Guru</th>
                            <th>NIP</th>
                            <th>Mata Pelajaran</th>
                            <th>No HP</th>
                            <th>Status</th>
                            <th>Aksi</th>

                        </tr>

                    </thead>

                    <tbody id="teacherTable">

                        <!-- AUTO DATA -->

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<!-- MODAL -->
<div
    class="modal fade"
    id="createTeacherModal"
    tabindex="-1"
>

    <div class="modal-dialog modal-lg">

        <div class="modal-content border-0">

            <div class="modal-header">

                <h5 class="modal-title text-light font-weight-bold">
                    Tambah Guru
                </h5>

                <button
                    type="button"
                    class="close text-light"
                    data-dismiss="modal"
                >
                    <span>&times;</span>
                </button>

            </div>

            <div class="modal-body">

                <form id="teacherForm">

                    <div class="row">

                        <!-- NAME -->
                        <div class="col-md-6 mb-3">

                            <label>Nama Guru</label>

                            <input
                                type="text"
                                class="form-control"
                                id="teacher_name"
                                placeholder="Masukkan nama guru"
                                required
                            >

                        </div>

                        <!-- EMAIL -->
                        <div class="col-md-6 mb-3">

                            <label>Email</label>

                            <input
                                type="email"
                                class="form-control"
                                id="teacher_email"
                                placeholder="Masukkan email"
                                required
                            >

                        </div>

                        <!-- NIP -->
                        <div class="col-md-6 mb-3">

                            <label>NIP</label>

                            <input
                                type="text"
                                class="form-control"
                                id="teacher_nip"
                                placeholder="Masukkan NIP"
                                required
                            >

                        </div>

                        <!-- SUBJECT -->
                        <div class="col-md-6 mb-3">

                            <label>Mata Pelajaran</label>

                            <select
                                class="form-control"
                                id="teacher_subject"
                                required
                            >

                                <option value="">
                                    Pilih Mata Pelajaran
                                </option>

                                <option value="Informatika">
                                    Informatika
                                </option>

                                <option value="Matematika">
                                    Matematika
                                </option>

                                <option value="Bahasa Inggris">
                                    Bahasa Inggris
                                </option>

                            </select>

                        </div>

                        <!-- PHONE -->
                        <div class="col-md-6 mb-3">

                            <label>No Handphone</label>

                            <input
                                type="text"
                                class="form-control"
                                id="teacher_phone"
                                placeholder="Masukkan nomor HP"
                                required
                            >

                        </div>

                        <!-- STATUS -->
                        <div class="col-md-6 mb-3">

                            <label>Status</label>

                            <select
                                class="form-control"
                                id="teacher_status"
                            >

                                <option value="active">
                                    Active
                                </option>

                                <option value="inactive">
                                    Inactive
                                </option>

                            </select>

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
                    type="submit"
                    class="btn btn-primary"
                    id="saveTeacherBtn"
                >

                    <i class="fas fa-save mr-2"></i>
                    Simpan Guru

                </button>

            </div>

        </div>

    </div>

</div>

<script>

/* =========================================
   STORAGE
========================================= */

let teacherData = [

    {
        id:1,
        name:'Budi Santoso',
        email:'budi@edunexa.sch.id',
        nip:'198765001',
        subject:'Informatika',
        phone:'08123456789',
        status:'active'
    },

    {
        id:2,
        name:'Siti Rahma',
        email:'siti@edunexa.sch.id',
        nip:'198765002',
        subject:'Matematika',
        phone:'08129876543',
        status:'active'
    },

    {
        id:3,
        name:'Ahmad Fauzi',
        email:'ahmad@edunexa.sch.id',
        nip:'198765003',
        subject:'Bahasa Inggris',
        phone:'08127771234',
        status:'inactive'
    }

];

/* =========================================
   ELEMENT
========================================= */

const teacherTable =
document.getElementById('teacherTable');

const totalTeacher =
document.getElementById('totalTeacher');

const activeTeacher =
document.getElementById('activeTeacher');

const inactiveTeacher =
document.getElementById('inactiveTeacher');

const searchTeacher =
document.getElementById('searchTeacher');

const filterSubject =
document.getElementById('filterSubject');

/* =========================================
   RENDER
========================================= */

function renderTeacher(data){

    teacherTable.innerHTML = '';

    let active = 0;
    let inactive = 0;

    data.forEach((teacher,index)=>{

        if(teacher.status === 'active'){
            active++;
        }else{
            inactive++;
        }

        teacherTable.innerHTML += `

            <tr>

                <td>
                    ${index + 1}
                </td>

                <td>

                    <div class="d-flex align-items-center">

                        <img
                            src="https://i.pravatar.cc/100?u=${teacher.email}"
                            class="rounded-circle mr-3"
                            width="45"
                        >

                        <div>

                            <div class="font-weight-bold text-light">
                                ${teacher.name}
                            </div>

                            <small class="text-muted">
                                ${teacher.email}
                            </small>

                        </div>

                    </div>

                </td>

                <td class="text-light">
                    ${teacher.nip}
                </td>

                <td>

                    <span class="badge badge-primary px-3 py-2">
                        ${teacher.subject}
                    </span>

                </td>

                <td class="text-light">
                    ${teacher.phone}
                </td>

                <td>

                    <span class="badge ${
                        teacher.status === 'active'
                        ? 'badge-success'
                        : 'badge-danger'
                    } px-3 py-2">

                        ${teacher.status}

                    </span>

                </td>

                <td>

                    <div class="d-flex">

                        <!-- DETAIL -->
                        <button
                            class="btn btn-sm btn-primary mr-2"
                            onclick="showTeacher(${teacher.id})"
                        >

                            <i class="fas fa-eye"></i>

                        </button>

                        <!-- DELETE -->
                        <button
                            class="btn btn-sm btn-danger"
                            onclick="deleteTeacher(${teacher.id})"
                        >

                            <i class="fas fa-trash"></i>

                        </button>

                    </div>

                </td>

            </tr>

        `;
    });

    totalTeacher.innerHTML =
    teacherData.length;

    activeTeacher.innerHTML =
    active;

    inactiveTeacher.innerHTML =
    inactive;
}

/* =========================================
   CREATE
========================================= */

document.getElementById(
'saveTeacherBtn'
).addEventListener(
'click',
()=>{

    const payload = {

        id:
        Date.now(),

        name:
        document.getElementById(
            'teacher_name'
        ).value,

        email:
        document.getElementById(
            'teacher_email'
        ).value,

        nip:
        document.getElementById(
            'teacher_nip'
        ).value,

        subject:
        document.getElementById(
            'teacher_subject'
        ).value,

        phone:
        document.getElementById(
            'teacher_phone'
        ).value,

        status:
        document.getElementById(
            'teacher_status'
        ).value
    };

    teacherData.push(payload);

    renderTeacher(teacherData);

    $('#createTeacherModal').modal('hide');

    document.getElementById(
        'teacherForm'
    ).reset();

});

/* =========================================
   DETAIL
========================================= */

function showTeacher(id){

    const teacher =
    teacherData.find(
        item=>item.id === id
    );

    alert(

`Nama : ${teacher.name}

Email : ${teacher.email}

NIP : ${teacher.nip}

Mapel : ${teacher.subject}

No HP : ${teacher.phone}

Status : ${teacher.status}`

    );

}

/* =========================================
   DELETE
========================================= */

function deleteTeacher(id){

    const confirmDelete =
    confirm(
        'Yakin ingin menghapus guru ini?'
    );

    if(!confirmDelete){
        return;
    }

    teacherData =
    teacherData.filter(
        item=>item.id !== id
    );

    renderTeacher(teacherData);

}

/* =========================================
   FILTER
========================================= */

searchTeacher.addEventListener(
'keyup',
filterTeacher
);

filterSubject.addEventListener(
'change',
filterTeacher
);

function filterTeacher(){

    const keyword =
    searchTeacher.value.toLowerCase();

    const subject =
    filterSubject.value;

    const filtered =
    teacherData.filter(item=>{

        const matchKeyword =

            item.name
            .toLowerCase()
            .includes(keyword)

            ||

            item.email
            .toLowerCase()
            .includes(keyword);

        const matchSubject =

            subject === ''

            ||

            item.subject === subject;

        return (
            matchKeyword &&
            matchSubject
        );

    });

    renderTeacher(filtered);

}

/* =========================================
   INIT
========================================= */

renderTeacher(teacherData);

</script>

@endsection