<?php if (session_status() !== PHP_SESSION_ACTIVE) session_start(); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0">Ürün Yönetimi</h5>
  <div class="d-flex gap-2">
    <a href="#" class="btn btn-sm btn-dark">Detaylı Ürün Raporu</a>
    <button class="btn btn-sm btn-primary" data-coreui-toggle="modal" data-coreui-target="#productModal">
      + Yeni Ürün Ekle
    </button>
  </div>
</div>

<!-- Filtre barı -->
<div class="row g-2 mb-3">
  <div class="col-md-3">
    <input class="form-control" type="text" placeholder="Ürün adı veya barkod">
  </div>
  <div class="col-md-2">
    <select class="form-select">
      <option>Tüm Markalar</option>
      <?php foreach(($brands??[]) as $b): ?>
        <option value="<?= (int)$b['id'] ?>"><?= htmlspecialchars($b['name']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-2">
    <select class="form-select">
      <option>Tüm Kategoriler</option>
      <?php foreach(($categories??[]) as $c): ?>
        <option value="<?= (int)$c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-2">
    <select class="form-select">
      <option>7</option><option>25</option><option>50</option>
    </select>
  </div>
  <div class="col-md-3 d-flex align-items-center">
    <div class="form-check">
      <input class="form-check-input" id="lowStock" type="checkbox">
      <label class="form-check-label" for="lowStock">Stoku azalan ürünler</label>
    </div>
  </div>
</div>

<!-- Tablo -->
<div class="card">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>Görsel</th>
          <th>Ürün Adı</th>
          <th>Kategori</th>
          <th>Barkod</th>
          <th>Alış Fiyatı</th>
          <th>Satış Fiyatı</th>
          <th>Kira Fiyatı</th>
          <th>Stok</th>
          <th>Kaç Kez Kiraya Verildi</th>
          <th>Yıl</th>
          <th>Beden</th>
        </tr>
      </thead>
      <tbody>
      <?php if (empty($products)): ?>
        <tr>
          <td colspan="11">
            <div class="alert alert-danger mb-0">Henüz bir şey eklenmemiş!</div>
          </td>
        </tr>
      <?php else: foreach ($products as $p): ?>
        <tr>
          <td style="width:70px">
            <?php if(!empty($p['photo'])): ?>
              <img src="/uploads/products/<?= htmlspecialchars($p['photo']) ?>" class="rounded" style="width:56px;height:56px;object-fit:cover">
            <?php endif; ?>
          </td>
          <td><?= htmlspecialchars($p['name'] ?? '') ?></td>
          <td><?= htmlspecialchars($p['category_name'] ?? '') ?></td>
          <td><?= htmlspecialchars($p['barcode'] ?? '') ?></td>
          <td><?= number_format((float)($p['buy_price'] ?? 0),2,',','.') ?></td>
          <td><?= number_format((float)($p['sale_price'] ?? 0),2,',','.') ?></td>
          <td><?= number_format((float)($p['rent_price'] ?? 0),2,',','.') ?></td>
          <td><?= (int)($p['stock'] ?? 0) ?></td>
          <td><?= (int)($p['usage_count'] ?? 0) ?></td>
          <td><?= htmlspecialchars($p['year'] ?? '') ?></td>
          <td><?= htmlspecialchars($p['size'] ?? '') ?></td>
        </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>

  <?php if (!empty($products)): ?>
  <div class="card-footer text-center">
    <button class="btn btn-primary btn-sm">1</button>
  </div>
  <?php endif; ?>
</div>

<!-- Yeni Ürün Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <form id="productCreateForm" class="modal-content" enctype="multipart/form-data">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf??'') ?>">
      <div class="modal-header">
        <h5 class="modal-title">Ürün Kaydı</h5>
        <button class="btn-close" type="button" data-coreui-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Ürün Resmi</label>
          <input class="form-control" type="file" name="photo" accept=".jpg,.jpeg,.png,.webp">
        </div>

        <div class="mb-3">
          <label class="form-label">Tip</label>
          <select class="form-select" name="type">
            <option value="">Seçiniz</option>
            <option>Gelinlik</option>
            <option>Abiye</option>
            <option>Aksesuar</option>
          </select>
        </div>

        <div class="row g-2">
          <div class="col-md-6">
            <label class="form-label">Ürün Adı</label>
            <input class="form-control" name="name" required>
            <div class="invalid-feedback"></div>
          </div>
          <div class="col-md-6">
            <label class="form-label">Barkod</label>
            <input class="form-control" name="barcode" placeholder="">
            <div class="invalid-feedback"></div>
          </div>
        </div>

        <div class="row g-2 mt-1">
          <div class="col-md-4">
            <label class="form-label">Birim</label>
            <select class="form-select" name="unit">
              <option>Adet</option>
              <option>Takım</option>
              <option>Çift</option>
              <option>Metre</option>
            </select>
          </div>
          <div class="col-md-8">
            <label class="form-label">Stok <small class="text-muted">(Limitsiz için -1 girin)</small></label>
            <input class="form-control" name="stock" value="0">
            <div class="invalid-feedback"></div>
          </div>
        </div>

        <div class="row g-2 mt-1">
          <div class="col-md-4">
            <label class="form-label">Kaç Kez Kullanıldı</label>
            <input class="form-control" name="usage_count" value="0">
          </div>
          <div class="col-md-4">
            <label class="form-label">Marka</label>
            <select class="form-select" name="brand_id">
              <option value="0">Seçiniz</option>
              <?php foreach(($brands??[]) as $b): ?>
                <option value="<?= (int)$b['id'] ?>"><?= htmlspecialchars($b['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Kategori</label>
            <select class="form-select" name="category_id">
              <option value="0">Seçiniz</option>
              <?php foreach(($categories??[]) as $c): ?>
                <option value="<?= (int)$c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="row g-2 mt-1">
          <div class="col-md-4">
            <label class="form-label">Alış Fiyatı</label>
            <input class="form-control" name="buy_price" value="0">
          </div>
          <div class="col-md-4">
            <label class="form-label">Satış Fiyatı</label>
            <input class="form-control" name="sale_price" value="0">
          </div>
          <div class="col-md-4">
            <label class="form-label">Kira Fiyatı</label>
            <input class="form-control" name="rent_price" value="0">
          </div>
        </div>

        <div class="row g-2 mt-1">
          <div class="col-md-4">
            <label class="form-label">KDV Oranı</label>
            <input class="form-control" name="vat_rate" value="0">
          </div>
          <div class="col-md-4">
            <label class="form-label">Min Satış Fiyatı</label>
            <input class="form-control" name="min_sale_price" value="0">
          </div>
          <div class="col-md-4">
            <label class="form-label">Min Kira Fiyatı</label>
            <input class="form-control" name="min_rent_price" value="0">
          </div>
        </div>

        <div class="form-check form-switch mt-2">
          <input class="form-check-input" type="checkbox" name="rent_price_on_barcode" id="rentPriceOnBarcode">
          <label class="form-check-label" for="rentPriceOnBarcode">Kira Fiyatı Barkodda Görünsün</label>
        </div>

        <div class="row g-2 mt-1">
          <div class="col-md-6">
            <label class="form-label">Yıl</label>
            <input class="form-control" name="year" value="">
          </div>
          <div class="col-md-6">
            <label class="form-label">Beden</label>
            <input class="form-control" name="size" value="">
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-light" type="button" data-coreui-dismiss="modal">İptal</button>
        <button class="btn btn-primary" type="submit">Kaydet</button>
      </div>
    </form>
  </div>
</div>

<script>
(() => {
  const form = document.getElementById('productCreateForm');
  form?.addEventListener('submit', async (e) => {
    e.preventDefault();
    [...form.querySelectorAll('.is-invalid')].forEach(el => el.classList.remove('is-invalid'));
    [...form.querySelectorAll('.invalid-feedback')].forEach(el => el.textContent = '');

    const fd = new FormData(form);
    try {
      const res = await fetch('/products', { method: 'POST', body: fd });
      const json = await res.json();

      if (!res.ok) {
        if (json?.errors) {
          Object.entries(json.errors).forEach(([k,v]) => {
            const inp = form.querySelector(`[name="${k}"]`);
            if (inp) {
              inp.classList.add('is-invalid');
              let fb = inp.closest('.mb-3, .col-md-4, .col-md-6')?.querySelector('.invalid-feedback');
              if (!fb) {
                fb = document.createElement('div');
                fb.className = 'invalid-feedback';
                inp.after(fb);
              }
              fb.textContent = v;
            }
          });
        } else {
          alert(json?.message || 'Kaydedilemedi');
        }
        return;
      }

      // Başarılı
      alert(json.message || 'Ürün kaydedildi');
      location.reload();

    } catch (err) {
      console.error(err);
      alert('Bir hata oluştu');
    }
  });
})();
</script>