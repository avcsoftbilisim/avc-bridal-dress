<h4 class="mb-3">Müşteri Oluştur</h4>
<form method="post" action="/customers">
  <?php include __DIR__.'/_form.php'; ?>
  <div class="mt-4">
    <a href="/customers" class="btn btn-light">İptal</a>
    <button class="btn btn-primary" type="submit">Kaydet</button>
  </div>
</form>