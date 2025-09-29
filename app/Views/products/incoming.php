<?php if (!isset($items)) $items = []; ?>
<div class="card mb-4">
  <div class="card-body">
    <div class="d-flex gap-2 flex-wrap align-items-center">
      <input class="form-control" style="max-width:220px" placeholder="Ürün adı veya barkod">
      <select class="form-select" style="max-width:220px"><option>Tüm Markalar</option></select>
      <select class="form-select" style="max-width:220px"><option>Tüm Kategoriler</option></select>
      <select class="form-select" style="max-width:120px">
        <option>7</option><option>15</option><option>30</option>
      </select>
      <div class="ms-auto">
        <button class="btn btn-primary" data-coreui-toggle="modal" data-coreui-target="#incomingModal">
          + Gelecek Ürün Ekle
        </button>
      </div>
    </div>
  </div>
</div>

<div class="card">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead>
        <tr>
          <th>Ürün Adı</th>
          <th>Kategori</th>
          <th>Marka</th>
          <th>Barkod</th>
          <th class="text-end">Alış Fiyatı</th>
          <th class="text-end">Satış Fiyatı</th>
          <th class="text-end">Kira Fiyatı</th>
          <th class="text-end">Stok</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!$items): ?>
          <tr>
            <td colspan="8">
              <div class="alert alert-danger mb-0">Henüz bir şey eklenmemiş!</div>
            </td>
          </tr>
        <?php else: foreach ($items as $r): ?>
          <tr>
            <td><?=htmlspecialchars($r['name'])?></td>
            <td><?=htmlspecialchars($r['category_id'] ?? '-')?></td>
            <td><?=htmlspecialchars($r['brand_id'] ?? '-')?></td>
            <td><?=htmlspecialchars($r['barcode'] ?? '-')?></td>
            <td class="text-end"><?=number_format((float)$r['buy_price'],2,',','.')?></td>
            <td class="text-end"><?=number_format((float)$r['sale_price'],2,',','.')?></td>
            <td class="text-end"><?=number_format((float)$r['rent_price'],2,',','.')?></td>
            <td class="text-end"><?= (int)$r['expected_stock'] ?></td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
  <?php /* basit sayfalama yeri istersek eklenir */ ?>
</div>

<!-- Modal -->
<div class="modal fade" id="incomingModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <form class="modal-content" id="incomingForm">
      <div class="modal-header">
        <h5 class="modal-title">Ürün Kaydı</h5>
        <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Ürün Adı *</label>
            <input name="name" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Barkod</label>
            <input name="barcode" class="form-control">
          </div>

          <div class="col-md-6">
            <label class="form-label">Birim</label>
            <select name="unit" class="form-select">
              <option value="Adet">Adet</option>
              <option value="Set">Set</option>
              <option value="Paket">Paket</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Gelecek Stok *</label>
            <input name="expected_stock" type="number" min="0" value="0" class="form-control" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Marka</label>
            <select name="brand_id" class="form-select">
              <option value="">—</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Kategori</label>
            <select name="category_id" class="form-select">
              <option value="">—</option>
            </select>
          </div>

          <div class="col-md-12">
            <label class="form-label">Alış Fiyatı</label>
            <input name="buy_price" type="number" step="0.01" min="0" class="form-control" value="0">
          </div>

          <div class="col-md-4">
            <label class="form-label">Satış Fiyatı</label>
            <input name="sale_price" type="number" step="0.01" min="0" class="form-control" value="0">
          </div>
          <div class="col-md-4">
            <label class="form-label">Kira Fiyatı</label>
            <input name="rent_price" type="number" step="0.01" min="0" class="form-control" value="0">
          </div>
          <div class="col-md-4">
            <label class="form-label">KDV Oranı</label>
            <input name="vat_rate" type="number" step="0.01" min="0" max="40" class="form-control" value="0">
          </div>

          <div class="col-12 d-flex align-items-center gap-2">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="showRent" name="show_rent_on_barcode">
              <label class="form-check-label" for="showRent">Kira Fiyatı Barkodda Görünsün</label>
            </div>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-coreui-dismiss="modal">İptal</button>
        <button type="submit" class="btn btn-primary">Kaydet</button>
      </div>
    </form>
  </div>
</div>

<script>
(() => {
  const form = document.getElementById('incomingForm');
  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const fd = new FormData(form);
    const res = await fetch('/products/incoming', { method:'POST', body: fd, headers:{'X-Requested-With':'fetch'} });
    const j = await res.json();
    if (j.ok) {
      location.reload();
    } else {
      alert('Hata:\n' + Object.values(j.errors || {}).join('\n'));
    }
  }, false);
})();
</script>