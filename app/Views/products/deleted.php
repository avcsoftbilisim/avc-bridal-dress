<?php $q = $q ?? ''; ?>
<div class="card mb-4">
  <div class="card-body">
    <form method="get" class="d-flex gap-2 flex-wrap align-items-center">
      <input name="q" value="<?=htmlspecialchars($q)?>" class="form-control" style="max-width:260px" placeholder="Ürün adı veya barkod">
      <select class="form-select" style="max-width:220px" disabled>
        <option>— Filtre (opsiyonel) —</option>
      </select>
      <div class="form-check ms-1" title="Sadece görünüm; silinen liste için anlamı yok">
        <input type="checkbox" class="form-check-input" disabled id="low">
        <label for="low" class="form-check-label">Stoku azalan ürünler</label>
      </div>
      <button class="btn btn-outline-secondary ms-auto">Filtrele</button>
    </form>
  </div>
</div>

<div class="card">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead>
        <tr>
          <th>Başlık</th>
          <th>Barkod</th>
          <th class="text-end">Alış Fiyatı</th>
          <th class="text-end">Satış Fiyatı</th>
          <th class="text-end">KDV Oranı</th>
          <th class="text-center">KDV Dahil</th>
          <th class="text-end">Kira Fiyatı</th>
          <th class="text-end">Stok</th>
          <th class="text-end">Kiraya Verildi</th>
          <th class="text-end">Silinme</th>
          <th class="text-end">İşlem</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($items)): ?>
          <tr><td colspan="11">
            <div class="d-flex justify-content-center py-4">
              <div class="spinner-border" role="status"><span class="visually-hidden">Yükleniyor...</span></div>
            </div>
          </td></tr>
          <tr><td colspan="11">
            <div class="alert alert-danger mb-0">Henüz bir şey eklenmemiş!</div>
          </td></tr>
        <?php else: foreach ($items as $r): ?>
          <tr>
            <td><?=htmlspecialchars($r['name'] ?? '-')?></td>
            <td><?=htmlspecialchars($r['barcode'] ?? '-')?></td>
            <td class="text-end"><?=number_format((float)($r['buy_price'] ?? 0),2,',','.')?></td>
            <td class="text-end"><?=number_format((float)($r['sale_price'] ?? 0),2,',','.')?></td>
            <td class="text-end"><?=number_format((float)($r['vat_rate'] ?? 0),2,',','.')?></td>
            <td class="text-center"><?= !empty($r['vat_included']) ? 'Evet' : 'Hayır' ?></td>
            <td class="text-end"><?=number_format((float)($r['rent_price'] ?? 0),2,',','.')?></td>
            <td class="text-end"><?= (int)($r['stock'] ?? 0) ?></td>
            <td class="text-end"><?= (int)($r['usage_count'] ?? 0) ?></td>
            <td class="text-end">
              <?= !empty($r['deleted_at']) ? date('d.m.Y H:i', strtotime($r['deleted_at'])) : '-' ?>
            </td>
            <td class="text-end">
              <div class="btn-group">
                <button class="btn btn-sm btn-success" data-act="restore" data-id="<?=$r['id']?>">Geri Al</button>
                <button class="btn btn-sm btn-outline-danger" data-act="purge" data-id="<?=$r['id']?>">Kalıcı Sil</button>
              </div>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
(() => {
  function post(url){
    return fetch(url, {method:'POST', headers:{'X-Requested-With':'fetch'}}).then(r=>r.json());
  }
  document.addEventListener('click', async (e)=>{
    const btn = e.target.closest('[data-act]');
    if(!btn) return;
    const id  = btn.getAttribute('data-id');
    const act = btn.getAttribute('data-act');

    if (act === 'restore') {
      if (!confirm('Bu ürünü geri almak istiyor musunuz?')) return;
      const j = await post(`/products/deleted/${id}/restore`);
      if (j.ok) location.reload(); else alert('İşlem başarısız.');
    }
    if (act === 'purge') {
      if (!confirm('Kalıcı silinecek. Bu işlem geri alınamaz!')) return;
      const j = await post(`/products/deleted/${id}/purge`);
      if (j.ok) location.reload(); else alert('İşlem başarısız.');
    }
  }, false);
})();
</script>