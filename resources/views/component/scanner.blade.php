
<link href="{{ asset('assets/css/custom_scanner.css') }}" rel="stylesheet">

<!-- HTML5 QR -->
<script src="https://unpkg.com/html5-qrcode"></script>

<!-- AXIOS -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<div class="container-fluid">

    <!-- PAGE TITLE -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">

        <div>

            <h1 class="h3 mb-1 text-gray-100 font-weight-bold">
                Scanner Absensi
            </h1>

            <p class="mb-0 text-muted">
                Scan QR Code siswa untuk melakukan absensi otomatis.
            </p>

        </div>

        <div>

            <span
                class="badge badge-primary p-2 shadow-sm"
                style="color:#fff !important;"
            >
                Sistem Absensi Digital
            </span>

        </div>

    </div>

    <div class="row">

        <!-- LEFT -->
        <div class="col-lg-3 mb-4">

            <div class="card shadow-lg border-0 scanner-card">

                <div class="card-header py-3 d-flex align-items-center justify-content-between">

                    <h5 class="m-0 font-weight-bold text-primary" >
                        Scanner
                    </h5>

                    <span
                        class="badge badge-danger px-3 py-2"
                        id="cameraStatus"
                        style="color:#fff !important;
                        width: 140px;
                        "
                    >
                        Kamera Tidak Aktif
                    </span>

                </div>

                <div class="card-body text-center">

                    <!-- SCANNER -->
                    <div class="scanner-box">

                        <div id="reader"></div>

                        <div class="scanner-overlay"></div>

                        <div class="scanner-line"></div>

                    </div>

                    <!-- ACTION -->
                    <div class="scanner-action mt-4">

                        <button
                            class="scanner-btn start-btn"
                            id="startScanner"
                        >

                            <i class="fas fa-camera mr-2"></i>
                            <span>Mulai Scan</span>

                        </button>

                    </div>

                </div>

            </div>

        </div>

        <!-- RIGHT -->
        <div class="col-lg-9">

            <div class="card shadow-lg border-0 student-card">

                <div class="card-header py-3">

                    <h6 class="m-0 font-weight-bold text-primary">
                        Data Siswa
                    </h6>

                </div>

                <div class="card-body">

                    <!-- PROFILE -->
                    <div class="student-profile text-center">

                        <img
                            id="studentAvatar"
                            src="https://ui-avatars.com/api/?name=Siswa&background=2563eb&color=fff"
                            class="student-avatar mb-3"
                            alt="Student"
                        >

                        <h4
                            class="student-name"
                            id="studentName"
                        >
                            Menunggu Scan...
                        </h4>

                        <p
                            class="student-class"
                            id="studentClass"
                        >
                            -
                        </p>

                    </div>

                    <hr>

                    <!-- INFO -->
                    <div class="student-info">

                        <div class="info-item d-flex justify-content-between py-2">
                            <span>NIS</span>
                            <strong id="studentNis">-</strong>
                        </div>

                        <div class="info-item d-flex justify-content-between py-2">
                            <span>Status</span>
                            <strong
                                class="text-success"
                                id="studentStatus"
                            >
                                Belum Scan
                            </strong>
                        </div>

                        <div class="info-item d-flex justify-content-between py-2">
                            <span>Jam Masuk</span>
                            <strong id="studentJamMasuk">-</strong>
                        </div>

                        <div class="info-item d-flex justify-content-between py-2">
                            <span>Jam Pulang</span>
                            <strong id="studentJamPulang">-</strong>
                        </div>

                        <div class="info-item d-flex justify-content-between py-2">
                            <span>Tanggal</span>
                            <strong id="studentTanggal">-</strong>
                        </div>

                    </div>

                    <!-- SUCCESS -->
                    <div
                        class="attendance-success mt-4 d-flex align-items-center"
                        id="successBox"
                        style="display:none !important;"
                    >

                        <i
                            class="fas fa-check-circle text-success mr-3"
                            style="font-size:35px;"
                        ></i>

                        <div>

                            <h5 class="mb-1">
                                Absensi Berhasil
                            </h5>

                            <p class="mb-0" id="successMessage">
                                QR Code berhasil dipindai
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- TABLE -->
    <div class="row mt-4">

        <div class="col-12">

            <div class="card shadow-lg border-0">

                <div
                    class="card-header py-3 d-flex align-items-center justify-content-between"
                >

                    <div>

                        <h5 class="m-0 font-weight-bold text-primary">
                            Absensi Hari Ini
                        </h5>

                        <small class="text-muted">
                            Daftar siswa yang sudah scan QR
                        </small>

                    </div>

                    <span
                        class="badge badge-success px-3 py-2"
                        id="attendanceCount"
                        style="color:#fff !important;"
                    >
                        0 Siswa Hadir
                    </span>

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table
                            class="table table-borderless align-items-center"
                        >

                            <thead>

                                <tr>
                                    <th>No</th>
                                    <th>Siswa</th>
                                    <th>Kelas</th>
                                    <th>Jam Masuk</th>
                                    <th>Status</th>
                                </tr>

                            </thead>

                            <tbody id="attendanceTable">

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<style>

/* BUTTON */
.scanner-action{
    display:flex;
    gap:12px;
    justify-content:center;
}

.scanner-btn{

    border:none;
    outline:none;

    padding:12px 22px;

    border-radius:14px;

    font-weight:600;

    font-size:14px;

    display:flex;
    align-items:center;
    justify-content:center;

    transition:.25s ease;

    color:#fff;

    min-width:150px;

}

.start-btn{
    background:linear-gradient(
        135deg,
        #2563eb,
        #1d4ed8
    );
}

.stop-btn{
    background:linear-gradient(
        135deg,
        #dc2626,
        #991b1b
    );
}

.scanner-btn:hover{
    transform:translateY(-2px);
}

.scanner-btn:disabled{
    opacity:.7;
    cursor:not-allowed;
}

</style>

<script>

/* =====================================
   CONFIG API
===================================== */

// ✅ Gunakan URL dinamis dari Laravel
const API_URL = '{{ config("app.url") }}/api/guru/attendances';
const token = '{{ session("token") }}'; // ← ambil dari session Laravel, bukan localStorage

/* =====================================
   ELEMENT
===================================== */

let scanner = null;
let totalScan = 0;
let scannerActive = false;

const startBtn =
document.getElementById('startScanner');

const stopBtn =
document.getElementById('stopScanner');

const cameraStatus =
document.getElementById('cameraStatus');

/* =====================================
   STATUS UI
===================================== */

function setScannerStatus(active){

    scannerActive = active;

    if(active){

        cameraStatus.classList.remove(
            'badge-danger'
        );

        cameraStatus.classList.add(
            'badge-success'
        );

        cameraStatus.innerHTML =
        'Scanner Aktif';

        startBtn.disabled = true;

    }else{

        cameraStatus.classList.remove(
            'badge-success'
        );

        cameraStatus.classList.add(
            'badge-danger'
        );

        cameraStatus.innerHTML =
        'Scanner Tidak Aktif';

        startBtn.disabled = false;
    }
}

/* =====================================
   START SCANNER
===================================== */

startBtn.addEventListener(
'click',
async () => {

    if(scannerActive){
        return;
    }

    scanner =
    new Html5Qrcode("reader");

    try{

        await scanner.start(

            {
                facingMode:"environment"
            },

            {
                fps:10,

                qrbox:{
                    width:220,
                    height:220
                }
            },

            async function(decodedText){

                console.log(
                    "QR RESULT:",
                    decodedText
                );

                /* =====================================
                   HIT API ABSENSI
                ===================================== */

                try{

                    const response =
                    await axios.post(

                        API_URL,

                        {
                            qr_code:
                            decodedText
                        },

                        {
                            headers:{

                                Authorization:
                                `Bearer ${token}`,

                                Accept:
                                'application/json',

                                'Content-Type':
                                'application/json'
                            }
                        }
                    );

                    console.log(
                        response.data
                    );

                    /*
                    =====================================
                    AMBIL DATA RESPONSE
                    =====================================
                    */

                    const data =
                    response.data.data;

                    /*
                    =====================================
                    UPDATE UI
                    =====================================
                    */

                    document.getElementById(
                        'studentName'
                    ).innerHTML =
                    data.student?.name ??
                    'Tidak diketahui';

                    document.getElementById(
                        'studentClass'
                    ).innerHTML =
                    data.student?.classroom?.name ??
                    '-';

                    document.getElementById(
                        'studentNis'
                    ).innerHTML =
                    data.student?.nis ??
                    '-';

                    document.getElementById(
                        'studentStatus'
                    ).innerHTML =
                    data.status ??
                    'Hadir';

                    document.getElementById(
                        'studentJamMasuk'
                    ).innerHTML =
                    data.check_in ??
                    '-';

                    document.getElementById(
                        'studentJamPulang'
                    ).innerHTML =
                    data.check_out ??
                    '-';

                    document.getElementById(
                        'studentTanggal'
                    ).innerHTML =
                    data.date ??
                    '-';

                    /*
                    =====================================
                    AVATAR
                    =====================================
                    */

                    document.getElementById(
                        'studentAvatar'
                    ).src =
                    `https://ui-avatars.com/api/?name=${encodeURIComponent(
                        data.student?.name ?? 'Siswa'
                    )}&background=2563eb&color=fff`;

                    /*
                    =====================================
                    SUCCESS BOX
                    =====================================
                    */

                    document.getElementById(
                        'successBox'
                    ).style.display =
                    'flex';

                    document.getElementById(
                        'successMessage'
                    ).innerHTML =
                    response.data.message ??
                    'Absensi berhasil';

                    /*
                    =====================================
                    TABLE
                    =====================================
                    */

                    totalScan++;

                    document.getElementById(
                        'attendanceCount'
                    ).innerHTML =
                    `${totalScan} Siswa Hadir`;

                    document.getElementById(
                        'attendanceTable'
                    ).innerHTML += `

                        <tr>

                            <td>
                                ${totalScan}
                            </td>

                            <td
                                class="d-flex align-items-center"
                            >

                                <img
                                    src="https://ui-avatars.com/api/?name=${encodeURIComponent(
                                        data.student?.name ?? 'Siswa'
                                    )}&background=2563eb&color=fff"
                                    width="45"
                                    class="rounded-circle mr-3"
                                >

                                <div>

                                    <strong class="text-white">
                                        ${data.student?.name ?? '-' }
                                    </strong>

                                    <br>

                                    <small>
                                        ${data.student?.nis ?? '-' }
                                    </small>

                                </div>

                            </td>

                            <td>
                                ${data.student?.classroom?.name ?? '-' }
                            </td>

                            <td>
                                ${data.check_in ?? '-' }
                            </td>

                            <td>

                                <span
                                    class="badge badge-success px-3 py-2"
                                >
                                    ${data.status ?? 'Hadir'}
                                </span>

                            </td>

                        </tr>

                    `;

                }catch(apiError){

                    console.log(apiError);

                    alert(

                        apiError.response?.data?.message ??

                        'Absensi gagal'
                    );
                }
            }
        );

        setScannerStatus(true);

    }catch(err){

        console.log(err);

        alert(
            'Kamera gagal dibuka'
        );
    }
});

/* =====================================
   STOP SCANNER
===================================== */

stopBtn.addEventListener(
'click',
async () => {

    if(!scanner){
        return;
    }

    try{

        await scanner.stop();

        await scanner.clear();

        scanner = null;

        setScannerStatus(false);

    }catch(err){

        console.log(err);
    }
});

</script>