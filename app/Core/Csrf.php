<?php
namespace App\Core;

class Csrf {
   public static function token(): string {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['_token'])) {
            $_SESSION['_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_token'];
    }
    public static function check(string $t): bool {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        return hash_equals($_SESSION['_token'] ?? '', $t);
    }
}
