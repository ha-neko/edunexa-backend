@extends('component.layouts.auth.main')
@section('title', 'Login')

<body>

<!-- WELCOME SIDE -->
<div class="welcome-side">

    <div class="company-logo">
        <i class="fas fa-building"></i>
    </div>

    <h1 class="welcome-title">
        EDUNEXA
    </h1>

    <h2 class="company-name">
        Admin Panel CS Absensi
    </h2>

    <p class="welcome-desc">
        Sistem manajemen internal untuk mengelola data,
        pengguna, operasional, dan aktivitas perusahaan
        secara cepat, aman, dan modern.
    </p>

</div>

<!-- LOGIN BOX -->
<div class="login-box">

    <div class="text-center mb-4">

        <h1 class="h4 login-title">
            Selamat Datang!
        </h1>

        <p class="login-subtitle">
            Silakan login ke sistem
        </p>

    </div>

    <!-- ERROR ALERT -->
    <div
        id="loginError"
        class="alert alert-danger"
        style="display:none;"
    ></div>

    <!-- FORM LOGIN -->
    <form id="loginAdminForm">

        <!-- EMAIL -->
        <div class="form-group">

            <input
                type="email"
                id="email"
                name="email"
                class="form-control form-control-user"
                placeholder="Masukkan Email..."
                required
            >

        </div>

        <!-- PASSWORD -->
        <div class="form-group">

            <input
                type="password"
                id="password"
                name="password"
                class="form-control form-control-user"
                placeholder="Password"
                required
            >

        </div>

        <!-- REMEMBER -->
        <div class="form-group text-left">

            <label class="text-light">

                <input
                    type="checkbox"
                    id="remember"
                >

                Ingat Saya

            </label>

        </div>

        <!-- BUTTON -->
        <button
            type="submit"
            id="btnLogin"
            class="btn btn-login btn-block"
        >

            <i class="fas fa-sign-in-alt mr-2"></i>
            Login

        </button>

    </form>

    <!-- GOOGLE LOGIN -->
    <div class="auth-divider mt-4">

        <span>
            ATAU LOGIN DENGAN EMAIL
        </span>

    </div>

    <a href="/auth/google" class="btn-google mb-4">

        <img
            src="https://www.svgrepo.com/show/475656/google-color.svg"
            alt="Google"
        >

        <span>
            Login dengan Google
        </span>

    </a>

    <hr>

    <div class="text-center">

        <a class="small" href="/forgetpassword">
            Lupa Password?
        </a>

    </div>

</div>

<!-- AXIOS -->

<!-- LOGIN SCRIPT -->
<!-- AXIOS -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>

// =======================================
// API URL
// =======================================
const API_URL = '{{ rtrim(config("app.url"), "/") }}/api';

// =======================================
// AUTO REDIRECT
// =======================================
@if(session('token'))
    window.location.href = '{{ route("admin.home") }}';
@endif

// =======================================
// LOGIN
// =======================================
document.getElementById('loginAdminForm')
.addEventListener('submit', async function(e){

    e.preventDefault();

    const btnLogin   = document.getElementById('btnLogin');
    const loginError = document.getElementById('loginError');

    const email    = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    const remember = document.getElementById('remember').checked;

    loginError.style.display = 'none';

    if(!email || !password){
        loginError.style.display = 'block';
        loginError.innerHTML     = 'Email dan password wajib diisi';
        return;
    }

    // LOADING
    btnLogin.innerHTML = `<i class="fas fa-spinner fa-spin mr-2"></i>Loading...`;
    btnLogin.disabled  = true;

    try{

        // 1. HIT API LOGIN
        const response = await axios.post(
            `${API_URL}/admin/auth/login`,
            { email, password },
            {
                headers:{
                    'Accept'      : 'application/json',
                    'Content-Type': 'application/json'
                }
            }
        );
        // ✅ Sesuaikan dengan response backend
const accessToken = response.data.access_token;
const userRole    = response.data.data?.roles?.[0] ?? 'admin'; // pakai spatie roles



        // 2. SIMPAN TOKEN KE SESSION LARAVEL
        await axios.post('/auth/store-token', {
            token : accessToken,
            role  : userRole,
        },{
            headers:{
                'X-CSRF-TOKEN' : '{{ csrf_token() }}',
                'Accept'       : 'application/json',
                'Content-Type' : 'application/json'
            }
        });

        // 3. REDIRECT BERDASARKAN ROLE
        if(userRole === 'guru'){
           window.location.href = '{{ route("guru.home") }}';
        } else {
            window.location.href = '{{ route("admin.home") }}';
        }

    }catch(error){

        loginError.style.display = 'block';

        if(error.code === 'ERR_NETWORK'){
            loginError.innerHTML = 'Backend tidak dapat diakses. Periksa koneksi server.';
        } else if(error.response){
            loginError.innerHTML = error.response.data.message ?? 'Email atau password salah';
        } else {
            loginError.innerHTML = 'Terjadi kesalahan, coba lagi.';
        }

    }finally{

        btnLogin.innerHTML = `<i class="fas fa-sign-in-alt mr-2"></i>Login`;
        btnLogin.disabled  = false;

    }

});

</script>

<!-- OTHER SCRIPT -->
<script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>

<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

<script src="{{ asset('assets/js/sb-admin-2.min.js') }}"></script>

</body>
</html>