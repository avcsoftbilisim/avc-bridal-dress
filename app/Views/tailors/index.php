<?php if (session_status()!==PHP_SESSION_ACTIVE) session_start(); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0"><?= htmlspecialchars($title) ?></h5>

  <div class="d-flex gap-2">
    <div class="input-group">
      <select class="form-select" onchange="location.href=this.value;">
        <option value="/tailors"          <?= $scope==='current'?'selected':'' ?>>Terzideki Ürünler</option>
        <option value="/tailors/past"     <?= $scope==='past'?'selected':'' ?>>Geçmişte terziye verilenler</option>
        <option value="/tailors/future"   <?= $scope==='future'?'selected':'' ?>>Gelecekte terziye verilecekler</option>
      </select>
    </div>
    <a class="btn btn-primary" href="/tailors/create">Terziye Ürün Gönder!</a>
  </div>
</div>

<table class="table table-hover align-middle">
  <thead class="table-light">
    <tr>
      <th>Terzi Adı</th>
      <th>Telefonu</th>
      <th>Ürün Adı</th>
      <th>Alış Tarihi</th>
      <th>Teslim Edilmesi Gereken Tarih</th>
      <th>Toplam Ücret</th>
      <th style="width:80px;"></th>
    </tr>
  </thead>
  <tbody>
  <?php if (empty($items)): ?>
    <tr><td colspan="7">
      <div class="alert alert-danger mb-0">Henüz bir şey eklenmemiş!</div>
    </td></tr>
  <?php else: foreach ($items as $r): ?>
    <tr>
      <td><?= htmlspecialchars($r['tailor_name']) ?></td>
      <td><?= htmlspecialchars($r['tailor_phone'] ?? '') ?></td>
      <td><?= htmlspecialchars($r['product_name']) ?></td>
      <td><?= htmlspecialchars($r['sent_at']) ?></td>
      <td><?= htmlspecialchars($r['due_at'] ?: '-') ?></td>
      <td><?= $r['price']!==null ? number_format((float)$r['price'],2) : '-' ?></td>
      <td class="text-end">
        <?php if (!$r['returned_at']): ?>
          <button class="btn btn-sm btn-success" onclick="markReturned(<?= (int)$r['id'] ?>)">Geldi</button>
        <?php else: ?>
          <span class="badge text-bg-secondary">Tamam</span>
        <?php endif; ?>
      </td>
    </tr>
  <?php endforeach; endif; ?>
  </tbody>
</table>

<script>
function markReturned(id){
  if(!confirm('Terziden geri geldi olarak işaretlensin mi?')) return;
  fetch('/tailors/'+id+'/return', {method:'POST'})
   .then(r=>r.json()).then(j=>{ if(j.ok){ location.reload(); } });
}
</script>