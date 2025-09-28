<div class="container-fluid border-bottom p-3">

    <button class="header-toggler" type="button"
        aria-controls="sidebar" aria-label="Toggle sidebar"
        onclick="window.App.toggleSidebar()">
    <i class="cil-menu"></i>
    </button>

  <ul class="header-nav d-none d-lg-flex">
    <li class="nav-item"><a class="nav-link" href="/">Dashboard</a></li>
    <li class="nav-item"><a class="nav-link" href="/customers">Users</a></li>
    <li class="nav-item"><a class="nav-link" href="/settings">Settings</a></li>
  </ul>

  <ul class="header-nav ms-auto">
    <li class="nav-item"><a class="nav-link" href="#"><i class="cil-bell"></i></a></li>
    <li class="nav-item"><a class="nav-link" href="#"><i class="cil-list-rich"></i></a></li>
    <li class="nav-item"><a class="nav-link" href="#"><i class="cil-envelope-open"></i></a></li>
  </ul>

  <ul class="header-nav">
    <li class="nav-item dropdown">
      <a class="nav-link py-0" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
        <div class="avatar avatar-md"><img class="avatar-img" src="https://i.pravatar.cc/64" alt=""></div>
      </a>
      <div class="dropdown-menu dropdown-menu-end pt-0">
        <div class="dropdown-header bg-body-tertiary text-body-secondary fw-semibold py-2">Hesap</div>
        <a class="dropdown-item" href="/profile"><i class="cil-user me-2"></i> Profil</a>
        <a class="dropdown-item" href="/settings"><i class="cil-settings me-2"></i> Ayarlar</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="/logout"><i class="cil-account-logout me-2"></i> Çıkış Yap</a>
      </div>
    </li>
  </ul>
</div>