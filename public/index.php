<?php
declare(strict_types=1);

session_start();

$isDebug = (getenv('APP_DEBUG') === 'true');   // .env: APP_DEBUG=true/false

var_dump(class_exists('App\\Controllers\\TailorController')); exit;



require __DIR__ . '/../app/Core/Autoloader.php';

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\CustomersController;
use App\Controllers\ProductsController;
use App\Controllers\RentalsController;
use App\Controllers\TailorController;

error_reporting(E_ALL);
ini_set('display_errors','1');

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


// Ürünler
$router->get('/products',                [ProductsController::class, 'index']);
$router->post('/products',               [ProductsController::class, 'store']);   // modal kaydet

// Gelecek ürünler listesi + kayıt
$router->get('/products/incoming',        [ProductsController::class, 'incoming']);
$router->post('/products/incoming',       [ProductsController::class, 'incomingStore']);

// Silinen ürünler
$router->get('/products/deleted',                [ProductsController::class, 'deleted']);
$router->post('#^/products/deleted/(\d+)/restore$#', [ProductsController::class, 'restore']);
$router->post('#^/products/deleted/(\d+)/purge$#',   [ProductsController::class, 'purge']);

// Kiralamalar (doğru path)
$router->get('/rentals',         [RentalsController::class, 'current']);
$router->get('/rentals/current', [RentalsController::class, 'current']);
$router->get('/rentals/past',    [RentalsController::class, 'past']);
$router->get('/rentals/future',  [RentalsController::class, 'future']);
$router->post('/rentals/{id}/return', [RentalsController::class, 'markReturned']);

// Teslim alma (return)
$router->post('#^/rentals/(\d+)/return$#', [RentalsController::class, 'markReturned']);

// Kiralamalar Köprü: /products/rentals → rentals
$router->get('/products/rentals',        [RentalsController::class, 'current']);
$router->get('/products/rentals/past',   [RentalsController::class, 'past']);
$router->get('/products/rentals/future', [RentalsController::class, 'future']);

// Terzideki (doğru path)
$router->get('/tailor',         [TailorController::class, 'current']);
$router->get('/tailor/current', [TailorController::class, 'current']);
$router->get('/tailor/past',    [TailorController::class, 'past']);
$router->get('/tailor/future',  [TailorController::class, 'future']);

// Terzideki ürünler
$router->get('/products/tailor',         [TailorController::class, 'current']); // Terzidekiler
$router->get('/products/tailor/past',    [TailorController::class, 'past']);    // Geçmiş
$router->get('/products/tailor/future',  [TailorController::class, 'future']);  // Gelecek

// (İleride) Terziye gönderme ve yeni terzi ekleme POST uçları
$router->post('/tailor/jobs',            [TailorController::class, 'storeJob']);    // “Terziye ürün gönder”
$router->post('/tailor/people',          [TailorController::class, 'storeTailor']); // “Yeni terzi oluştur”

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
