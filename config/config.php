<?php
// Simple .env loader
function env($key, $default = null) {
    static $loaded = false;
    static $vars = [];
    if (!$loaded) {
        $file = dirname(__DIR__) . '/.env';
        if (file_exists($file)) {
            foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
                if (str_starts_with(trim($line), '#')) continue;
                [$k, $v] = array_pad(explode('=', $line, 2), 2, null);
                if ($k !== null) $vars[trim($k)] = trim($v ?? '');
            }
        }
        $loaded = true;
    }
    return $vars[$key] ?? $default;
}

return [
    'app' => [
        'env' => env('APP_ENV', 'production'),
        'debug' => filter_var(env('APP_DEBUG', false), FILTER_VALIDATE_BOOLEAN),
        'url' => env('APP_URL', 'http://bridal.local'),
        'key' => bin2hex(random_bytes(16)),
    ],
    'db' => [
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3307'),
        'name' => env('DB_NAME', 'bridal_mvc'),
        'user' => env('DB_USER', 'root'),
        'pass' => env('DB_PASS', ''),
        'charset' => env('DB_CHARSET', 'utf8mb4'),
    ],
];
