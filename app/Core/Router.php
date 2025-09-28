<?php
namespace App\Core;

class Router {
    protected array $routes = [];

    public function get(string $path, $handler) { $this->add('GET', $path, $handler); }
    public function post(string $path, $handler) { $this->add('POST', $path, $handler); }

    protected function add(string $method, string $path, $handler) {
        $pattern = '#^' . preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_-]*)\}#', '(?P<$1>[^/]+)', $path) . '$#';
        $this->routes[] = compact('method','pattern','handler');
    }

    public function dispatch(string $method, string $uri) {
        $path = parse_url($uri, PHP_URL_PATH);
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

    protected function invoke($handler, array $params) {
        if (is_callable($handler)) return call_user_func_array($handler, $params);
        if (is_string($handler) && str_contains($handler, '@')) {
            [$class, $method] = explode('@', $handler, 2);
            $class = 'App\\Controllers\\' . $class;
            $c = new $class;
            return call_user_func_array([$c, $method], $params);
        }
        throw new \RuntimeException('Invalid route handler');
    }
}
