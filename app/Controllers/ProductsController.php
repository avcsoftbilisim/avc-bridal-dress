<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Models\Product;
use App\Models\IncomingProduct;

class ProductsController extends Controller
{
    public function index()
    {
        $p = new Product();
        $this->view('products/index', [
            'title'       => 'Tüm Ürünler',
            'active'      => 'products',
            'sub'         => 'all',
            'breadcrumbs' => ['Ürün Yönetimi', 'Tüm Ürünler'],
            'products'    => $p->all(50),
            'brands'      => $p->brands(),
            'categories'  => $p->categories(),
            'csrf'        => \App\Core\Csrf::token(), // form için
        ], 'layouts/main');
    }

    public function store()
    {
        // CSRF
        if (!Csrf::validate($_POST['csrf'] ?? '')) {
            http_response_code(419);
            echo json_encode(['ok'=>false,'message'=>'Oturum süresi doldu. Sayfayı yenileyin.']);
            return;
        }

        // Veri toplama
        $data = [
            'photo'                 => null,
            'type'                  => trim($_POST['type']     ?? ''),
            'name'                  => trim($_POST['name']     ?? ''),
            'barcode'               => trim($_POST['barcode']  ?? ''),
            'unit'                  => trim($_POST['unit']     ?? 'Adet'),
            'stock'                 => (int)($_POST['stock']   ?? 0),
            'usage_count'           => (int)($_POST['usage_count'] ?? 0),
            'brand_id'              => (int)($_POST['brand_id'] ?? 0),
            'category_id'           => (int)($_POST['category_id'] ?? 0),
            'buy_price'             => (float)($_POST['buy_price']  ?? 0),
            'sale_price'            => (float)($_POST['sale_price'] ?? 0),
            'rent_price'            => (float)($_POST['rent_price'] ?? 0),
            'vat_rate'              => (float)($_POST['vat_rate']   ?? 0),
            'min_sale_price'        => (float)($_POST['min_sale_price'] ?? 0),
            'min_rent_price'        => (float)($_POST['min_rent_price'] ?? 0),
            'rent_price_on_barcode' => isset($_POST['rent_price_on_barcode']) ? 1 : 0,
            'year'                  => (int)($_POST['year'] ?? 0),
            'size'                  => trim($_POST['size'] ?? ''),
        ];

        // Basit doğrulama
        $err = [];
        if ($data['name'] === '')                                   $err['name'] = 'Ürün adı zorunlu';
        if (!in_array($data['unit'], ['Adet','Takım','Çift','Metre'])) $data['unit']='Adet';
        if (($_POST['stock'] ?? '') !== '' && !preg_match('/^-?\d+$/', (string)$_POST['stock']))
            $err['stock'] = 'Stok sayı olmalı (sınırsız için -1)';
        if ($data['barcode'] !== '' && !preg_match('/^[0-9A-Za-z\-]+$/', $data['barcode']))
            $err['barcode'] = 'Barkod yalnız harf/rakam ve tire içerebilir';
        if ($data['buy_price'] < 0 || $data['sale_price'] < 0 || $data['rent_price'] < 0)
            $err['prices'] = 'Fiyatlar negatif olamaz';

        // Foto
        if (!empty($_FILES['photo']['name'])) {
            $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg','jpeg','png','webp'])) {
                $err['photo'] = 'Desteklenen türler: jpg, jpeg, png, webp';
            } else {
                $dir = __DIR__ . '/../../public/uploads/products';
                if (!is_dir($dir)) mkdir($dir, 0775, true);
                $name = uniqid('p_').'.'.$ext;
                if (!move_uploaded_file($_FILES['photo']['tmp_name'], $dir.'/'.$name)) {
                    $err['photo'] = 'Dosya yüklenemedi';
                } else {
                    $data['photo'] = $name;
                }
            }
        }

        if ($err) {
            http_response_code(422);
            echo json_encode(['ok'=>false,'errors'=>$err]);
            return;
        }

        $id = (new Product())->create($data);
        echo json_encode(['ok'=>true,'id'=>$id,'message'=>'Ürün kaydedildi']);
    }

    // Gelecek ürünler listesi
    public function incoming()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['uid'])) { header('Location: /login'); exit; }

        $page = max(1, (int)($_GET['p'] ?? 1));
        $model = new IncomingProduct();
        $list  = $model->paginate(20, $page);

        $this->view('products/incoming', [
            'items' => $list['rows'],
            'page'  => $list['page'],
            'total' => $list['total'],
            'limit' => $list['limit'],
            'title' => 'Gelecek Ürünler',
            'breadcrumbs' => ['Ürün Yönetimi', 'Gelecek Ürünler'],
            'active' => 'products-incoming',
        ], 'layouts/main');
    }

    // Modal POST (yeni gelecek ürün)
    public function incomingStore()
    {
        header('Content-Type: application/json; charset=utf-8');

        // Basit doğrulama + normalize
        $name   = trim($_POST['name'] ?? '');
        $barcode= trim($_POST['barcode'] ?? '');
        $unit   = trim($_POST['unit'] ?? 'Adet');
        $expstk = (int)($_POST['expected_stock'] ?? 0);
        $brand  = $_POST['brand_id'] ?? null;
        $cat    = $_POST['category_id'] ?? null;

        $buy    = (float)str_replace(',', '.', $_POST['buy_price']  ?? '0');
        $sale   = (float)str_replace(',', '.', $_POST['sale_price'] ?? '0');
        $rent   = (float)str_replace(',', '.', $_POST['rent_price'] ?? '0');
        $vat    = (float)str_replace(',', '.', $_POST['vat_rate']   ?? '0');

        $show   = isset($_POST['show_rent_on_barcode']) ? 1 : 0;

        $errors = [];
        if ($name === '')               $errors['name'] = 'Ürün adı zorunludur';
        if ($expstk < 0)                $errors['expected_stock'] = 'Stok 0’dan küçük olamaz';
        if ($buy   < 0 || $sale < 0 || $rent < 0) $errors['prices'] = 'Fiyatlar 0’dan küçük olamaz';
        if ($vat   < 0 || $vat > 40)    $errors['vat_rate'] = 'KDV 0-40 aralığında olmalı';

        if ($errors) {
            echo json_encode(['ok'=>false, 'errors'=>$errors]); return;
        }

        $model = new IncomingProduct();
        $id = $model->create([
            'name'                 => $name,
            'barcode'              => $barcode ?: null,
            'unit'                 => $unit ?: 'Adet',
            'expected_stock'       => $expstk,
            'brand_id'             => $brand ?: null,
            'category_id'          => $cat ?: null,
            'buy_price'            => $buy,
            'sale_price'           => $sale,
            'rent_price'           => $rent,
            'vat_rate'             => $vat,
            'show_rent_on_barcode' => $show,
        ]);

        echo json_encode(['ok'=>true, 'id'=>$id]);
    }


    // Modal DELETE (Ürün silme ve geri alma kurtarma durumları)
    public function deleted()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['uid'])) { header('Location:/login'); exit; }

        $page = max(1, (int)($_GET['p'] ?? 1));
        $q    = trim($_GET['q'] ?? '');

        $model = new Product();
        $list  = $model->paginateDeleted(20, $page, $q ?: null);

        $this->view('products/deleted', [
            'items' => $list['rows'],
            'page'  => $list['page'],
            'total' => $list['total'],
            'limit' => $list['limit'],
            'q'     => $q,
            'title' => 'Silinen Ürünler',
            'breadcrumbs' => ['Ürün Yönetimi', 'Silinen Ürünler'],
            'active' => 'products-deleted',
        ], 'layouts/main');
    }

    public function restore($id)
    {
        header('Content-Type: application/json; charset=utf-8');
        $id = (int)$id;
        $ok = (new Product())->restore($id);
        echo json_encode(['ok'=>$ok]);
    }

    public function purge($id)
    {
        header('Content-Type: application/json; charset=utf-8');
        $id = (int)$id;
        $ok = (new Product())->purge($id);
        echo json_encode(['ok'=>$ok]);
    }
}