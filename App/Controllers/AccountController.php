<?php
    namespace App\Controllers;

    use App\Controllers\Controller;

    class AccountController extends Controller {
        public function registerIndex($request) {
            $data = [];
            $this->loadView('pages/register/register', $data);
        }

        public function registerPartnerIndex($request) {
            $data = [];
            $this->loadView('pages/register/registerParter', $data);
        }
    }
