<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\Customer;

class CustomersController extends Controller {
    public function index() {
        $model = new Customer();
        $customers = $model->all();
        $this->view('customers/index', compact('customers'));
    }
}
