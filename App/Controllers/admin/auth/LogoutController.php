<?php

namespace App\Controllers\admin\auth;

use App\Controllers\Controller;
use App\Models\User;

class LogoutController extends Controller {   
    private $router;

    public function __construct($router) {
        parent::__construct($router);

        $this->router = $router;
    }
    
    public function logout($request) {
        $user = new User();
        $user->logout();   

        $this->router->redirect('name.home');
    }
}