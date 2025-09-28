<?php
namespace App\Controllers;
use App\Core\Controller;

class HomeController extends Controller {
    
    
    public function index()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['uid'])) { header('Location: /login'); exit; }

        // Dashboard sayfasını CoreUI layout ile aç
        $this->view('home/index', [
            'title'       => 'Dashboard',
            'active'      => 'dashboard',
            'breadcrumbs' => ['Dashboard'],
        ], 'layouts/main');
    }

}
