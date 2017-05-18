<!DOCTYPE html>
<html lang="en">
    <head>
       @include('layouts.header')
         <link rel="stylesheet" href="{{ asset('css/app.css') }}"></link>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @if (Auth::check())
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ url('/login') }}">Login</a>
                        <a href="{{ url('/register') }}">Register</a>
                    @endif
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    Eventos
                </div>
                @yield('content')
                
            </div>
        </div>
         <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
