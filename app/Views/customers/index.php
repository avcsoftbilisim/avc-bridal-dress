<div class="card">
  <h3>Müşteriler</h3>
  <table class="table">
    <thead><tr><th>ID</th><th>Ad Soyad</th><th>Telefon</th><th>E-posta</th><th>Not</th></tr></thead>
    <tbody>
      <?php foreach ($customers as $c): ?>
        <tr>
          <td><?php echo (int)$c['id']; ?></td>
          <td><?php echo htmlspecialchars($c['name']); ?></td>
          <td><?php echo htmlspecialchars($c['phone']); ?></td>
          <td><?php echo htmlspecialchars($c['email']); ?></td>
          <td><?php echo htmlspecialchars($c['notes']); ?></td>
        </tr>
      <?php endforeach; ?>
      <?php if(empty($customers)): ?><tr><td colspan="5">Kayıt yok</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>
