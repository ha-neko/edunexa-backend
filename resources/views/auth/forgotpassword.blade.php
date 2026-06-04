@extends('layoutsAuth.main')
@section('title', 'Password Recovery')

<body>

<!-- LEFT SIDE -->
<div class="welcome-side">

    <div class="company-logo">
        <i class="fas fa-shield-alt"></i>
    </div>

    <h1 class="welcome-title">
        Password Recovery
    </h1>

    <h2 class="company-name">
        EDUNEXA SECURITY
    </h2>

    <p class="welcome-desc">
        Masukkan email akun Anda untuk menerima
        link reset password secara aman dan cepat.
    </p>

</div>

<!-- RIGHT SIDE -->
<div class="login-box">

    <!-- HEADER -->
    <div class="text-center mb-4">

        <h1 class="h4 login-title">
            Lupa Password?
        </h1>

        <p class="login-subtitle">
            Kami akan membantu memulihkan akun Anda
        </p>

    </div>

    <!-- ALERT -->
    <div
        id="alertBox"
        class="alert"
        style="display:none;"
    ></div>

    <!-- FORM -->
    <form id="forgotPasswordForm">

        <!-- EMAIL -->
        <div class="form-group">

            <label class="text-light mb-2">
                Email Akun
            </label>

            <input
                type="email"
                id="email"
                class="form-control form-control-user"
                placeholder="Masukkan email..."
                required
            >

        </div>

        <!-- BUTTON -->
        <button
            type="submit"
            id="submitBtn"
            class="btn btn-login btn-block"
        >

            <i class="fas fa-paper-plane mr-2"></i>
            Kirim Link Reset

        </button>

    </form>

    <!-- EXTRA -->
    <div class="text-center mt-4">

        <a href="/" class="small text-light">

            <i class="fas fa-arrow-left mr-1"></i>
            Kembali ke Login

        </a>

    </div>

</div>

<!-- STYLE -->
<style>

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

// ====================================
// API URL
// ====================================

const API_URL =
    'http://10.6.160.79:8081/api';


// ====================================
// FORM SUBMIT
// ====================================

document.getElementById(
    'forgotPasswordForm'
)
.addEventListener('submit', async function(e){

    e.preventDefault();

    // ELEMENT
    const email =
        document.getElementById('email')
        .value
        .trim();

    const alertBox =
        document.getElementById('alertBox');

    const submitBtn =
        document.getElementById('submitBtn');

    // RESET ALERT
    alertBox.style.display = 'none';

    // VALIDATION
    if(!email){

        alertBox.style.display = 'block';

        alertBox.className =
            'alert alert-danger';

        alertBox.innerHTML =
            'Email wajib diisi';

        return;

    }

    // LOADING
    submitBtn.disabled = true;

    submitBtn.innerHTML = `
        <i class="fas fa-spinner fa-spin mr-2"></i>
        Mengirim...
    `;

    try{

        // ====================================
        // REQUEST API
        // ====================================

        const response = await axios.post(

            `${API_URL}/forgot-password`,

            {
                email: email
            },

            {
                headers:{

                    'Accept':'application/json',

                    'Content-Type':'application/json'

                }
            }

        );

        console.log(response.data);

        // SUCCESS
        alertBox.style.display = 'block';

        alertBox.className =
            'alert alert-success';

        alertBox.innerHTML =
            'Link reset password berhasil dikirim ke email Anda';

    }catch(error){

        console.log(error);

        alertBox.style.display = 'block';

        alertBox.className =
            'alert alert-danger';

        // NETWORK
        if(error.code === 'ERR_NETWORK'){

            alertBox.innerHTML =
                'Backend tidak dapat diakses';

        }

        // API ERROR
        else if(error.response){

            alertBox.innerHTML =
                error.response.data.message ||
                'Gagal mengirim reset password';

        }

        // UNKNOWN
        else{

            alertBox.innerHTML =
                'Terjadi kesalahan';

        }

    }finally{

        submitBtn.disabled = false;

        submitBtn.innerHTML = `
            <i class="fas fa-paper-plane mr-2"></i>
            Kirim Link Reset
        `;

    }

});

</script>

</body>
</html>