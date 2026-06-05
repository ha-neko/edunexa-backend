<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>EDUNEXA | @yield('title')</title>
    <link rel="icon" href="{{ asset('img/undraw_rocket.svg') }}">
    <link href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="{{ asset('assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/custom_main.css') }}" rel="stylesheet">
    <style>
        .modal-title { color: #fff !important; }
    </style>
</head>

<body id="page-top">
<div id="wrapper">

    {{-- Sidebar Guru --}}
    @include('component.layouts.guru.sidebar')

    <div id="content-wrapper" class="d-flex flex-column min-vh-100">
        <div id="content" class="flex-grow-1">

            @include('component.layouts.template.header')

            <div class="container-fluid">
                @yield('content') {{-- ✅ Samakan dengan admin --}}
            </div>

        </div>

        @include('component.layouts.template.footer')
    </div>

</div>

{{-- ✅ Hapus duplikat jQuery & Bootstrap --}}
<script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('assets/js/sb-admin-2.min.js') }}"></script>

@yield('scripts')

<script>
    const sidebar   = document.querySelector('.sidebar');
    const toggleBtn = document.getElementById('sidebarToggleCustom');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('sidebar-mini');
            const icon = toggleBtn.querySelector('i');
            icon.classList.toggle('fa-angle-left');
            icon.classList.toggle('fa-angle-right');
        });
    }
</script>

</body>
</html>