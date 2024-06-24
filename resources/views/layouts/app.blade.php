<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Quản lý khu gửi xe</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <!-- Custom fonts for this template-->
  <link href="{{ asset('https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css')}}" rel="stylesheet" type="text/css">
  <script src="{{ asset('https://use.fontawesome.com/releases/v6.3.0/js/all.js')}}" crossorigin="anonymous"></script>
  <!-- Custom styles for this template-->
  <link href="{{ asset('css/style.css') }}" rel="stylesheet">
  <!-- Toastr JavaScript -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" rel="stylesheet">
  <!-- Toastr CSS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
  <link href="{{ asset('toastr-custom.css') }}" rel="stylesheet">
  <!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="sb-nav-fixed">
    <!-- Toastr initialization -->
    @include('toastr')
    <!-- Top Navbar -->
    @include('layouts.navbar')
  
    <div id="layoutSidenav">
        <!-- Sidebar -->
        @include('layouts.sidebar')
        <!-- End of Sidebar -->

        <!-- Main Content -->
        <div id="layoutSidenav_content">
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mt-3 mx-3 text-gray-800 text-gray-800">@yield('title')</h1>
            </div>
            @yield('contents')
            <!-- Footer -->
            @include('layouts.footer')
            <!-- End of Footer -->
        </div>
    </div>
 
  <!-- Bootstrap core JavaScript-->
  <script src="{{ asset('https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('js/scripts.js') }}"></script>
  <!-- Core plugin JavaScript-->
  <script src="{{ asset('https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js') }}"></script>
  @yield('scripts')
</body>
</html>
