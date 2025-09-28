
<?php $title='Müşteriler'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Müşteriler</h4>
  <div>
    <a href="/customers/create" class="btn btn-outline-primary me-2"><i class="cil-user-follow"></i> Detaylı Oluştur</a>
    <button class="btn btn-primary" data-coreui-toggle="modal" data-coreui-target="#quickCustomerModal">
      <i class="cil-plus"></i> Hızlı Ekle
    </button>
  </div>
</div>

<table class="table table-hover align-middle">
  <thead><tr>
    <th>#</th><th>Ad Soyad</th><th>GSM</th><th>Tür</th><th>Durum</th><th></th>
  </tr></thead>
  <tbody>
    <?php foreach ($customers as $c): ?>
      <tr>
        <td><?= (int)$c['id'] ?></td>
        <td><?= htmlspecialchars($c['name']) ?></td>
        <td><?= htmlspecialchars($c['phone']) ?></td>
        <td><?= $c['type']==='corporate'?'Kurumsal':'Bireysel' ?></td>
        <td><?= !empty($c['is_quick'])?'Hızlı Kayıt':'Detaylı' ?></td>
        <td class="text-end">
          <a class="btn btn-sm btn-outline-secondary" href="/customers/<?= (int)$c['id'] ?>/edit">Düzenle</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php include __DIR__.'/_quick_modal.php'; ?>
