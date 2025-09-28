<?php
namespace App\Controllers;
use App\Core\Validator;
use App\Core\Flash;
use App\Core\Controller;
use App\Models\Customer;

class CustomersController extends Controller {
    private function requireAuth() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['uid'])) { header('Location: /login'); exit; }
    }

    private function validateCustomer(array $in): array {
    $errors = [];

    // Zorunlular
    if ($in['name'] === '')          $errors['name'] = 'Ad Soyad zorunludur.';
    if ($in['type'] === '')          $errors['type'] = 'Cari tipi seçiniz.';
    if ($in['gsm'] === '')           $errors['gsm']  = 'Telefon zorunludur.';

    // Biçim kontrolleri
    if ($in['email'] !== '' && !filter_var($in['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'E-posta formatı geçersiz.';
    }
    if ($in['tc'] !== '' && !preg_match('/^\d{11}$/', $in['tc'])) {
        $errors['tc'] = 'TC Kimlik No 11 hane olmalı.';
    }
    // Telefon 10-11 hane (başında 0 olabilir diye "text" tutuyoruz)
    if ($in['gsm'] !== '' && !preg_match('/^\d{10,11}$/', $in['gsm'])) {
        $errors['gsm'] = 'Telefon 10–11 hane (sadece rakam) olmalı.';
    }
    if ($in['groom_phone'] !== '' && !preg_match('/^\d{10,11}$/', $in['groom_phone'])) {
        $errors['groom_phone'] = 'Damat telefonu 10–11 hane olmalı.';
    }

    return $errors;
    }

    public function index() {
        $this->requireAuth();
        $m = new Customer;
        $customers = $m->all(200);
        
        // Liste
        $this->view('customers/index', [
            'title' => 'Cari Listele',
            'active' => 'cari',
            'active_sub'  => 'list',
            'breadcrumbs' => ['Cari Yönetimi', 'Cari Listele'],
            'customers' => $customers
        ], 'layouts/main');
    }    

    

    // Cari İşlemleri (placeholder)
    public function transactions() {
    $this->requireAuth();
    $this->view('customers/transactions', [
        'title'       => 'Cari İşlemleri',
        'active'      => 'cari',
        'active_sub'  => 'ops',
        'breadcrumbs' => ['Cari Yönetimi', 'Cari İşlemleri'],
    ], 'layouts/main');
    }    

    /** Hızlı ekleme (modal) -> JSON */
    public function quickStore() {
        $this->requireAuth();

        $name     = trim($_POST['name'] ?? '');
        $phone    = Customer::sanitizePhone($_POST['phone'] ?? '');
        $phone2   = Customer::sanitizePhone($_POST['phone2'] ?? '');
        $country  = trim($_POST['country'] ?? 'Türkiye');
        $address  = trim($_POST['address'] ?? '');

        $errors=[];
        if ($name==='')  $errors['name']  = 'Ad Soyad zorunlu';
        if ($phone==='') $errors['phone'] = 'GSM zorunlu';

        header('Content-Type: application/json; charset=UTF-8');

        if ($errors) { http_response_code(422); echo json_encode(['ok'=>false,'errors'=>$errors]); return; }

        $data = [
            'name'      => $name,
            'phone'     => $phone,
            'phone2'    => $phone2 ?: null,
            'country'   => $country ?: null,
            'address'   => $address ?: null,
            'type'      => 'individual',
            'is_quick'  => 1,
        ];
        $id = (new Customer)->create($data);
        echo json_encode(['ok'=>true, 'id'=>$id, 'name'=>$name]);
    }

    /** Detaylı form – yeni kayıt */
    public function create() {
        $errors = Validator::errors();
        $old    = Validator::old();
        $this->requireAuth();
        // Yeni
        $this->view('customers/create', [
        'title'       => 'Yeni Cari',
        'errors'=> $errors,
        'old'   => $old,
        'active'      => 'cari',
        'active_sub'  => 'create',
        'breadcrumbs' => ['Cari Yönetimi', 'Yeni Cari'],
        ], 'layouts/main');
    } 
    

    /** Detaylı form POST */
    public function store() {
        $this->requireAuth();
        $type = ($_POST['type']??'individual')==='corporate' ? 'corporate' : 'individual';

        $data = [
            'type'        => $type,
            'is_quick'    => 0,
            'name'        => trim($_POST['name']??''),
            'email'       => trim($_POST['email']??'') ?: null,
            'phone'       => Customer::sanitizePhone($_POST['phone']??''),
            'phone2'      => Customer::sanitizePhone($_POST['phone2']??'') ?: null,
            'country'     => trim($_POST['country']??'Türkiye'),
            'state'       => trim($_POST['state']??'') ?: null,
            'district'    => trim($_POST['district']??'') ?: null,
            'address'     => trim($_POST['address']??'') ?: null,
            'category'    => trim($_POST['category']??'Müşteri'),
            'national_id' => trim($_POST['national_id']??'') ?: null,
            'groom_name'  => trim($_POST['groom_name']??'') ?: null,
            'groom_phone' => Customer::sanitizePhone($_POST['groom_phone']??'') ?: null,
            'groom_national_id' => trim($_POST['groom_national_id']??'') ?: null,
            'company_name'=> $type==='corporate' ? trim($_POST['company_name']??'') ?: null : null,
            'tax_number'  => $type==='corporate' ? trim($_POST['tax_number']??'') ?: null : null,
            'tax_office'  => $type==='corporate' ? trim($_POST['tax_office']??'') ?: null : null,
        ];

        if ($data['name']==='' || $data['phone']==='') {
            $_SESSION['flash_error'] = 'Ad Soyad ve GSM zorunlu.';
            header('Location: /customers/create'); exit;
        }

        $id = (new Customer)->create($data);
        header('Location: /customers/'.$id.'/edit'); // kayıttan sonra düzenlemeye
    }

    public function edit($id) {
        $this->requireAuth();
        $m = new Customer;
        $c = $m->find((int)$id);
        if (!$c) { http_response_code(404); echo 'Müşteri bulunamadı'; return; }
        $errors = Validator::errors();
        $old    = Validator::old();

        // Düzenle (liste sekmesi içinde kalabilir)
        $this->view('customers/edit', [
        'title'       => 'Cari Düzenle',
        'row'   => $row,
        'form'  => $form,
        'errors'=> $errors,
        'active'      => 'cari',
        'active_sub'  => 'list',
        'breadcrumbs' => ['Cari Yönetimi', 'Cari Listele', 'Düzenle'],
        'c'           => $c,
        ], 'layouts/main');
    }    
    

    public function update($id) {
        $this->requireAuth();
        $type = ($_POST['type']??'individual')==='corporate' ? 'corporate' : 'individual';

        $data = [
            'type'        => $type,
            'name'        => trim($_POST['name']??''),
            'email'       => trim($_POST['email']??'') ?: null,
            'phone'       => Customer::sanitizePhone($_POST['phone']??''),
            'phone2'      => Customer::sanitizePhone($_POST['phone2']??'') ?: null,
            'country'     => trim($_POST['country']??'Türkiye'),
            'state'       => trim($_POST['state']??'') ?: null,
            'district'    => trim($_POST['district']??'') ?: null,
            'address'     => trim($_POST['address']??'') ?: null,
            'category'    => trim($_POST['category']??'Müşteri'),
            'national_id' => trim($_POST['national_id']??'') ?: null,
            'groom_name'  => trim($_POST['groom_name']??'') ?: null,
            'groom_phone' => Customer::sanitizePhone($_POST['groom_phone']??'') ?: null,
            'groom_national_id' => trim($_POST['groom_national_id']??'') ?: null,
            'company_name'=> $type==='corporate' ? trim($_POST['company_name']??'') ?: null : null,
            'tax_number'  => $type==='corporate' ? trim($_POST['tax_number']??'') ?: null : null,
            'tax_office'  => $type==='corporate' ? trim($_POST['tax_office']??'') ?: null : null,
        ];

        $errors = $this->validateCustomer($in);
        if ($errors) {
            Validator::back($errors, $_POST, "/customers/$id/edit");
        }

        (new Customer)->updateById((int)$id, $data);
        Flash::set('success', 'Değişiklikler kaydedildi.');
        header('Location: /customers/'.$id.'/edit');
    }
}
