<?php
namespace App\Controllers;

use App\Controllers\Controller;

class ErrorController extends Controller {  
    public function index($data) {
        $this->loadView('pages/error/'.$data['error_code']);
    }

} 
