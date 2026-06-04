@extends('layoutsAuth.main')
@section('title', 'OTP Verification')

<body>

<!-- LEFT SIDE -->
<div class="welcome-side">

    <div class="company-logo">
        <i class="fas fa-shield-alt"></i>
    </div>

    <h1 class="welcome-title">
        OTP Verification
    </h1>

    <h2 class="company-name">
        EDUNEXA SECURITY
    </h2>

    <p class="welcome-desc">
        Masukkan kode OTP yang telah dikirim
        ke email Anda untuk melanjutkan proses
        pemulihan akun.
    </p>

</div>

<!-- RIGHT SIDE -->
<div class="login-box">

    <!-- HEADER -->
    <div class="text-center mb-4">

        <h1 class="h4 login-title">
            Verifikasi OTP
        </h1>

        <p class="login-subtitle">
            Periksa email Anda dan masukkan kode OTP
        </p>

    </div>

    <!-- ALERT -->
    <div
        id="alertBox"
        class="alert"
        style="display:none;"
    ></div>

    <!-- OTP FORM -->
    <form id="otpForm">

        <!-- OTP BOX -->
        <div class="otp-group">

            <input type="text" maxlength="1" class="otp-input">
            <input type="text" maxlength="1" class="otp-input">
            <input type="text" maxlength="1" class="otp-input">
            <input type="text" maxlength="1" class="otp-input">
            <input type="text" maxlength="1" class="otp-input">
            <input type="text" maxlength="1" class="otp-input">

        </div>

        <!-- BUTTON -->
        <button
            type="submit"
            id="verifyBtn"
            class="btn btn-login btn-block mt-4"
        >

            <i class="fas fa-check-circle mr-2"></i>
            Verifikasi OTP

        </button>

    </form>

    <!-- RESEND -->
    <div class="text-center mt-4">

        <p class="text-light mb-2">
            Tidak menerima kode?
        </p>

        <button
            id="resendBtn"
            class="btn btn-outline-light btn-sm"
        >

            Kirim Ulang OTP

        </button>

    </div>

    <!-- BACK -->
    <div class="text-center mt-4">

        <a href="/" class="small text-light">

            <i class="fas fa-arrow-left mr-1"></i>
            Kembali ke Login

        </a>

    </div>

</div>

<!-- STYLE -->
<style>

.otp-group{

    display:flex;
    justify-content:center;
    gap:12px;

}

.otp-input{

    width:58px;
    height:65px;

    border:none;
    outline:none;

    border-radius:16px;

    text-align:center;

    font-size:24px;
    font-weight:700;

    background:rgba(255,255,255,.08);

    color:#fff;

    transition:.25s ease;

    border:1px solid rgba(255,255,255,.08);

}

.otp-input:focus{

    border-color:#3b82f6;

    box-shadow:
        0 0 0 4px rgba(59,130,246,.2);

    transform:translateY(-2px);

}

.alert-success{

    background:rgba(34,197,94,.15);
    border:1px solid rgba(34,197,94,.3);
    color:#4ade80;

}

.alert-danger{

    background:rgba(239,68,68,.15);
    border:1px solid rgba(239,68,68,.3);
    color:#f87171;

}

</style>

<!-- AXIOS -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<!-- SCRIPT -->
<script>

// ========================================
// OTP AUTO NEXT
// ========================================

const inputs =
    document.querySelectorAll('.otp-input');

inputs.forEach((input, index) => {

    input.addEventListener('input', () => {

        if(input.value.length > 0){

            if(index < inputs.length - 1){

                inputs[index + 1].focus();

            }

        }

    });

});


// ========================================
// VERIFY OTP
// ========================================

document.getElementById('otpForm')
.addEventListener('submit', async function(e){

    e.preventDefault();

    const alertBox =
        document.getElementById('alertBox');

    const verifyBtn =
        document.getElementById('verifyBtn');

    // GET OTP
    let otp = '';

    inputs.forEach(input => {

        otp += input.value;

    });

    // VALIDATE
    if(otp.length < 6){

        alertBox.style.display = 'block';

        alertBox.className =
            'alert alert-danger';

        alertBox.innerHTML =
            'OTP harus 6 digit';

        return;

    }

    verifyBtn.disabled = true;

    verifyBtn.innerHTML = `
        <i class="fas fa-spinner fa-spin mr-2"></i>
        Verifying...
    `;

    try{

        // SIMULASI API
        console.log("OTP:", otp);

        // NANTI GANTI KE API ASLI
        /*
        await axios.post(
            'http://10.6.160.79/api/verify-otp',
            {
                otp: otp
            }
        );
        */

        alertBox.style.display = 'block';

        alertBox.className =
            'alert alert-success';

        alertBox.innerHTML =
            'OTP berhasil diverifikasi';

        // REDIRECT
        setTimeout(() => {

            window.location.href =
                '/reset-password';

        }, 1500);

    }catch(error){

        alertBox.style.display = 'block';

        alertBox.className =
            'alert alert-danger';

        alertBox.innerHTML =
            'OTP tidak valid';

    }finally{

        verifyBtn.disabled = false;

        verifyBtn.innerHTML = `
            <i class="fas fa-check-circle mr-2"></i>
            Verifikasi OTP
        `;

    }

});


// ========================================
// RESEND OTP
// ========================================

document.getElementById('resendBtn')
.addEventListener('click', async () => {

    alert('OTP berhasil dikirim ulang');

});

</script>

</body>
</html>