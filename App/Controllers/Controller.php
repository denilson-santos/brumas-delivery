<?php 
namespace App\Controllers;

use App\Config\Config;
use App\Config\Language;

class Controller {
    private $loader;
    private $twig;
    private $template;
    protected $language;

    public function __construct() {
        $this->loader = new \Twig\Loader\FilesystemLoader('App/Views');
        $this->twig = new \Twig\Environment($this->loader);
        $this->language = new Language();
    }

    public function loadView($viewName, $viewData = []) {
        $this->template = $this->twig->load($viewName.'.html.twig');
        $this->template = $this->template->render($viewData);
        echo $this->template;
    }

    // public function loadView($viewPatch, $viewData = []) {
    //     extract($viewData);
    //     require 'App/Views/'.$viewPatch.'.php';
    // }

    // public function loadViewNotExtract($viewPatch, $viewData = []) {
    //     require 'App/Views/'.$viewPatch.'.php';
    // }

    // public function loadTemplateDefault($viewPatch, $viewData = []) {
    //     require 'App/Views/templates/default.php';
    // }

    // public function loadTemplateHeaderFooter($viewPatch, $viewData = []) {
    //     require 'App/Views/templates/headerFooter.php';
    // }

    // public function loadViewInTemplate($viewPatch, $viewData = []) {
    //     extract($viewData);
    //     require 'App/Views/'.$viewPatch.'.php';
    // }
}