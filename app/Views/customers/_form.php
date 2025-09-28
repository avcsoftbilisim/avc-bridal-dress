<?php
$errors = $errors ?? [];
$old    = $old ?? [];
// edit sayfasında $form geliyor; create’de $old
$form   = $form ?? $old ?? [];
$val = fn($k,$d='') => htmlspecialchars((string)($form[$k] ?? $d));
$inv = fn($k) => isset($errors[$k]) ? ' is-invalid' : '';
$err = fn($k) => isset($errors[$k]) ? '<div class="invalid-feedback">'.$errors[$k].'</div>' : '';

$c = $c ?? [];  // edit'te dolu gelir
$isCorporate = ($c['type']??($_POST['type']??'individual'))==='corporate';
?>
<div class="row g-4">
  <div class="col-md-2">
    <label class="form-label d-block">Cari Tipi *</label>
    <div class="form-check">
      <input class="form-check-input" type="radio" name="type" id="type1" value="individual" <?= !$isCorporate?'checked':'' ?>>
      <label for="type1" class="form-check-label">Bireysel</label>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="radio" name="type" id="type2" value="corporate" <?= $isCorporate?'checked':'' ?>>
      <label for="type2" class="form-check-label">Kurumsal</label>
    </div>
  </div>
  <div class="col-md-5">
    <label class="form-label">Ad Soyad *</label>
    <input required name="name" class="form-control" value="<?= htmlspecialchars($c['name']??'') ?>">
  </div>
  <div class="col-md-5">
    <label class="form-label">E-mail</label>
    <input name="email" type="email" class="form-control" value="<?= htmlspecialchars($c['email']??'') ?>">
    <?= $err('email') ?>
  </div>

  <div class="col-md-6">
    <label class="form-label">Telefon Numarası *</label>
    <input required name="phone" class="form-control" pattern="\d{10,11}" maxlength="11" value="<?= htmlspecialchars($c['phone']??'') ?>">
    <?= $err('phone') ?>
    <small class="text-body-secondary">Başında 0 olabilir; 10–11 hane.</small>   
  </div>
  <div class="col-md-6">
    <label class="form-label">Kategori *</label>
    <select name="category" class="form-select">
      <?php $cat = $c['category']??'Müşteri'; ?>
      <option <?= $cat==='Müşteri'?'selected':'' ?>>Müşteri</option>
      <option <?= $cat==='Tedarikçi'?'selected':'' ?>>Tedarikçi</option>
      <option <?= $cat==='Diğer'?'selected':'' ?>>Diğer</option>
    </select>
  </div>

  <div class="col-md-6">
    <label class="form-label">TC Kimlik No</label>
    <input name="national_id" class="form-control" inputmode="numeric" pattern="\d{11}" maxlength="11" data-digits value="<?= htmlspecialchars($c['national_id']??'') ?>">
    <?= $err('tc') ?>
    <small class="text-body-secondary">11 hane, sadece rakam.</small>    
  </div>
  <div class="col-md-3">
    <label class="form-label">Ülke</label>
    <select name="country" class="form-select">
      <?php $country = $c['country']??'Türkiye'; ?>
      <option <?= $country==='Türkiye'?'selected':'' ?>>Türkiye</option>
      <option <?= $country==='Azerbaycan'?'selected':'' ?>>Azerbaycan</option>
      <option <?= $country==='Almanya'?'selected':'' ?>>Almanya</option>
    </select>
  </div>
  <div class="col-md-3">
    <label class="form-label">İl</label>
    <input name="state" class="form-control" value="<?= htmlspecialchars($c['state']??'') ?>">
  </div>
  <div class="col-md-3">
    <label class="form-label">İlçe</label>
    <input name="district" class="form-control" value="<?= htmlspecialchars($c['district']??'') ?>">
  </div>
  <div class="col-md-9">
    <label class="form-label">Adres</label>
    <input name="address" class="form-control" value="<?= htmlspecialchars($c['address']??'') ?>">
  </div>

  <div class="col-md-6">
    <label class="form-label">Damat Ad Soyad *</label>
    <input name="groom_name" class="form-control" value="<?= htmlspecialchars($c['groom_name']??'') ?>">
  </div>
  <div class="col-md-3">
    <label class="form-label">Damat Telefonu *</label>
    <input name="groom_phone" class="form-control" pattern="\d{10,11}" maxlength="11" data-digits value="<?= htmlspecialchars($c['groom_phone']??'') ?>">
    <?= $err('groom_phone') ?>    
  </div>
  <div class="col-md-3">
    <label class="form-label">Damat T.C. No *</label>
    <input name="groom_national_id" class="form-control" inputmode="numeric" pattern="\d{11}" maxlength="11" data-digits value="<?= htmlspecialchars($c['groom_national_id']??'') ?>">
    <?= $err('tc') ?>
    <small class="text-body-secondary">11 hane, sadece rakam.</small>
  </div>

  <!-- Kurumsal alanlar -->
  <div class="col-12">
    <div class="row g-4 corporate-fields" <?= $isCorporate?'':'style="display:none"' ?>>
      <div class="col-md-4">
        <label class="form-label">Firma Ünvanı</label>
        <input name="company_name" class="form-control" value="<?= htmlspecialchars($c['company_name']??'') ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Vergi No</label>
        <input name="tax_number" class="form-control" value="<?= htmlspecialchars($c['tax_number']??'') ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Vergi Dairesi</label>
        <input name="tax_office" class="form-control" value="<?= htmlspecialchars($c['tax_office']??'') ?>">
      </div>
    </div>
  </div>
</div>

<script>
(function(){
  function toggleCorp(){
    const isCorp = document.getElementById('type2').checked;
    document.querySelector('.corporate-fields').style.display = isCorp ? '' : 'none';
  }
  document.getElementById('type1').addEventListener('change', toggleCorp);
  document.getElementById('type2').addEventListener('change', toggleCorp);
})();
</script>