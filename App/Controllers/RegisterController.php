<?php

namespace Controllers;

use Core\Controller;

class RegisterController extends Controller {  
    public function index() {
        $data = [];
        $this->loadView('pages/register/register', $data);
    }
}