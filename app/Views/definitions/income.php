<?php if (session_status() !== PHP_SESSION_ACTIVE) session_start(); ?>
<div class="row">
  <!-- Sol sekmeler -->
  <div class="col-12 col-lg-3 col-xl-2 mb-3">
    <div class="list-group">
      <a href="/definitions/income"
         class="list-group-item list-group-item-action<?= ($sub??'')==='income'?' active':'' ?>">
         Gelir Kategorileri
      </a>
      <a href="#"
         class="list-group-item list-group-item-action disabled">Gider Kategorileri</a>
      <a href="#"
         class="list-group-item list-group-item-action disabled">Cari Grupları</a>
      <a href="#"
         class="list-group-item list-group-item-action disabled">Ürün Kategorileri</a>
      <a href="#"
         class="list-group-item list-group-item-action disabled">Ürün Alt Kategorileri</a>
      <a href="#"
         class="list-group-item list-group-item-action disabled">Ürün Markaları</a>
      <a href="#"
         class="list-group-item list-group-item-action disabled">Birimler</a>
      <a href="#"
         class="list-group-item list-group-item-action disabled">Sabit SMS'ler</a>
      <a href="#"
         class="list-group-item list-group-item-action disabled">Satış Sözleşmesi</a>
      <a href="#"
         class="list-group-item list-group-item-action disabled">Kira Sözleşmesi</a>
      <a href="#"
         class="list-group-item list-group-item-action disabled">Sipariş Sözleşmesi</a>
      <a href="#"
         class="list-group-item list-group-item-action disabled">Terzi Fişi</a>
      <a href="#"
         class="list-group-item list-group-item-action disabled">Kuru Temizleme Fişi</a>
    </div>
  </div>

  <!-- Sağ içerik -->
  <div class="col-12 col-lg-9 col-xl-10">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">Gelir Kategorileri</h5>
      <button class="btn btn-primary" data-coreui-toggle="modal" data-coreui-target="#modalCreate">
        Yeni Oluştur
      </button>
    </div>

    <?php if (!empty($_SESSION['flash_ok'])): ?>
      <div class="alert alert-success"><?= $_SESSION['flash_ok']; unset($_SESSION['flash_ok']); ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['flash_error'])): ?>
      <div class="alert alert-danger"><?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?></div>
    <?php endif; ?>

    <div class="card">
      <div class="card-body p-0">
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th class="w-75">Başlık</th>
              <th class="text-center">Sıra</th>
              <th class="text-end">İşlem</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach (($items ?? []) as $row): ?>
            <tr>
              <td><?= htmlspecialchars($row['title']) ?></td>
              <td class="text-center"><?= (int)$row['sort'] ?></td>
              <td class="text-end">
                <button class="btn btn-sm btn-outline-primary me-2"
                        data-coreui-toggle="modal"
                        data-coreui-target="#modalEdit"
                        data-id="<?= (int)$row['id'] ?>"
                        data-title="<?= htmlspecialchars($row['title']) ?>"
                        data-sort="<?= (int)$row['sort'] ?>">
                  Düzenle
                </button>
                <form class="d-inline" method="post" action="/definitions/income/<?= (int)$row['id'] ?>/delete"
                      onsubmit="return confirm('Silinsin mi?')">
                  <input type="hidden" name="_token" value="<?= \App\Core\Csrf::token() ?>">
                  <button class="btn btn-sm btn-danger">Sil</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($items)): ?>
            <tr><td colspan="3" class="text-center py-4 text-muted">Henüz bir kayıt yok.</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Yeni Oluştur Modal -->
<div class="modal fade" id="modalCreate" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form class="modal-content" method="post" action="/definitions/income">
      <input type="hidden" name="_token" value="<?= \App\Core\Csrf::token() ?>">
      <div class="modal-header">
        <h5 class="modal-title">Yeni Gelir Kategorisi</h5>
        <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Başlık *</label>
          <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Sıra</label>
          <input type="number" name="sort" class="form-control" value="0" step="1" min="0">
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-coreui-dismiss="modal">İptal</button>
        <button class="btn btn-primary">Kaydet</button>
      </div>
    </form>
  </div>
</div>

<!-- Düzenle Modal -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form class="modal-content" method="post" id="editForm">
      <input type="hidden" name="_token" value="<?= \App\Core\Csrf::token() ?>">
      <div class="modal-header">
        <h5 class="modal-title">Kategori Düzenle</h5>
        <button type="button" class="btn-close" data-coreui-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Başlık *</label>
          <input type="text" name="title" id="editTitle" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Sıra</label>
          <input type="number" name="sort" id="editSort" class="form-control" step="1" min="0">
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-coreui-dismiss="modal">İptal</button>
        <button class="btn btn-primary">Güncelle</button>
      </div>
    </form>
  </div>
</div>

<script>
document.getElementById('modalEdit')?.addEventListener('show.coreui.modal', function (e) {
  const btn = e.relatedTarget;
  const id   = btn.getAttribute('data-id');
  const t    = btn.getAttribute('data-title');
  const s    = btn.getAttribute('data-sort');
  document.getElementById('editTitle').value = t || '';
  document.getElementById('editSort').value  = s || 0;
  document.getElementById('editForm').action = '/definitions/income/' + id;
});
</script>