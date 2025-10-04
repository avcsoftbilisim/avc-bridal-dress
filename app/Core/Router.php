<?php
namespace App\Core;

class Router {
    protected array $routes = [];

    public function get(string $path, $handler)  { $this->add('GET',  $path, $handler); }
    public function post(string $path, $handler) { $this->add('POST', $path, $handler); }

    protected function add(string $method, string $path, $handler) {
        // trailing slash normalizasyonu
        $path = rtrim($path, '/');
        if ($path === '') $path = '/';

        // Eğer ham regex verdiysek (# ile başlıyorsa) dokunmadan kullan
        if ($path[0] === '#') {
            $pattern = $path;
        } else {
            // {id} gibi parametreleri yakala
            $pattern = '#^' . preg_replace(
                '#\{([a-zA-Z_][a-zA-Z0-9_-]*)\}#',
                '(?P<$1>[^/]+)',
                $path
            ) . '$#';
        }

        $this->routes[] = compact('method','pattern','handler');
    }

    public function dispatch(string $method, string $uri) {
    $path = parse_url($uri, PHP_URL_PATH);

        // /rentals/ → /rentals
        if ($path !== '/' && substr($path, -1) === '/') {
            $path = rtrim($path, '/');
        }

        foreach ($this->routes as $r) {
            if ($r['method'] !== $method) continue;
            if (preg_match($r['pattern'], $path, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                return $this->invoke($r['handler'], $params);
            }
        }
        http_response_code(404);
        echo '404 Not Found';
    }

    protected function invoke($handler, array $params = []) {
        if (is_array($handler) && count($handler) === 2) {
            [$class, $method] = $handler;
            if (is_string($class) && class_exists($class)) {
                $obj = new $class();
                if (method_exists($obj, $method)) {
                    // parametreleri sıralı diziye çevir
                    return call_user_func_array([$obj, $method], array_values($params));
                }
                throw new \RuntimeException("Method not found: {$class}::{$method}");
            }
            throw new \RuntimeException("Class not found: ".print_r($class,true));
        }
        if (is_callable($handler)) {
            return call_user_func_array($handler, array_values($params));
        }
        throw new \RuntimeException('Invalid route handler: '.var_export($handler,true));
    }
}