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
        $user = new User();
        
        $request['userLevel'] = $this->userLevels['customer'];

        $validation = $user->validateRegister($request);            

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
        $user = new User();
        
        $request['userLevel'] = $this->userLevels['partner'];

        $request = $this->sanitizeInputs($request);

        $maskedFields = [
            'cellPhone', 
            'phone', 
            'restaurantCnpj',
            'restaurantPhone',
            'restaurantCellPhone'
        ];

        $request = $this->clearMasks($request, $maskedFields);

        // $DateFields = ['nascimento'];
        // $request = $this->formatDates($request, $DateFields);
        
        $validation = $user->validateRegister($request);
    }
}