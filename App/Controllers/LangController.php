<?php
namespace Controllers;

use App\Controllers\Controller;

class LangController extends Controller {
    public function index() {

    }

    public function set($lang) {
        $_SESSION['language'] = $lang; 
        header('Location: '.BASE_URL);
    }
}