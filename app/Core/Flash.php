<?php
namespace App\Core;

class Flash {
  public static function set(string $key, string $msg): void {
    $_SESSION['flash'][$key] = $msg;
  }
  public static function get(string $key, $default = null) {
    if (!empty($_SESSION['flash'][$key])) {
      $v = $_SESSION['flash'][$key];
      unset($_SESSION['flash'][$key]);
      return $v;
    }
    return $default;
  }
  public static function all(): array {
    $all = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $all;
  }
}