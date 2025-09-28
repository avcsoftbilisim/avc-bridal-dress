
<?php if (!empty($success)): ?>
  <div class="error" style="background:#14532d"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>
<?php use App\Core\Csrf; ?>
<?php if (!empty($error)): ?><div class="error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
<form method="post" action="/login" autocomplete="on">
  <input type="hidden" name="_token" value="<?php echo Csrf::token(); ?>">
  <input class="input" name="email" type="email" placeholder="e-posta" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
  <input class="input" name="password" type="password" placeholder="şifre" required>
  <button class="btn" type="submit">Giriş Yap</button>
  <a class="btn secondary" href="/register" style="display:block;text-align:center">Kayıt Ol</a>
</form>

<div style="margin-top:10px;text-align:center">
  <a href="/password/forgot">Şifremi Unuttum?</a>
</div>