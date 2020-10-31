<?php

namespace App\Controllers;

use App\Models\Neighborhood;
use App\Models\User;

class RegisterController extends Controller {  
    public function registerCustomerIndex($request) {
        $neighborhood = new Neighborhood();
        $data = [];
        
        $data = [
            'neighborhoods' => $neighborhood->getListNeighborhoods(['city' => 336])
        ];           

        $this->loadView('pages/register/register', $data);
    }
    
    public function registerCustomerIndexAction($request) {
        $request['userLevel'] = $this->userLevels['customer'];

        $request = $this->sanitizeInputs($request);

        $maskedFields = [
            'accountCellPhone'
        ];

        $request = $this->clearMasks($request, $maskedFields);
        
        $user = new User($request);

        $validation = $user->validateRegisterCustomerForm();            

        if ($validation['validate']) {
            $user->saveRegisterCustomerForm();

            echo json_encode($validation);
        } else {
            echo json_encode($validation);
        }
    }
    
    public function registerPartnerIndex($request) {
        $neighborhood = new Neighborhood();
        $data = [];
        
        $data = [
            'neighborhoods' => $neighborhood->getListNeighborhoods(['city' => 336])
        ];   

        $this->loadView('pages/register/registerPartner', $data);
    }

    public function registerPartnerAction($request) {        
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

        $validation = $user->validateRegisterPartnerForm();

        if ($validation['validate']) {
            echo json_encode($validation);
        } else {
            echo json_encode($validation);
        }
    }
}