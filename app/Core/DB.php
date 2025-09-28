<?php
namespace App\Core;
use PDO;

class DB {
    private static ?PDO $pdo = null;

    public static function conn(): PDO {
        if (self::$pdo) return self::$pdo;

        $host    = env('DB_HOST', '127.0.0.1');
        $port    = env('DB_PORT', '3307');          // <-- ÖNEMLİ: PORT
        $name    = env('DB_NAME', 'bridal_mvc');
        $charset = env('DB_CHARSET', 'utf8mb4');
        $user    = env('DB_USER', 'root');
        $pass    = env('DB_PASS', '');

        $dsn = "mysql:host={$host};port={$port};dbname={$name};charset={$charset}";
        self::$pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        return self::$pdo;
    }
}
