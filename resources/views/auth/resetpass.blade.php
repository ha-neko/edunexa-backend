@extends('layoutsAuth.main')
@section('title', 'Password Reset')

<body>

<!-- LEFT SIDE -->
<div class="welcome-side">

    <div class="company-logo">
        <i class="fas fa-lock"></i>
    </div>

    <h1 class="welcome-title">
        EDUNEXA
    </h1>

    <h2 class="company-name">
        Reset Password System
    </h2>

    <p class="welcome-desc">
        Buat password baru untuk mengamankan akun admin
        dan melanjutkan akses ke sistem absensi digital.
    </p>

</div>

<!-- RIGHT SIDE -->
<div class="reset-box">

    <div class="text-center mb-4">

        <h1 class="login-title">
            Reset Password
        </h1>

        <p class="login-subtitle">
            Masukkan password baru Anda
        </p>

    </div>

    <!-- ERROR -->
    <div
        id="resetError"
        class="alert alert-danger"
        style="display:none;"
    ></div>

    <!-- SUCCESS -->
    <div
        id="resetSuccess"
        class="alert alert-success"
        style="display:none;"
    ></div>

    <!-- FORM -->
    <form id="resetPasswordForm">

        <!-- PASSWORD -->
        <div class="form-group">

            <input
                type="password"
                id="password"
                class="form-control form-control-user"
                placeholder="Masukkan password baru"
                required
            >

        </div>

        <!-- CONFIRM PASSWORD -->
        <div class="form-group">

            <input
                type="password"
                id="password_confirmation"
                class="form-control form-control-user"
                placeholder="Konfirmasi password baru"
                required
            >

        </div>

        <!-- BUTTON -->
        <button
            type="submit"
            class="btn btn-login btn-block"
            id="btnReset"
        >

            <i class="fas fa-save mr-2"></i>
            Simpan Password

        </button>

    </form>

    <hr>

    <!-- BACK -->
    <div class="text-center">

        <a href="/" class="small">

            <i class="fas fa-arrow-left mr-1"></i>
            Kembali ke Login

        </a>

    </div>

</div>

<!-- AXIOS -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<!-- SCRIPT -->
<script>

document.getElementById('resetPasswordForm')
.addEventListener('submit', async function(e){

    e.preventDefault();

    const password =
        document.getElementById('password').value;

    const confirmPassword =
        document.getElementById('password_confirmation').value;

    const btnReset =
        document.getElementById('btnReset');

    const resetError =
        document.getElementById('resetError');

    const resetSuccess =
        document.getElementById('resetSuccess');

    // RESET ALERT
    resetError.style.display = 'none';
    resetSuccess.style.display = 'none';

    // VALIDASI
    if(password !== confirmPassword){

        resetError.style.display = 'block';

        resetError.innerHTML =
            'Konfirmasi password tidak cocok';

        return;

    }

    // LOADING
    btnReset.disabled = true;

    btnReset.innerHTML = `
        <i class="fas fa-spinner fa-spin mr-2"></i>
        Menyimpan...
    `;

    try{

        const response = await axios.post(

            'http://10.6.160.79:8081/api/admin/auth/reset-password',

            {
                password: password,
                password_confirmation: confirmPassword
            },

            {
                headers:{
                    'Accept':'application/json',
                    'Content-Type':'application/json'
                }
            }

        );

        resetSuccess.style.display = 'block';

        resetSuccess.innerHTML =
            response.data.message ||
            'Password berhasil direset';

        // REDIRECT LOGIN
        setTimeout(() => {

            window.location.href = '/';

        }, 2000);

    }catch(error){

        console.log(error);

        resetError.style.display = 'block';

        if(error.response){

            resetError.innerHTML =
                error.response.data.message ||
                'Reset password gagal';

        }else{

            resetError.innerHTML =
                'Tidak dapat terhubung ke server';

        }

    }finally{

        btnReset.disabled = false;

        btnReset.innerHTML = `
            <i class="fas fa-save mr-2"></i>
            Simpan Password
        `;

    }

});

</script>

