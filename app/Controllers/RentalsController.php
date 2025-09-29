<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Rental;

class RentalsController extends Controller
{
    public function current() { $this->list('ongoing'); }
    public function past()    { $this->list('past');    }
    public function future()  { $this->list('future');  }

    private function list(string $scope)
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['uid'])) { header('Location: /login'); exit; }

        $page = max(1, (int)($_GET['p'] ?? 1));
        $q    = trim($_GET['q'] ?? '');

        $m = new Rental();
        $list = $m->paginate($scope, 20, $page, $q ?: null);

        $title = $scope==='past' ? 'Geçmiş Kiralar' : ($scope==='future' ? 'Gelecek Kiralar' : 'Kiradaki Ürünler');

        $this->view('rentals/index', [
            'items'       => $list['rows'],
            'page'        => $list['page'],
            'total'       => $list['total'],
            'limit'       => $list['limit'],
            'q'           => $q,
            'scope'       => $scope,
            'title'       => $title,
            'breadcrumbs' => ['Kiralama Yönetimi', $title],
            'active'      => 'rentals-'.$scope,
        ], 'layouts/main');
    }

    // Teslim al (dönüş)
    public function markReturned($id)
    {
        header('Content-Type: application/json; charset=utf-8');
        $ok = (new Rental())->markReturned((int)$id);
        echo json_encode(['ok'=>$ok]);
    }
}