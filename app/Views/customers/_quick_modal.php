<div class="modal fade" id="quickCustomerModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Müşteri Oluştur</h5>
        <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Kapat"></button>
      </div>

      <form id="quickCustomerForm">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Ad Soyad *</label>
            <input name="name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">GSM *</label>
            <input name="phone" class="form-control" placeholder="+90 5xx ..." required>
          </div>
          <div class="mb-3">
            <label class="form-label">GSM (Yakını)</label>
            <input name="phone2" class="form-control" placeholder="+90 5xx ...">
          </div>
          <div class="mb-3">
            <label class="form-label">Ülke</label>
            <select name="country" class="form-select">
              <option value="Türkiye" selected>Türkiye</option>
              <option value="Azerbaycan">Azerbaycan</option>
              <option value="Almanya">Almanya</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Adres</label>
            <input name="address" class="form-control">
          </div>
          <div class="text-danger small" id="quickCustomerErrors" style="display:none"></div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">İptal</button>
          <button class="btn btn-primary" type="submit">Kaydet</button>
        </div>
      </form>

    </div>
  </div>
</div>

<script>
(function(){
  const fm  = document.getElementById('quickCustomerForm');
  const err = document.getElementById('quickCustomerErrors');
  fm.addEventListener('submit', async function(e){
    e.preventDefault();
    err.style.display='none'; err.innerHTML='';
    const fd = new FormData(fm);
    const res = await fetch('/customers/quick', { method:'POST', body:fd });
    const data = await res.json();
    if(!data.ok){
      err.style.display='block';
      err.innerHTML = Object.values(data.errors||{}).join('<br>');
      return;
    }
    // başarı: modalı kapat, sayfayı yenile ya da listeye ekle
    coreui.Modal.getInstance(document.getElementById('quickCustomerModal')).hide();
    location.reload();
  });
})();
</script>