<?php
namespace App\Controllers;

use App\Core\Controller;

class ErrorController extends Controller {  
    public function index($data) {
        $this->loadView('pages/error/'.$data['error_code']);
    }

} 
