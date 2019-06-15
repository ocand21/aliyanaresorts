<!DOCTYPE Html>
<html>

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
    <meta name="author" content="Łukasz Holeczek">
    <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
    <meta charset="utf-8" name="csrf-token" content="{{ csrf_token() }}">
    <title>Aliyana Hotel & Resorts - Admin</title>

    <link rel="stylesheet" href="/css/app.css">

</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show">
    <div id="app">
        {{-- Header --}}
        <header class="app-header navbar">
            <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="#">
                <img class="navbar-brand-full" src="/img/icon/logo.png" width="89" height="40" alt="CoreUI Logo">
                <img class="navbar-brand-minimized" src="img/brand/sygnet.svg" width="30" height="30" alt="CoreUI Logo">
            </a>
            <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
                <span class="navbar-toggler-icon"></span>
            </button>
            <ul class="nav navbar-nav d-md-down-none">
                <li class="nav-item px-3">
                    <a class="nav-link" href="#">Dashboard</a>
                </li>
                <li class="nav-item px-3">
                    <a class="nav-link" href="#">Users</a>
                </li>
                <li class="nav-item px-3">
                    <a class="nav-link" href="#">Settings</a>
                </li>
            </ul>
            <ul class="nav navbar-nav ml-auto">
                <li class="nav-item d-md-down-none">
                    <a class="nav-link" href="#">
                        <i class="fa fa-bell"></i>
                        <span class="badge badge-pill badge-danger">5</span>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <img class="img-avatar" src="/img/icon/user.png" alt="admin@bootstrapmaster.com">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#">
                            <i class="fa fa-user"></i> Profile</a>
                        <a class="dropdown-item" onclick="event.preventDefault();
                  document.getElementById('logout-form').submit();">
                            <i class="fa fa-lock"></i> Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
                <li class="nav-item d-md-down-none">
                    <strong>{{ Auth::user()->name}}</strong>
                </li>
            </ul>
        </header>
        {{-- --}}

        {{-- Body --}}
        <div class="app-body">
            <div class="sidebar">
                <nav class="sidebar-nav">
                    <ul class="nav">
                        <li class="nav-item">
                            <router-link to="/admin/dashboard" class="nav-link" href="main.html">
                                <i class="nav-icon fa fa-home"></i> Dashboard
                                <span class="badge badge-info">NEW</span>
                            </router-link>
                        </li>
                        {{-- <li class="nav-title">Master Data</li> --}}
                        <li class="nav-item nav-dropdown">
                            <a class="nav-link nav-dropdown-toggle" href="#">
                                <i class="nav-icon fa fa-hotel"></i> Reservasi</a>
                            <ul class="nav-dropdown-items" style="margin-left: 10px">
                              <li class="nav-item">
                                  <router-link to="/admin/bookings" class="nav-link" href="colors.html">
                                      <i class="nav-icon fa fa-key"></i> Booking
                                  </router-link>
                              </li>
                              <li class="nav-item">
                                  <router-link to="/admin/check-in" class="nav-link" href="colors.html">
                                      <i class="nav-icon fa fa-bed"></i> Check-in
                                  </router-link>
                              </li>
                            </ul>
                        </li>

                        <li class="nav-item nav-dropdown">
                            <a class="nav-link nav-dropdown-toggle" href="#">
                                <i class="nav-icon fa fa-wallet"></i> Payment</a>
                            <ul class="nav-dropdown-items" style="margin-left: 10px">
                              <li class="nav-item">
                                  <router-link to="/admin/payment/tagihan" class="nav-link" href="colors.html">
                                      <i class="nav-icon fa fa-money-bill"></i> Tagihan
                                  </router-link>
                              </li>
                            </ul>
                        </li>
                        <li class="nav-item nav-dropdown">
                            <a class="nav-link nav-dropdown-toggle" href="#">
                                <i class="nav-icon fa fa-briefcase"></i> Master Data</a>
                            <ul class="nav-dropdown-items" style="margin-left: 10px">
                              <li class="nav-item">
                                  <router-link to="/admin/kamar" class="nav-link" href="colors.html">
                                      <i class="nav-icon fa fa-bed"></i> Kamar
                                  </router-link>
                              </li>
                              <li class="nav-item">
                                  <router-link to="/admin/fasilitas" class="nav-link" href="colors.html">
                                      <i class="nav-icon fa fa-glass-cheers"></i> Fasilitas
                                  </router-link>
                              </li>
                              <li class="nav-item">
                                  <router-link to="/admin/departemen" class="nav-link" href="colors.html">
                                      <i class="nav-icon fa fa-users"></i> Departemen
                                  </router-link>
                              </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <router-link to="/admin/staff" class="nav-link" href="colors.html">
                                <i class="nav-icon fa fa-users"></i> Staff
                            </router-link>
                        </li>
                        <li class="nav-title">Konfigurasi Website</li>
                        <li class="nav-item">
                            <router-link to="/admin/pengaturan" class="nav-link" href="colors.html">
                                <i class="nav-icon fa fa-wrench"></i> Pengaturan
                            </router-link>
                        </li>

                    </ul>
                </nav>
                <button class="sidebar-minimizer brand-minimizer" type="button"></button>
            </div>
            {{-- --}}

            <main class="main">
                {{-- Konten --}}

                <vue-progress-bar></vue-progress-bar>
                <router-view></router-view>
            </main>

        </div>
    </div>
    <footer class="app-footer">
        <div>
            <a href="https://coreui.io/pro/">CoreUI Pro</a>
            <span>© 2018 creativeLabs.</span>
        </div>
        <div class="ml-auto">
            <span>Powered by</span>
            <a href="https://coreui.io/pro/">CoreUI Pro</a>
        </div>
    </footer>
    <script type="text/javascript" src="/js/app.js"></script>
</body>

</html>
