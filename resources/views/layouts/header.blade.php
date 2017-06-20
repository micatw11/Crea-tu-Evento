    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
 
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="/css/ionicons.min.css">
    <!-- Fonts -->
    <link href="/css/app.css" rel="stylesheet" type="text/css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/dist/css/AdminLTE.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="/dist/css/skins/_all-skins.min.css">

    <!-- fullCalendar 2.2.5
    <link rel="stylesheet" href="/plugins/fullcalendar/fullcalendar.min.css">
    <link rel="stylesheet" href="/plugins/fullcalendar/fullcalendar.print.css" media="print">
    -->
    <!-- iCheck 
    <link rel="stylesheet" href="/plugins/iCheck/square/blue.css">
    -->


    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Crea tu Evento') }}</title>
    <!--<title> @yield ('title') | Crea tu Evento</title>-->
    <style>
        html, body{background-color:#ecf0f5;}
    </style>
  <!-- Scripts -->
  <script>
      window.Laravel = <?php echo json_encode([
          'csrfToken' => csrf_token(),
      ]); ?>
  </script>