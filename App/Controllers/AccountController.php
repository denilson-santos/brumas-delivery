<?php
namespace App\Controllers;

use App\Models\User;
use App\Controllers\Controller;


class AccountController extends Controller {
    private $userLevels;

    public function __construct($router) {
        parent::__construct($router);

        $this->userLevels = [
            'admin' => 1,
            'partner' => 2,
            'customer' => 3
        ];
    }

    public function registerIndex($request) {
        $data = [];           
        $this->loadView('pages/register/register', $data);
    }
    
    public function registerIndexAction($request) {
        $user = new User();
        
        $request['userLevel'] = $this->userLevels['customer'];
        
        $validation = $user->validateRegister($request);            

        if ($validation['validate']) {
            echo 'Validado!';
            print_r($validation);
        } else {
            echo 'Não Validado!';
            print_r($validation);
        }
    }
    
    public function registerPartnerIndex($request) {
        $data = [];
        $this->loadView('pages/register/registerPartner', $data);
    }

}
