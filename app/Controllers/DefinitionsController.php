<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Models\IncomeCategory;

class DefinitionsController extends Controller
{
    // /definitions -> varsayılan sekme: gelir kategorileri
    public function index() { return $this->incomeList(); }

    /* -------- GELİR KATEGORİLERİ -------- */

    public function incomeList()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['uid'])) { header('Location: /login'); exit; }

        $items = (new IncomeCategory())->listAll();
        $this->view('definitions/income', [
            'items'       => $items,
            'active'      => 'defs',            // sidebar aktiflik
            'sub'         => 'income',          // soldaki sekme aktiflik
            'breadcrumbs' => ['Tanımlamalar', 'Gelir Kategorileri'],
            'title'       => 'Gelir Kategorileri',
        ], 'layouts/main');
    }

    public function incomeCreate()
    {
        Csrf::check();
        $title = trim($_POST['title'] ?? '');
        $sort  = (int)($_POST['sort'] ?? 0);

        if ($title === '') {
            $_SESSION['flash_error'] = 'Başlık zorunludur.';
        } else {
            (new IncomeCategory())->create(['title'=>$title, 'sort'=>$sort]);
            $_SESSION['flash_ok'] = 'Kategori eklendi.';
        }
        header('Location: /definitions/income'); exit;
    }

    public function incomeUpdate($id)
    {
        Csrf::check();
        $title = trim($_POST['title'] ?? '');
        $sort  = (int)($_POST['sort'] ?? 0);

        if ($title === '') {
            $_SESSION['flash_error'] = 'Başlık zorunludur.';
        } else {
            (new IncomeCategory())->updateById((int)$id, ['title'=>$title, 'sort'=>$sort]);
            $_SESSION['flash_ok'] = 'Kategori güncellendi.';
        }
        header('Location: /definitions/income'); exit;
    }

    public function incomeDelete($id)
    {
        Csrf::check();
        (new IncomeCategory())->softDelete((int)$id);
        $_SESSION['flash_ok'] = 'Kategori silindi.';
        header('Location: /definitions/income'); exit;
    }
}