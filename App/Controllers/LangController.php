<?php
namespace App\Controllers;

class LangController extends Controller {
    public function index() {

    }

    public function set($request) {
        $_SESSION['language'] = $request['language']; 
        return true;
    }
}