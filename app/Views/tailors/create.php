<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0">Terziye Ürün Gönder</h5>
</div>

<form method="post" action="/tailors" class="row g-3">
  <div class="col-md-4">
    <label class="form-label">Terzi Seçimi <span class="text-danger">*</span></label>
    <div class="input-group">
      <select id="tailorSelect" name="tailor_id" class="form-select" required>
        <option value="">Seçiniz…</option>
        <?php foreach($tailors as $t): ?>
          <option value="<?= (int)$t['id'] ?>"><?= htmlspecialchars($t['name'].($t['phone']?' - '.$t['phone']:'')) ?></option>
        <?php endforeach; ?>
        <option value="__new__">Yeni Oluştur</option>
      </select>
    </div>
  </div>

  <div class="col-md-8">
    <label class="form-label">Ürün Seç</label>
    <select name="product_id" id="productSelect" class="form-select">
      <option value="">(Opsiyonel) – Listeden seç</option>
      <?php foreach($products as $p): ?>
        <option value="<?= (int)$p['id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="col-12">
    <label class="form-label">Ürün Adı <span class="text-danger">*</span></label>
    <input type="text" name="product_name" class="form-control" required placeholder="Örn: Dantel A kesim gelinlik" />
  </div>

  <div class="col-md-6">
    <label class="form-label">İstenen Teslim Tarihi</label>
    <input type="datetime-local" name="due_at" class="form-control" />
  </div>
  <div class="col-md-6">
    <label class="form-label">Ücret (opsiyonel)</label>
    <input type="number" step="0.01" min="0" name="price" class="form-control" />
  </div>

  <div class="col-12">
    <label class="form-label">Terzi Notu</label>
    <textarea name="note" rows="5" class="form-control" placeholder="Örn: Etek boyu kısaltılacak, bel daraltma…"></textarea>
  </div>

  <div class="col-12">
    <button class="btn btn-primary w-100">Oluştur</button>
  </div>
</form>

<!-- Yeni Terzi Modal -->
<div class="modal" id="modalTailor" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" id="formNewTailor">
      <div class="modal-header"><h6 class="modal-title">Terzi Oluştur</h6>
        <button type="button" class="btn-close" data-coreui-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Ad Soyad</label>
          <input name="name" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">GSM</label>
          <input name="phone" class="form-control">
        </div>
        <div class="mb-3">
          <label class="form-label">2. GSM</label>
          <input name="phone2" class="form-control">
        </div>
        <div class="mb-3">
          <label class="form-label">Adres</label>
          <input name="address" class="form-control">
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-coreui-dismiss="modal">İptal</button>
        <button class="btn btn-primary" type="submit">Kaydet</button>
      </div>
    </form>
  </div>
</div>

<script>
(function(){
  const sel = document.getElementById('tailorSelect');
  const modalEl = document.getElementById('modalTailor');
  let modal;
  if(window.coreui){ modal = new coreui.Modal(modalEl); }

  sel.addEventListener('change', function(){
    if(this.value==='__new__'){ modal.show(); }
  });

  document.getElementById('formNewTailor').addEventListener('submit', function(e){
    e.preventDefault();
    const fd = new FormData(this);
    fetch('/tailors/new', {method:'POST', body:fd})
      .then(r=>r.json())
      .then(j=>{
        if(j.ok){
          const opt = document.createElement('option');
          opt.value = j.id; opt.textContent = j.name;
          sel.insertBefore(opt, sel.querySelector('option[value="__new__"]'));
          sel.value = j.id;
          modal.hide();
          this.reset();
        }else{
          alert(j.msg||'Kaydedilemedi');
        }
      });
  });
})();
</script>