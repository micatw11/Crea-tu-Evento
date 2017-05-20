<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.header')
</head>
<body class="hold-transition skin-blue sidebar-mini">
    <div id="app">

            @include('layouts.panel_config')
            <div class="control-sidebar-bg"></div>
    </div>
    @yield ('scripts')
    @include('layouts.scripts')
</body>
</html>


