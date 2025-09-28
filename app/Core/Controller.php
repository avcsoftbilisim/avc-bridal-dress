<?php
namespace App\Core;

class Controller {
    protected function view(string $view, array $data = [], string $layout = 'layouts/main') {
        extract($data);
        $viewPath = __DIR__ . '/../Views/' . $view . '.php';
        $layoutPath = __DIR__ . '/../Views/' . $layout . '.php';
        ob_start();
        require $viewPath;
        $content = ob_get_clean();
        require $layoutPath;
    }

    protected function redirect(string $path) {
        header('Location: ' . $path); exit;
    }

    protected function isPost(): bool { return $_SERVER['REQUEST_METHOD'] === 'POST'; }
}
