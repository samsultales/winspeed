<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <!-- Messages Dropdown Menu -->
    @if ( in_array(Session::get('auth')['key_id_unit_kerja'], ['0', '1'])) 
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-envelope"></i>
        <span class="badge badge-danger navbar-badge vcount"></span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header vcount2"></span>
        <div class="dropdown-divider"></div>
        <div id="target">
          <!-- all divs will append here -->
        </div>
        <a href="{{ route('notif.index') }}" class="dropdown-item dropdown-footer">See All Notifications</a>
      </div>
    </li>
    @endif
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-user"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <a href="#" class="dropdown-item">
          <!-- Message Start -->
          <div class="media">
            <img src="{{ asset('assets/AdminLTE-3.2.0/dist/img/user2-160x160_bw.png') }}" alt="User Avatar" class="img-size-50 mr-3 img-circle">
            <div class="media-body">
              <h3 class="dropdown-item-title">
                {{Session::get('auth')['name']}}
                <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
              </h3>
              <p class="text-sm">Call me whenever you can...</p>
            </div>
          </div>
          <!-- Message End -->
        </a>
        <div class="dropdown-divider"></div>
        <div class="dropdown-item">
          <div class="row">
            <a class="btn btn-app bg-success">
              <i class="fas fa-user-tie"></i> Profile
            </a>
            <a class="btn btn-app bg-danger" href="logout">
              <i class="fas fa-power-off"></i> Logout
            </a>
          </div>
        </div>
        <div class="dropdown-divider"></div>
      </div>
    </li>
   
  </ul>
</nav>