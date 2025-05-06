<?php
require_once 'controllers/Controller.php';

class CompanyController extends Controller {
    public function index() {
        $this->view('company/profile');
    }
}