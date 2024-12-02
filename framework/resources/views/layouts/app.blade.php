<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset(App\Setting::first()->company) ? App\Setting::first()->company : config('app.name') }}</title>
    @php 
  $setting = \App\setting::first();
  @endphp 
  @if($setting && $setting->favicon)
    <link rel="icon" type="image/x-icon" sizes="32x32" href="{{ asset('uploads/' . $setting->favicon) }}">
    @else

    <link rel="icon" type="img/png" sizes="32x32" href="{{ asset('assets/1x/favicon.png') }}">
@endif
    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">

    {{-- PNotify  --}}
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/pnotify.custom.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dist/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/iCheck/all.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/default.css') }}">


    <style type="text/css">
        .login-box, .register-box{
            margin: 6% auto;
        }
        .sticky-box{
        position: sticky;
    }
    </style>
</head>
<body class="hold-transition login-page">
    <div id="app">

    <main class="content">
        @yield('content')
    </main>
    </div>
    <script src="{{ asset('assets/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('assets/bower_components/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
      $.widget.bridge('uibutton', $.ui.button);
    </script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('assets/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    {{-- PNotify --}}
    <script src="{{ asset('assets/js/pnotify.custom.min.js') }}" type="text/javascript"></script>

    <script src="{{ asset('assets/plugins/iCheck/icheck.min.js') }}"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
    });
     @if(session('flash_message'))
      new PNotify({
              title: ' Success!',
              text: "{{ session('flash_message') }}",
              type: 'success',
              delay: 3000,
              nonblock: {
                nonblock: true
              }
          });
    @endif
    @if(session('flash_message_error'))
      new PNotify({
              title: ' Warning!',
              text: "{{ session('flash_message_error') }}",
              type: 'warning',
              delay: 3000,
              nonblock: {
                nonblock: true
              }
          });
    @endif
  });
</script>
</body>
</html>
