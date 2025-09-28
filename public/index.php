<?php
declare(strict_types=1);

session_start();

require __DIR__ . '/../app/Core/Autoloader.php';

use App\Core\Router;

//error_reporting(E_ALL);
//ini_set('display_errors','1');

$config = require __DIR__ . '/../config/config.php';

$router = new Router();

// Routes
$router->get('/', 'HomeController@index');
$router->get('/login', 'AuthController@login');
$router->post('/login', 'AuthController@loginPost');
$router->get('/logout', 'AuthController@logout');
$router->get('/register', 'AuthController@register');
$router->post('/register', 'AuthController@registerPost');
$router->get('/password/forgot', 'AuthController@forgot');
$router->post('/password/forgot', 'AuthController@forgotPost');
$router->get('/password/reset', 'AuthController@reset'); // ?token=...
$router->post('/password/reset', 'AuthController@resetPost');

$router->get('/customers', 'CustomersController@index');

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
