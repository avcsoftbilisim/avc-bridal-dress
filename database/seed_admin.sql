-- örnek: hash'ı başka PC'de public/make_hash.php ile üretip buraya manuel koyarsın
UPDATE users SET email='admin@example.com', name='Yönetici', role='admin',
       password_hash='BURAYA_HASH_GELECEK' WHERE id=1;