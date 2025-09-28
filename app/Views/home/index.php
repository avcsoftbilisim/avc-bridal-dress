<?php /** @var array $stats */ ?>
<div class="row g-4">
  <div class="col-sm-6 col-xl-3">
    <div class="card text-white bg-success">
      <div class="card-body pb-0">
        <div class="fs-6 fw-semibold">Gelir</div>
        <div class="fs-2"><?php echo number_format($stats['income'],2,',','.'); ?> TL</div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card text-white bg-primary">
      <div class="card-body pb-0">
        <div class="fs-6 fw-semibold">Gider</div>
        <div class="fs-2"><?php echo number_format($stats['expense'],2,',','.'); ?> TL</div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card text-white bg-warning">
      <div class="card-body pb-0">
        <div class="fs-6 fw-semibold">Kâr</div>
        <div class="fs-2"><?php echo number_format($stats['profit'],2,',','.'); ?> TL</div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card text-white bg-info">
      <div class="card-body pb-0">
        <div class="fs-6 fw-semibold">Yeni İşlem</div>
        <div class="fs-2"><?php echo (int)$stats['new_ops']; ?></div>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4">
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header"><strong>Bugünkü Provalar</strong></div>
      <div class="card-body">
        <p class="text-body-secondary mb-0">Bugün hiç prova yok</p>
      </div>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header"><strong>Yaklaşan Borçlar</strong></div>
      <div class="card-body">
        <p class="text-body-secondary mb-0">Hiç yaklaşan borcunuz yok!</p>
      </div>
    </div>
  </div>
</div>
