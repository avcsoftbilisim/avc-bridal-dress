<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
$isAuth = isset($_SESSION['uid']);
?><!doctype html>
<html lang="tr" data-bs-theme="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gelinlik Paneli</title>
  <link rel="stylesheet" href="/assets/vendor/coreui/coreui.min.css">
  <link rel="stylesheet" href="/assets/vendor/coreui/icons/free.min.css">
  <style>.sidebar-brand-text{font-weight:700;font-size:1.05rem}</style>
</head>
<body>
  <div class="sidebar sidebar-dark sidebar-fixed" id="sidebar">
    <div class="sidebar-brand d-none d-md-flex align-items-center">
      <svg class="sidebar-brand-narrow" width="32" height="32" aria-hidden="true"></svg>
      <span class="sidebar-brand-text ms-2">Gelinlik Paneli</span>
    </div>
    <ul class="sidebar-nav" data-coreui="navigation" data-simplebar>
      <li class="nav-item"><a class="nav-link" href="/"><i class="nav-icon cil-speedometer"></i> Anasayfa</a></li>
      <li class="nav-item"><a class="nav-link" href="/customers"><i class="nav-icon cil-people"></i> Müşteriler</a></li>
      <li class="nav-item"><a class="nav-link" href="/fittings"><i class="nav-icon cil-calendar"></i> Provalar</a></li>
      <li class="nav-item"><a class="nav-link" href="/rentals"><i class="nav-icon cil-library"></i> Kiralamalar</a></li>
      <li class="nav-item"><a class="nav-link" href="/reports"><i class="nav-icon cil-chart-line"></i> Raporlar</a></li>
      <?php if($isAuth): ?>
        <li class="nav-title">Hesap</li>
        <li class="nav-item"><a class="nav-link" href="/logout"><i class="nav-icon cil-account-logout"></i> Çıkış Yap</a></li>
      <?php else: ?>
        <li class="nav-item"><a class="nav-link" href="/login"><i class="nav-icon cil-account-login"></i> Giriş</a></li>
      <?php endif; ?>
    </ul>
    <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
  </div>

  <div class="wrapper d-flex flex-column min-vh-100 bg-body-tertiary">
    <header class="header header-sticky mb-4">
      <div class="container-fluid">
        <button class="header-toggler px-md-0 me-md-3" id="sidebarToggle" type="button">
          <i class="icon cil-menu"></i>
        </button>
        <a class="header-brand d-md-none" href="/">Gelinlik Paneli</a>
        <ul class="header-nav ms-auto me-4">
          <?php if($isAuth): ?>
            <li class="nav-item"><span class="nav-link">Merhaba <?php echo htmlspecialchars($_SESSION['name']); ?></span></li>
          <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="/login">Giriş</a></li>
          <?php endif; ?>
        </ul>
      </div>
      <div class="header-divider"></div>
      <div class="container-fluid">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb my-0">
            <li class="breadcrumb-item"><a href="/">Anasayfa</a></li>
          </ol>
        </nav>
      </div>
    </header>

    <div class="body flex-grow-1 px-3">
      <div class="container-lg">
        <?php echo $content; ?>
      </div>
    </div>

    <footer class="footer">
      <div><a href="/">Gelinlik Paneli</a> © <?php echo date('Y'); ?>.</div>
      <div class="ms-auto">Made with CoreUI</div>
    </footer>
  </div>

  <script src="/assets/vendor/coreui/coreui.bundle.min.js"></script>
  <script>
    (function(){
      const sidebarEl = document.getElementById('sidebar');
      if (sidebarEl && window.coreui) new coreui.Sidebar(sidebarEl);
      const btn = document.getElementById('sidebarToggle');
      if (btn && sidebarEl && window.coreui) btn.addEventListener('click', function(){
        coreui.Sidebar.getInstance(sidebarEl).toggle();
      });
    })();
  </script>
</body>
</html>
