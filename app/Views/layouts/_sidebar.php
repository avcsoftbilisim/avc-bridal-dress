<?php /* $active değerini controllers'tan gönderiyoruz */ ?>

<style>
  /* Alt menüler küçük ve içe girintili */
  .sidebar .sidebar-nav .nav-group-items{
    margin-left: .25rem;
    padding-left: .5rem;
    border-left: 1px dashed rgba(255,255,255,.15);
  }
  .sidebar .sidebar-nav .nav-group-items .nav-link{
    font-size: .875rem !important;
    padding: .35rem .75rem .35rem 3rem !important; /* içe girinti */
    color: rgba(255,255,255,.80);
  }
  .sidebar .sidebar-nav .nav-group-items .nav-link .nav-icon{
    font-size: 1.35rem;
    margin-right: 1.35rem;
    opacity: .85;
  }
  .sidebar .sidebar-nav .nav-group-items .nav-link:hover{
    background: rgba(255,255,255,.04);
    color:#fff;
  }
  .sidebar .sidebar-nav .nav-group-items .nav-link.active{
    background: rgba(98,173,255,.15);
    color:#fff;
  }
</style>

<div class="sidebar-header border-bottom">
    <div class="sidebar-brand">
        <span class="sidebar-brand-text fw-bold">GP</span>
    </div>
</div>

<ul class="sidebar-nav" data-coreui="navigation" data-simplebar>
  <li class="nav-item">
    <a class="nav-link <?= $active==='dashboard'?'active':'' ?>" href="/">
      <i class="nav-icon cil-speedometer"></i> Anasayfa
    </a>
  </li>

  <li class="nav-title">Modüller</li>
    <?php
    // aktiflik için controller'dan gelecek değerler
    $active     = $active     ?? '';   // üst grup
    $active_sub = $active_sub ?? '';   // alt sekme
    $isCari     = ($active === 'cari');
    ?>

    <li class="nav-group"> <!-- show: ilk açılışta açık gelsin -->
        <a class="nav-link nav-group-toggle" href="#">
            <i class="nav-icon cil-address-book"></i>
            <span>Cari Yönetimi</span>
        </a>
        <ul class="nav-group-items">
            <li class="nav-item">
            <a class="nav-link" href="/customers/create">
                <i class="nav-icon cil-plus"></i><span>Yeni Cari</span>
            </a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="/customers">
                <i class="nav-icon cil-list"></i><span>Cari Listele</span>
            </a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="/customers/transactions">
                <i class="nav-icon cil-library"></i><span>Cari İşlemleri</span>
            </a>
            </li>
        </ul>
    </li>
  <li class="nav-item"><a class="nav-link <?= $active==='fittings'?'active':'' ?>" href="/fittings"><i class="nav-icon cil-calendar"></i> Provalar</a></li>
  <li class="nav-item"><a class="nav-link <?= $active==='rentals'?'active':'' ?>" href="/rentals"><i class="nav-icon cil-library"></i> Kiralamalar</a></li>
  <li class="nav-item"><a class="nav-link <?= $active==='reports'?'active':'' ?>" href="/reports"><i class="nav-icon cil-chart-line"></i> Raporlar</a></li>

  <li class="nav-title">Hesap</li>
  <li class="nav-item"><a class="nav-link" href="/logout"><i class="nav-icon cil-account-logout"></i> Çıkış Yap</a></li>
</ul>

<script>
  (function(){
    var path = location.pathname;
    document.querySelectorAll('.nav-group-items .nav-link').forEach(function(a){
      if (a.getAttribute('href') === path) {
        a.classList.add('active');
        var grp = a.closest('.nav-group');
        grp && grp.classList.add('show');
      }
    });
  })();
</script>
