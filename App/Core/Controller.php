<?php 
namespace App\Core;

use App\Config\Config;
use App\Core\Language;

class Controller {
    protected $language;

    public function __construct() {
        // $config = new Config();
        $this->language = new Language();
    }

    public function loadView($viewPatch, $viewData = []) {
        extract($viewData);
        require 'App/Views/'.$viewPatch.'.php';
    }

    public function loadViewNotExtract($viewPatch, $viewData = []) {
        require 'App/Views/'.$viewPatch.'.php';
    }

    public function loadTemplateDefault($viewPatch, $viewData = []) {
        require 'App/Views/templates/default.php';
    }

    public function loadTemplateHeaderFooter($viewPatch, $viewData = []) {
        require 'App/Views/templates/headerFooter.php';
    }

    public function loadViewInTemplate($viewPatch, $viewData = []) {
        extract($viewData);
        require 'App/Views/'.$viewPatch.'.php';
    }
}