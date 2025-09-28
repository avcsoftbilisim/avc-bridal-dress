<?php
declare(strict_types=1);

session_start();

$isDebug = (getenv('APP_DEBUG') === 'true');   // .env: APP_DEBUG=true/false

if ($isDebug) {
  ini_set('display_errors', '1');
  error_reporting(E_ALL);
} else {
  ini_set('display_errors', '0');
  ini_set('log_errors', '1');
  ini_set('error_log', __DIR__ . '/../logs/php-error.log');
  // deprecation/notice/uyarıları arayüzden gizle
  error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_WARNING);
}

require __DIR__ . '/../app/Core/Autoloader.php';

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\CustomersController;

//error_reporting(E_ALL);
//ini_set('display_errors','1');

$config = require __DIR__ . '/../config/config.php';

$router = new Router();

// Routes
// Routes (tam nitelikli sınıf adlarıyla)
$router->get('/',                   [\App\Controllers\HomeController::class, 'index']);

$router->get('/login',              [\App\Controllers\AuthController::class, 'login']);
$router->post('/login',             [\App\Controllers\AuthController::class, 'loginPost']);

$router->get('/logout',             [\App\Controllers\AuthController::class, 'logout']);

$router->get('/register',           [\App\Controllers\AuthController::class, 'register']);
$router->post('/register',          [\App\Controllers\AuthController::class, 'registerPost']);

$router->get('/password/forgot',    [\App\Controllers\AuthController::class, 'forgot']);
$router->post('/password/forgot',   [\App\Controllers\AuthController::class, 'forgotPost']);

$router->get('/password/reset',     [\App\Controllers\AuthController::class, 'reset']);     // ?token=...
$router->post('/password/reset',    [\App\Controllers\AuthController::class, 'resetPost']);

// Cari Yönetimi
$router->get('/customers',          [\App\Controllers\CustomersController::class, 'index']);
$router->get('/customers/create',   [\App\Controllers\CustomersController::class, 'create']);
$router->post('/customers',         [\App\Controllers\CustomersController::class, 'store']);
$router->get('/customers/{id}/edit',[\App\Controllers\CustomersController::class, 'edit']);
$router->post('/customers/{id}',    [\App\Controllers\CustomersController::class, 'update']);

$router->get('/customers/transactions', [\App\Controllers\CustomersController::class, 'transactions']);

// (İsteğe bağlı) /customer -> /customers
$router->get('/customer', function(){ header('Location:/customers', true, 301); exit; });

// Hızlı test
$router->get('/ping', function(){ echo 'pong'; });

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
