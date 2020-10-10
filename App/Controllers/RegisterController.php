<?php

namespace App\Controllers;

use App\Models\Restaurant;
use App\Models\User;

class RegisterController extends Controller {  
    public function registerIndex($request) {
        $data = [];           
        $this->loadView('pages/register/register', $data);
    }
    
    public function registerIndexAction($request) {
        $request['userLevel'] = $this->userLevels['customer'];
        
        $user = new User($request);

        $validation = $user->validateUserRegister();            

        if ($validation['validate']) {
            echo json_encode($validation);
        } else {
            echo json_encode($validation);
        }
    }
    
    public function registerPartnerIndex($request) {
        $data = [];
        $this->loadView('pages/register/registerPartner', $data);
    }

    public function registerPartnerAction($request) {        
        // print_r($request); exit;
        $request['userLevel'] = $this->userLevels['partner'];
        
        $request = $this->sanitizeInputs($request);
        
        $maskedFields = [
            'accountCellPhone', 
            'accountPhone', 
            'restaurantCnpj',
            'restaurantPhone',
            'restaurantCellPhone'
        ];

        $request = $this->clearMasks($request, $maskedFields);
        
        // $dateFields = ['nascimento'];
        // $request = $this->formatDates($request, $dateFields);
        
        // Group operation data
        $operation = array_slice($request, 26, 7, true);
        array_splice($request, 26, 7);

        $request['operation'] = $operation;

        $user = new User($request);

        $validation = $user->validateUserRegister();

        if ($validation['validate']) {
            echo json_encode($validation);
        } else {
            echo json_encode($validation);
        }
    }
}