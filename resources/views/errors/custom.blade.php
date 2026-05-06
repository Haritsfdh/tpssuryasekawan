<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name', 'TPS')}} | @yield('title')</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('adminlte') }}/dist/css/adminlte.min.css">
</head>
<body class="hold-transition lockscreen">
<div class="wrapper">
  <!-- Content Wrapper. Contains page content -->
  <div class="lockscreen-wrapper" style="max-width: 800px;">    
    <!-- Main content -->
    <div class="content" style="margin: 0 auto !important;margin-top:10% !important;">
      <div class="container">
        <div class="error-page" style="width: 100% !important;margin: auto 0 !important;">
          <h2 class="headline text-danger mr-5" style="font-size: 80px;">{{ $errCode ?? '500' }}</h2>
  
          <div class="error-content">
            <h3><i class="fas fa-exclamation-triangle text-danger"></i>{{ $errInfo ?? 'Oops! Ada yang salah dengan server.' }} </h3>
  
            <p>
              @if($errDetail)
              {!! $errDetail !!}
              @else
              Kami akan segera memperbaikinya.
              @endif
              <br>
              Silahkan kembali ke <a href="{{url()->previous()}}" style="color: blue !important;">halaman sebelumnya</a>, atau kembali ke <a href="/" style="color: blue;">Dashboard</a>.
            </p>
          </div>
        </div>
        <!-- /.error-page -->
      </div>
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->  
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('adminlte') }}/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('adminlte') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="{{ asset('adminlte') }}/dist/js/adminlte.min.js"></script>
</body>
</html>
