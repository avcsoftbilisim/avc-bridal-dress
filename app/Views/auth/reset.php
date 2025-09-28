<?php use App\Core\Csrf; ?>
<?php if (!empty($error_list)): ?>
  <div class="error"><?php echo implode('<br>', array_map('htmlspecialchars', $error_list)); ?></div>
<?php endif; ?>
<form method="post" action="/password/reset" autocomplete="off">
  <input type="hidden" name="_token" value="<?php echo Csrf::token(); ?>">
  <input type="hidden" name="token" value="<?php echo htmlspecialchars($token ?? ''); ?>">
  <input class="input" name="password" type="password" placeholder="Yeni şifre (min 8)" required>
  <input class="input" name="password_confirm" type="password" placeholder="Şifre (Tekrar)" required>
  <button class="btn" type="submit">Şifreyi Güncelle</button>
</form>
<div style="margin-top:10px"><a href="/login">Girişe dön</a></div>
