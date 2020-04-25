<?php 
namespace Core;

use Config\Config;
use Core\Language;

class Controller {
    protected $language;

    public function __construct() {
        // $config = new Config();
        $this->language = new Language();
    }

    public function loadView($viewPatch, $viewData = []) {
        extract($viewData);
        require 'Views/'.$viewPatch.'.php';
    }

    public function loadViewNotExtract($viewPatch, $viewData = []) {
        require 'Views/'.$viewPatch.'.php';
    }

    public function loadTemplateDefault($viewPatch, $viewData = []) {
        require 'Views/templates/default.php';
    }

    public function loadTemplateHeaderFooter($viewPatch, $viewData = []) {
        require 'Views/templates/headerFooter.php';
    }

    public function loadViewInTemplate($viewPatch, $viewData = []) {
        extract($viewData);
        require 'Views/'.$viewPatch.'.php';
    }
}