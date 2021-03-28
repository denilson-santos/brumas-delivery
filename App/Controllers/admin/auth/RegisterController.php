<?php

namespace App\Controllers\admin\auth;

use App\Controllers\Controller;
use App\Models\Category;
use App\Models\Neighborhood;
use App\Models\User;

class RegisterController extends Controller {  
    public function registerCustomerIndex($request) {
        $neighborhood = new Neighborhood();
        $data = [];
        
        $data = [
            'neighborhoods' => $neighborhood->getListNeighborhoods(['city' => 336])
        ];           

        $this->loadView('admin/auth/register', $data);
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
        $category = new Category();
        $neighborhood = new Neighborhood();
        $data = [];
        
        $data = [
            'categories' => $category->getListCategories(),
            'neighborhoods' => $neighborhood->getListNeighborhoods(['city' => 336])
        ];   

        $this->loadView('admin/auth/registerPartner', $data);
    }

    public function registerPartnerAction($request) {
        $request['userLevel'] = $this->userLevels['partner'];
        $request['restaurantBrand'] = $_FILES['restaurantBrand'];
        
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
        $operation = array_slice($request, 26, 8, true);
        array_splice($request, 26, 8);

        $request['operation'] = $operation;


        $user = new User($request);
    
        $validation = $user->validateRegisterPartnerForm();

        if ($validation['validate']) {
            $user->saveRegisterPartnerForm();

            echo json_encode($validation);
        } else {
            echo json_encode($validation);
        }
    }

    public function checkEmail($request) {
        $request = $this->sanitizeInputs($request);
        
        $email = reset($request);

        $user = new User();

        if ($user->validateUniqueEmail($email)) {
            echo json_encode(true);
        } else {
            echo json_encode(false);
        }
    }

    public function checkUser($request) {
        $request = $this->sanitizeInputs($request);
        
        $userName = $request['accountUserName'];

        $user = new User();

        if ($user->validateUniqueUser($userName)) {
            echo json_encode(true);
        } else {
            echo json_encode(false);
        }
    }
}