<?php use App\Core\Csrf; ?>
<?php if (!empty($success)): ?><div class="error" style="background:#14532d"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
<?php if (!empty($dev_link)): ?>
  <div class="error" style="background:#1f2937">Geliştirici bağlantısı: <a href="<?php echo htmlspecialchars($dev_link); ?>"><?php echo htmlspecialchars($dev_link); ?></a></div>
<?php endif; ?>
<form method="post" action="/password/forgot" autocomplete="on">
  <input type="hidden" name="_token" value="<?php echo Csrf::token(); ?>">
  <input class="input" name="email" type="email" placeholder="Kayıtlı e-posta" required>
  <button class="btn" type="submit">Sıfırlama Bağlantısı Gönder</button>
  <a class="btn secondary" href="/login" style="display:block;text-align:center">Girişe Dön</a>
</form>
