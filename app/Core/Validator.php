<?php
namespace App\Core;

class Validator {
  public static function back(array $errors, array $old, string $to): void {
    $_SESSION['errors'] = $errors;
    $_SESSION['old']    = $old;
    header("Location: $to");
    exit;
  }
  public static function errors(): array {
    $e = $_SESSION['errors'] ?? [];
    unset($_SESSION['errors']);
    return $e;
  }
  public static function old(): array {
    $o = $_SESSION['old'] ?? [];
    unset($_SESSION['old']);
    return $o;
  }
}