<?php 
namespace App\Controllers;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use App\Config\Config;
use App\Config\Language;
use Twig\TwigFunction;
use voku\helper\AntiXSS;

class Controller {
    private $loader;
    private $twig;
    private $template;
    private $config;
    
    protected $antiXss;
    protected $language;
    protected $userLevels; 
    
    public function __construct($router) {
        $this->config = new Config();
        $this->loader = new FilesystemLoader('App/Views');
        $this->antiXss = new AntiXSS();
        $this->language = new Language();
        $this->twig = new Environment($this->loader);

        $this->twig->addGlobal('GET_URL', $_GET);
        $this->twig->addGlobal('router', $router);

        $this->userLevels = [
            'admin' => 1,
            'partner' => 2,
            'customer' => 3
        ];
        // print_r($_GET); exit;

        // Add function of PHP to use in twig 
        
        // empty
        $this->twig->addFunction(new TwigFunction('empty', 'empty'));
        $this->twig->addFunction(new TwigFunction('notEmpty', '!empty'));

        // isset
        $this->twig->addFunction(new TwigFunction('isset', 'isset'));
        $this->twig->addFunction(new TwigFunction('notIsset', '!isset'));

        // pathinfo
        $this->twig->addFunction(new TwigFunction('pathinfo', 'pathinfo'));
        
        // in_array
        $this->twig->addFunction(new TwigFunction('in_array', 'in_array'));
        
        // array_filter
        $this->twig->addFunction(new TwigFunction('array_filter', 'array_filter'));
        
        // array_push
        $this->twig->addFunction(new TwigFunction('array_push', 'array_push'));
        
        // array_key_exists
        $this->twig->addFunction(new TwigFunction('array_key_exists', 'array_key_exists'));
        
        // array_fill_keys
        $this->twig->addFunction(new TwigFunction('array_fill_keys', 'array_fill_keys'));
        
        // print_r
        $this->twig->addFunction(new TwigFunction('print_r', 'print_r'));
        
        // implode
        $this->twig->addFunction(new TwigFunction('implode', 'implode'));
        
        // explode
        $this->twig->addFunction(new TwigFunction('explode', 'explode'));
        
        // strlen
        $this->twig->addFunction(new TwigFunction('strlen', 'strlen'));
        
        // substr
        $this->twig->addFunction(new TwigFunction('substr', 'substr'));
        
        // number_format
        $this->twig->addFunction(new TwigFunction('number_format', 'number_format'));
        
        // http_build_query
        $this->twig->addFunction(new TwigFunction('http_build_query', 'http_build_query'));

        // getCurrentBaseUrl
        $this->twig->addFunction(new TwigFunction('getCurrentBaseUrl', [$this->config,'getCurrentBaseUrl']));
    }

    public function loadView($viewName, $viewData = []) {
        $this->template = $this->twig->render($viewName.'.html.twig', $viewData);
        echo $this->template;
    }

    public function sanitizeInputs($request) {
        foreach ($request as $field => $value) {
            $request[$field] = $this->antiXss->xss_clean($value);
        }

        return $request;
    }

    public function clearMasks($request, $keys) {
        foreach ($request as $field => $value) {
            if (in_array($field, $keys)) {
                // Regex for remove space, bars, dots and parentheses
                $request[$field] = preg_replace('/\s|\/|\-|\.|\(|\)/', '', $value);
            }
        }

        return $request;
    }

    public function formatDates($request, $keys) {
        foreach ($request as $field => $value) {
            if (in_array($field, $keys)) {
                $request[$field] = date('Y-m-d', $value);
            }
        }

        return $request;
    }
}