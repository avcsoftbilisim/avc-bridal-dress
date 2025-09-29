<?php $q = $q ?? ''; $scope = $scope ?? 'ongoing'; ?>
<div class="d-flex align-items-center justify-content-between mb-3">
  <h5 class="mb-0"><?= htmlspecialchars($title ?? 'Kiralamalar') ?></h5>
  <div>
    <div class="dropdown">
      <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-coreui-toggle="dropdown">
        <?= $scope==='past' ? 'Geçmiş Kiralar' : ($scope==='future' ? 'Gelecek Kiralar' : 'Kiradaki Ürünler') ?>
      </button>
      <ul class="dropdown-menu dropdown-menu-end">
        <li><a class="dropdown-item" href="/rentals">Kiradaki Ürünler</a></li>
        <li><a class="dropdown-item" href="/rentals/past">Geçmiş Kiralar</a></li>
        <li><a class="dropdown-item" href="/rentals/future">Gelecek Kiralar</a></li>
      </ul>
    </div>
  </div>
</div>

<div class="card mb-3">
  <div class="card-body">
    <form method="get" class="row g-2">
      <div class="col-sm-4">
        <input class="form-control" name="q" value="<?=htmlspecialchars($q)?>" placeholder="Müşteri/Telefon/Ürün/Barkod ara">
      </div>
      <div class="col-auto">
        <button class="btn btn-outline-secondary">Filtrele</button>
      </div>
    </form>
  </div>
</div>

<div class="card">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead>
        <tr>
          <th>Sıra No</th>
          <th>Müşteri Adı</th>
          <th>Telefonu</th>
          <th>Ürün Adı</th>
          <th>Alış Tarihi</th>
          <th>Teslim Edilmesi Gereken Tarih</th>
          <th class="text-end">Kapora</th>
          <th class="text-end">Toplam Ücret</th>
          <?php if ($scope==='ongoing'): ?>
            <th class="text-end">İşlem</th>
          <?php endif; ?>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($items)): ?>
          <tr><td colspan="<?= $scope==='ongoing' ? 9 : 8 ?>">
            <div class="alert alert-danger mb-0">Henüz bir şey eklenmemiş!</div>
          </td></tr>
        <?php else: $i = ($page-1)*$limit; foreach ($items as $r): $i++; ?>
          <tr>
            <td><?= $i ?></td>
            <td><?= htmlspecialchars($r['customer_name'] ?? '-') ?></td>
            <td><?= htmlspecialchars($r['phone'] ?? '-') ?></td>
            <td><?= htmlspecialchars($r['product_name'] ?? '-') ?></td>
            <td><?= !empty($r['start_date']) ? date('d.m.Y', strtotime($r['start_date'])) : '-' ?></td>
            <td><?= !empty($r['due_date'])   ? date('d.m.Y', strtotime($r['due_date']))   : '-' ?></td>
            <td class="text-end"><?= number_format((float)($r['deposit'] ?? 0),2,',','.') ?></td>
            <td class="text-end"><?= number_format((float)($r['total_price'] ?? 0),2,',','.') ?></td>
            <?php if ($scope==='ongoing'): ?>
            <td class="text-end">
              <button class="btn btn-sm btn-success" data-return="<?=$r['id']?>">Teslim Al</button>
            </td>
            <?php endif; ?>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
(() => {
  document.addEventListener('click', async (e) => {
    const btn = e.target.closest('[data-return]');
    if (!btn) return;
    const id = btn.getAttribute('data-return');
    if (!confirm('Bu kiralamayı teslim al (dönüş) olarak işaretlemek istiyor musunuz?')) return;
    const r = await fetch(`/rentals/${id}/return`, { method: 'POST', headers: { 'X-Requested-With':'fetch' }});
    try {
      const j = await r.json();
      if (j.ok) location.reload();
      else alert('İşlem başarısız.');
    } catch (_) { alert('İşlem başarısız.'); }
  });
})();
</script>