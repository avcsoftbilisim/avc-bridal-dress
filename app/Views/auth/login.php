<?php use App\Core\Csrf; ?>
<div class="row justify-content-center">
  <div class="col-md-5 col-lg-4">
    <div class="card shadow-sm">
      <div class="card-header"><strong>Giriş Yap</strong></div>
      <div class="card-body">
        <?php if (!empty($error)): ?>
          <div class="alert alert-danger py-2"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="post" action="/login">
          <input type="hidden" name="_token" value="<?php echo Csrf::token(); ?>">
          <div class="mb-3">
            <label class="form-label">E-posta</label>
            <input name="email" type="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Şifre</label>
            <input name="password" type="password" class="form-control" required>
          </div>
          <div class="d-grid">
            <button class="btn btn-primary" type="submit">Giriş</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
