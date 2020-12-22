<?php
    // Init session
    session_start();
    
    require 'vendor/autoload.php';

    use Dotenv\Dotenv;
    use App\Config\Config;
    use CoffeeCode\Router\Router;
    
    // display errors
    ini_set('display_errors', 'On');

    $dotenv = Dotenv::create(__DIR__);
    $dotenv->load();
    
    // Global vars of config
    $config = new Config();
    
    define('SERVER', $config->getServer());
    define('DB_NAME', $config->getDbName());
    define('USER', $config->getUser());
    define('PASSWORD', $config->getPassword());
    define('BASE_URL', $config->getCurrentBaseUrl());
    define('DEFAULT_LANG', $config->getDefaultLang());
    define('ENVIRONMENT', $config->getEnvironment());
    
    // Routes
    $router = new Router(BASE_URL);
    
    /*
     * (Auth) Admin Panel
    */ 
    $router->namespace("App\Controllers\admin\auth");
    
    // Login 
    $router->get('/login', 'LoginController:index', 'name.login');
    $router->post('/login-action', 'LoginController:action');

    // Logout
    $router->get('/logout', 'LogoutController:logout', 'name.logout');

    // Register
    $router->get('/register', 'RegisterController:registerCustomerIndex', 'name.register-customer');
    $router->post('/register-action', 'RegisterController:registerCustomerIndexAction');
    $router->get('/be-a-partner', 'RegisterController:registerPartnerIndex', 'name.register-partner');
    $router->post('/be-a-partner-action', 'RegisterController:registerPartnerAction');
    $router->post('/register/check-email', 'RegisterController:checkEmail');
    $router->post('/register/check-user', 'RegisterController:checkUser');

    /*
     * Admin Panel
    */
    $router->namespace("App\Controllers\admin");

    // Acount
    $router->get('/profile', 'UserController:index', 'name.profile');
    $router->get('/orders', 'PurchaseController:index', 'name.orders');

    // Restaurant
    $router->get('/restaurant/details', 'RestaurantController:index', 'name.restaurant-details');
    $router->get('/restaurant/plates', 'RestaurantController:index', 'name.restaurant-plates');
    $router->get('/restaurant/orders', 'RestaurantController:index', 'name.restaurant-orders');

    /*
     * Site
    */ 
    $router->namespace("App\Controllers");

    // Home
    $router->group(null);
    $router->get('/', 'HomeController:index', 'name.home');
    $router->get('/home', 'HomeController:index');
    $router->get('/category/{id}', 'CategoryController:open');

    // Language
    $router->post('/lang', 'LangController:set');

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

    