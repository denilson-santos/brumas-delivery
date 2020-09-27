<?php
    // session_start();
    
    require 'vendor/autoload.php';

    use Dotenv\Dotenv;
    use App\Config\Config;
    use CoffeeCode\Router\Router;

    // display errors
    ini_set('display_errors', 'On');
    
    $dotenv = Dotenv::create(__DIR__);
    $dotenv->load();
    
    $config = new Config();
    define('BASE_URL', $config->getCurrentBaseUrl());

    
    // Routes
    $router = new Router(BASE_URL);
    
    /*
     * Admin Panel
    */ 
    $router->namespace("App\Controllers\admin");

    // Dashboard
    $router->group('admin');
    $router->get('/', 'DashboardController:index');
    $router->get('/dashboard', 'DashboardController:index');

    // Login admin

    /*
     * Site
    */ 
    $router->namespace("App\Controllers");

    // Home
    $router->group(null);
    $router->get('/', 'HomeController:index');
    $router->get('/home', 'HomeController:index');
    $router->get('/category/{id}', 'CategoryController:open');

    // Login 

    // Register
    $router->get('/register', 'RegisterController:registerIndex');
    $router->post('/register-action', 'RegisterController:registerIndexAction');
    $router->get('/be-a-partner', 'RegisterController:registerPartnerIndex');
    $router->post('/be-a-partner-action', 'RegisterController:registerPartnerAction');

    // Error
    // 400 Bad Request
    // 404 Not Found
    // 405 Method Not Allowed 
    // 501 Not Implemented
    $router->group("ooops");
    $router->get("/{error_code}", "ErrorController:index");

    $router->dispatch();

    // if ($router->error()) {
    //     $router->redirect("/ooops/{$router->error()}");
    // }

    