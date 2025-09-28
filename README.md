# AVC Bridal Dress – Admin (Plain PHP MVC + PDO)

- PHP 8.4+
- MariaDB 11+
- Apache + mod_rewrite (DocumentRoot: `public/`)
- No Composer / No Framework

## Basit Kurulum
1) Bir veritabanı oluşturun: `bridal_mvc`.
2) `database/migrations.sql` dosyasını içe aktarın.
3) `.env.example` dosyasını kopyalayıp `.env` olarak düzenleyin.
4) Web sunucu kök dizinini `public/` klasörüne yönlendirin (Apache için `.htaccess` mevcut).
5) Tarayıcıdan açın: `http://localhost/`

## Gelişmiş Kurulum
1. `cp .env.example .env` ve DB bilgilerini doldur.
2. DB oluştur ve migration’ları yükle:
   - `database/migrations.sql` (varsa)
   - `database/migrations_password_resets.sql`
3. CoreUI offline dosyaları (1 kez internetle):
   - `scripts/fetch_coreui.ps1` (Windows) veya `scripts/fetch_coreui.sh`
4. hosts/vhost:
   - `127.0.0.1 bridal.local`  
   - vhost DocumentRoot → `public/`
5. Giriş: `/login` (veya `/register` ile kullanıcı oluştur).



### Giriş
- E-posta: `admin@example.com`
- Şifre: varsayılanı siz oluşturun (migrations sql açıklamasına bakın).

> Güvenlik: production'da `APP_DEBUG=false` ayarlayın, güçlü bir `password_hash()` üretip `users` tablosuna yazın.

## Yapı
- `app/Core` : Router, Controller, DB, Model, CSRF
- `app/Controllers` : İş mantığı
- `app/Models` : Modeller
- `app/Views` : Görünümler + basit layout
- `public` : Front controller ve assetler
- `config` : Uygulama/DB ayarları
- `database` : SQL

## Geliştirme İpuçları
- Yeni sayfa için Controller + View ekleyip route tanımlayın (bkz. `public/index.php`).
- Model içinde ortak CRUD metodlarını genişletin.
- CSRF koruması formlarda `<?php echo App\Core\Csrf::token(); ?>` ile.

