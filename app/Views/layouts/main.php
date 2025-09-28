<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
$isAuth = !empty($_SESSION['uid']);
$title  = $title  ?? 'Gelinlik Paneli';
$active = $active ?? '';               // menü için
$breadcrumbs = $breadcrumbs ?? [];     // breadcrumb için
?><!doctype html>
<html lang="tr" data-bs-theme="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($title) ?></title>
  <link rel="stylesheet" href="/assets/vendor/coreui/coreui.min.css">
  <link rel="stylesheet" href="/assets/vendor/coreui/icons/free.min.css">
  <link rel="stylesheet" href="/assets/app.css">
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar sidebar-dark sidebar-fixed" id="sidebar">
    <?php include __DIR__.'/_sidebar.php'; ?>
  </div>

  <!-- Wrapper -->
  <div class="wrapper d-flex flex-column min-vh-100">

    <!-- Header -->
    <header class="header header-sticky p-0 mb-4">
      <?php include __DIR__.'/_header.php'; ?>
      <div class="container-fluid px-4">
        <?php if ($breadcrumbs): ?>
            <nav aria-label="breadcrumb" class="m-3">
              <ol class="breadcrumb my-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <?php foreach ($breadcrumbs as $i => $bc): ?>
                  <li class="breadcrumb-item<?= ($i === array_key_last($breadcrumbs) ? ' active' : '') ?>">
                    <?= htmlspecialchars($bc) ?>
                  </li>
                <?php endforeach; ?>
              </ol>
            </nav>
        <?php endif; ?>
      </div>
    </header>

    <!-- Content -->
    <div class="body flex-grow-1 px-3">
      <div class="container-lg">
        <?php $flash = \App\Core\Flash::all(); ?>
        <?php if ($flash): foreach ($flash as $type => $msg): ?>
          <div class="alert alert-<?= $type === 'success' ? 'success' : ($type==='error'?'danger':'info') ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($msg) ?>
            <button type="button" class="btn-close" data-coreui-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endforeach; endif; ?>
        <?= $content ?>
      </div>
    </div>

    <!-- Footer -->
    <?php include __DIR__.'/_footer.php'; ?>
  </div>

  <script src="/assets/vendor/coreui/coreui.bundle.min.js"></script>
  <script src="/assets/app.js"></script>
</body>
</html>
