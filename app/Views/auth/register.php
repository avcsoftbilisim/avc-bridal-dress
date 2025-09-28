<?php use App\Core\Csrf; $old = $old ?? []; ?>
<?php if (!empty($error_list)): ?>
  <div class="error"><?php echo implode('<br>', array_map('htmlspecialchars', $error_list)); ?></div>
<?php endif; ?>
<form method="post" action="/register" autocomplete="on">
  <input type="hidden" name="_token" value="<?php echo Csrf::token(); ?>">
  <input class="input" name="name" type="text" placeholder="Ad Soyad" value="<?php echo htmlspecialchars($old['name'] ?? ''); ?>" required>
  <input class="input" name="email" type="email" placeholder="E-posta" value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>" required>
  <input class="input" name="password" type="password" placeholder="Şifre (min 8)" required>
  <input class="input" name="password_confirm" type="password" placeholder="Şifre (Tekrar)" required>
  <button class="btn" type="submit">Kayıt Ol</button>
  <a class="btn secondary" href="/login" style="display:block;text-align:center">Girişe Dön</a>
</form>
