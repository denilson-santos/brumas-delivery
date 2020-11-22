<?php

namespace App\Controllers\admin\auth;

use App\Controllers\Controller;
use App\Models\User;

class LoginController extends Controller {      
    public function index($request) {
        $data = [];
        $this->loadView('admin/auth/login', $data);
    }

    public function action($request) {
        $request = $this->sanitizeInputs($request);

        $user = new User($request);

        $validation = $user->validateLoginForm();            
        
        if ($validation['validate'] && $user->isAuthenticated()) {
            echo json_encode($validation);
        } else {
            echo json_encode([
                'validate' => false, 
                'errors' => ['Usuário e/ou senha incorretos!']
            ]);
        }
    }
}