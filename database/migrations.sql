-- MariaDB 11.x schema for bridal shop admin
CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(160) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin','staff','tailor') NOT NULL DEFAULT 'admin',
  phone VARCHAR(32) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS customers (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(160) NOT NULL,
  phone VARCHAR(32) NULL,
  email VARCHAR(160) NULL,
  notes TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS products (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  sku VARCHAR(64) NOT NULL UNIQUE,
  name VARCHAR(200) NOT NULL,
  size VARCHAR(20) NULL,
  color VARCHAR(50) NULL,
  price DECIMAL(10,2) NOT NULL DEFAULT 0,
  stock INT NOT NULL DEFAULT 0,
  status ENUM('available','reserved','rented','maintenance','archived') DEFAULT 'available',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS fittings (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  customer_id INT UNSIGNED NOT NULL,
  appointment_at DATETIME NOT NULL,
  notes TEXT NULL,
  status ENUM('planned','done','cancelled') DEFAULT 'planned',
  FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS rentals (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  customer_id INT UNSIGNED NOT NULL,
  product_id INT UNSIGNED NOT NULL,
  rent_date DATE NOT NULL,
  due_date DATE NOT NULL,
  return_date DATE NULL,
  price DECIMAL(10,2) NOT NULL,
  deposit DECIMAL(10,2) NOT NULL DEFAULT 0,
  status ENUM('active','returned','late','cancelled') DEFAULT 'active',
  FOREIGN KEY (customer_id) REFERENCES customers(id),
  FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS payments (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  rental_id INT UNSIGNED NULL,
  customer_id INT UNSIGNED NULL,
  amount DECIMAL(10,2) NOT NULL,
  type ENUM('income','expense') NOT NULL,
  method ENUM('cash','card','transfer') DEFAULT 'cash',
  note VARCHAR(255) NULL,
  paid_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (rental_id) REFERENCES rentals(id),
  FOREIGN KEY (customer_id) REFERENCES customers(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- seed admin user (email: admin@example.com / password: admin123)
INSERT INTO users (name,email,password_hash,role) VALUES
('Yönetici','admin@example.com', CONCAT('$2y$10$', SUBSTRING(SHA2(RAND(),256),1,22), 'ZZ0gdQyqX5G2RkF0Kq7wduG3pPOgk9w4C6z6c3m2qkq8fC/6y'), 'admin');
-- Update the password hash to a generated one using PHP when running if needed.

CREATE TABLE IF NOT EXISTS tailor_jobs (
  id            INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  tailor_id     INT UNSIGNED NOT NULL,
  product_id    INT UNSIGNED NULL,                -- seçili üründen
  product_name  VARCHAR(150) NOT NULL,            -- serbest isim (formdaki “Ürün adı”)
  note          TEXT NULL,
  price         DECIMAL(10,2) NULL,
  sent_at       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,   -- terziye verildi
  due_at        DATETIME NULL,                                  -- istenen teslim tarihi
  returned_at   DATETIME NULL,                                  -- terziden geri geldi
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at    DATETIME NULL,
  deleted_at    DATETIME NULL,

  CONSTRAINT fk_tailor_jobs_tailor
    FOREIGN KEY (tailor_id) REFERENCES tailors(id)
    ON DELETE RESTRICT ON UPDATE CASCADE,

  KEY idx_jobs_scope (returned_at, sent_at),
  KEY idx_jobs_deleted (deleted_at),
  KEY idx_jobs_name (product_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===== definitions: income categories =========================
CREATE TABLE IF NOT EXISTS income_categories (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  title      VARCHAR(120) NOT NULL,
  sort       INT NOT NULL DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- örnek başlangıç verisi
INSERT INTO income_categories (title, sort)
SELECT * FROM (SELECT 'FİRMA SAHİBİ' AS title, 0 AS sort) AS seed
WHERE NOT EXISTS (SELECT 1 FROM income_categories LIMIT 1);