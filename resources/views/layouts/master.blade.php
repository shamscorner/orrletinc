
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>AdminLTE 3 | Starter</title>

  <link rel="stylesheet" href="/css/app.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper" id="app">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">

    <!-- left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item" data-widget="pushmenu">
        <a href="#" class="nav-link"><i class="fa fa-bars"></i></a>
      </li>
    </ul> 

    <!-- SEARCH FORM -->
    <div class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" 
          v-model="search" 
          type="search" 
          placeholder="Search" 
          aria-label="Search"
          @keyup="searchText">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit" @click.prevent="searchText">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </div>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="brand-link">
      <img src="./img/company.png" alt="Orrlet Logo" class="brand-image img-circle elevation-3">
      <span class="brand-text font-weight-light">Orrlet LLC</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="./img/user.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">
            {{ Auth::user()->name }}
            <br>
            {{ Auth::user()->type }}
          </a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
            <li class="nav-item">
            <router-link to="/dashboard" active-class="active" exact class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt teal"></i>
              <p>
                Dashboard
              </p>
            </router-link>
          </li>
          
          @can('isAdmin') 
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cog orange"></i>
              <p>
                Management
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/users" class="nav-link" active-class="active" exact>
                  <i class="fas fa-users nav-icon green"></i>
                  <p>Employees</p>
                </router-link>
              </li>
            </ul>
          </li>
          @endcan

          <li class="nav-item">
            <router-link to="/profile" active-class="active" exact class="nav-link">
              <i class="nav-icon fas fa-th purple"></i>
              <p>
                Profile
              </p>
            </router-link>
          </li>
          
          @can('isAdmin')
          <li class="nav-item">
            <router-link to="/developer" active-class="active" exact class="nav-link">
              <i class="nav-icon fas fa-terminal yellow"></i>
              <p>
                Developer
              </p>
            </router-link>
          </li>
          @endcan

          <li class="nav-item">
            <a class="nav-link" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                <p>
                  <i class="nav-icon fas fa-power-off pink"></i>
                  {{ __('Logout') }}
                </p>
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <router-view></router-view>

        <vue-progress-bar></vue-progress-bar>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      Anything you want
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2019 <a href="https://facebook.com/shamscorner">Shamim</a>.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- this section for only authenticate user -->
@auth
<script>
  window.user = @json(auth()->user());
</script>
@endauth

<script src="/js/app.js"></script>

</body>
</html>
