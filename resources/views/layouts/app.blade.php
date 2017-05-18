<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.header')
</head>
<body>
  <div id="app">
    @include('layouts.barra')
    <!-- Authentication Links -->
    @if (Auth::guest())
        <li><a href="{{ url('/login') }}">Login</a></li>
        <li><a href="{{ url('/register') }}">Register</a></li>
    @else
        
            @include('layouts.barra_lateral')
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
               @include('layouts.paginas_previas')
               <section class="content">
                <!-- Content Header (Page header) 
                
                  <div id="app">-->
                        @yield ('content')
                         <section class="section">
                            
                            <div class="container">

                                <router-view></router-view>

                            </div>

                        </section>          
                  
                </section>
            </div>
          
          @include('layouts.footer')
          <!-- ./wrapper -->
        
    @endif
        
     </div>
    <!-- Scripts -->
    @include('layouts.scripts')
    @yield ('scripts')
</body>
</html>


