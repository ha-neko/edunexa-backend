<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>EDUNEXA | @yield('title')</title>
    <link rel="icon" href="{{ asset('img/undraw_rocket.svg') }}">

    <!-- Custom fonts for this template-->
    <link href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
<link href="{{ asset('assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/custom_main.css') }}" rel="stylesheet">
    <style>
       .modal-title {
            color: #fff !important; 
        }
    </style>


</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
            @include('component.layouts.admin.sidebar')
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column min-vh-100">

                <div id="content" class="flex-grow-1">

                    @include('component.layouts.template.header')

                    <div class="container-fluid">
                        @yield('content')
                    </div>

                </div>
                

                <!-- /.container-fluid -->
               
            <!-- End of Main Content -->

            <!-- Footer -->
            @include('component.layouts.template.footer')
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->


   

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('assets/js/sb-admin-2.min.js') }}"></script>

    <!-- Page level plugins -->
    <script src="{{ asset('assets/vendor/chart.js/Chart.min.js') }}"></script>

    <!-- Page level custom scripts -->
    @yield('script')
    <script>

    const sidebar = document.querySelector('.sidebar');
    const toggleBtn = document.getElementById('sidebarToggleCustom');

    toggleBtn.addEventListener('click', () => {

        sidebar.classList.toggle('sidebar-mini');

        const icon = toggleBtn.querySelector('i');

        if(sidebar.classList.contains('sidebar-mini')){
            icon.classList.remove('fa-angle-left');
            icon.classList.add('fa-angle-right');
        }else{
            icon.classList.remove('fa-angle-right');
            icon.classList.add('fa-angle-left');
        }

});

</script>

</body>

</html>