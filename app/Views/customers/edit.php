<h4 class="mb-3">Müşteri Düzenle</h4>
<form method="post" action="/customers/<?= (int)$c['id'] ?>">
  <?php include __DIR__.'/_form.php'; ?>
  <div class="mt-4">
    <a href="/customers" class="btn btn-light">Geri</a>
    <button class="btn btn-primary" type="submit">Güncelle</button>
  </div>
</form>