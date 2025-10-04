<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Tailor;
use App\Models\TailorJob;
use App\Models\Product;

class TailorsController extends Controller
{
    public function current() { return $this->list('current'); }
    public function past()    { return $this->list('past');    }
    public function future()  { return $this->list('future');  }

    private function list(string $scope)
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['uid'])) { header('Location: /login'); exit; }

        $title = [
            'current' => 'Terzideki Ürünler',
            'past'    => 'Geçmişte Terziye Verilenler',
            'future'  => 'Gelecekte Terziye Verilecekler',
        ][$scope] ?? 'Terzideki Ürünler';

        // Şimdilik boş veri ile sayfayı açalım (404 kalksın)
        $this->view('tailor/index', [
            'items'       => [],   // sonra dolduracağız
            'page'        => 1,
            'total'       => 0,
            'limit'       => 20,
            'q'           => '',
            'scope'       => $scope,
            'title'       => $title,
            'breadcrumbs' => ['Ürün Yönetimi', $title],
            'active'      => 'products',
            'sub'         => 'tailor',
        ], 'layouts/main');
    }

    /** Form */
    public function create(){
        if (session_status()!==PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['uid'])) { header('Location: /login'); exit; }

        $tailors  = (new Tailor())->options();
        $products = (new Product())->optionsForSelect(); // küçük bir “id, name” listesi döndürsün
        $this->view('tailors/create', [
          'tailors'=>$tailors, 'products'=>$products,
          'breadcrumbs'=>['Terzi Yönetimi','Terziye Ürün Gönder'],
          'active'=>'tailors-create'
        ], 'layouts/main');
    }

    /** Kaydet */
    public function store(){
        if (session_status()!==PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['uid'])) { header('Location: /login'); exit; }

        // basit doğrulama
        $tailor_id    = (int)($_POST['tailor_id'] ?? 0);
        $product_id   = (int)($_POST['product_id'] ?? 0);
        $product_name = trim($_POST['product_name'] ?? '');
        if (!$tailor_id || $product_name==='') {
            $_SESSION['flash_error'] = 'Terzi ve ürün adı zorunludur.';
            header('Location: /tailors/create'); exit;
        }

        (new TailorJob())->store([
          'tailor_id'=>$tailor_id,
          'product_id'=>$product_id ?: null,
          'product_name'=>$product_name,
          'note'=>$_POST['note'] ?? null,
          'price'=>$_POST['price'] ?? null,
          'due_at'=>($_POST['due_at'] ?? null) ?: null
        ]);

        $_SESSION['flash_ok'] = 'Kayıt oluşturuldu.';
        header('Location: /tailors'); exit;
    }

    /** Modal: yeni terzi (AJAX) */
    public function storeTailor(){
        header('Content-Type: application/json; charset=utf-8');
        $name = trim($_POST['name'] ?? '');
        if ($name===''){ echo json_encode(['ok'=>false,'msg'=>'Ad Soyad gerekli']); return; }
        $id = (new Tailor())->create([
          'name'=>$name,
          'phone'=>$_POST['phone']??null,
          'phone2'=>$_POST['phone2']??null,
          'address'=>$_POST['address']??null,
        ]);
        echo json_encode(['ok'=>true,'id'=>$id,'name'=>$name]);
    }

    public function markReturned($id){
        header('Content-Type: application/json; charset=utf-8');
        $ok = (new TailorJob())->markReturned((int)$id);
        echo json_encode(['ok'=>$ok]);
    }
}