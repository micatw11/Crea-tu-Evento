<!-- Left side column. conta ins the logo and sidebar -->
  <aside class="main-sidebar container" >
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>{{ Auth::user()->name }}</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MENU</li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>Ejemplo componenete</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            
               <router-link tag="li" to="/about">
                    <a><i class="fa fa-circle-o"></i>
                    About
                    </a>
                </router-link>

               <router-link tag="li" to="/">
                    <a><i class="fa fa-circle-o"></i>
                    home
                    </a>
                </router-link>
           
             
               <router-link tag="li" to="/header">
                    <a><i class="fa fa-circle-o"></i>
                    Header
                    </a>
                </router-link>
           
              
               <router-link tag="li" to="/calendario">
                    <a><i class="fa fa-circle-o"></i>
                    Calendario
                    </a>
                </router-link>
            
            <li>
                <a href="../index2.html">
                    <i class="fa fa-circle-o"></i> 
                    Dashboard v2
                </a>
            </li>
          </ul>
        </li>
          <a href="{{ url('/calendario') }}">
            <i class="fa fa-calendar"></i> <span>Calendar</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-red">3</small>
              <small class="label pull-right bg-blue">17</small>
            </span>
          </a>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
