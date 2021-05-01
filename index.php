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

    // Account
    $router->get('/account/profile', 'UserController:getProfile', 'name.account-profile');
    $router->post('/account/profile-action', 'UserController:editProfile');
    $router->get('/account/rates', 'UserController:getRates', 'name.account-rates');
    $router->get('/account/favorites', 'UserController:getFavorites', 'name.account-favorites');
    $router->get('/account/orders', 'UserController:getOrders', 'name.account-orders');
    $router->post('/account/orders/change-status', 'UserController:changeStatusOrder', 'name.account-change-status-order');
    $router->post('/account/orders/delete', 'UserController:deleteOrder', 'name.account-delete-order');
    $router->post('/account/favorite-status', 'UserController:changeFavoriteStatus', 'name.account-favorite-status');

    // Restaurant
    $router->get('/restaurant/details', 'RestaurantController:index', 'name.restaurant-details');
    $router->get('/restaurant/menu', 'RestaurantController:getRestaurantMenu', 'name.restaurant-menu');
    $router->post('/restaurant/menu', 'RestaurantController:saveRestaurantMenu', 'name.save-restaurant-menu');
    $router->get('/restaurant/plates', 'RestaurantController:getRestaurantPlates', 'name.restaurant-plates');
    $router->get('/restaurant/orders', 'RestaurantController:getRestaurantOrders', 'name.restaurant-orders');
    $router->post('/restaurant/orders/change-status', 'RestaurantController:changeStatusOrder', 'name.restaurant-change-status-order');
    $router->post('/restaurant/orders/delete', 'RestaurantController:deleteOrder', 'name.restaurant-delete-order');
    $router->get('/restaurant/rates', 'RestaurantController:getRestaurantRates', 'name.restaurant-rates');
    $router->get('/restaurant/edit', 'RestaurantController:restaurantEdit', 'name.restaurant-edit');
    $router->post('/restaurant/edit-action', 'RestaurantController:restaurantEditAction');

    /*
     * Site
    */ 
    $router->namespace("App\Controllers");  

    // Home
    $router->group(null);
    $router->get('/', 'HomeController:index', 'name.home');
    $router->get('/home', 'HomeController:index');
    $router->get('/category/{id}', 'CategoryController:open');

    // Restaurant
    $router->get('/restaurant/show/{id}', 'RestaurantController:getRestaurant');
    
    // Category
    $router->post('/category/delete', 'CategoryController:deletePlates');

    // Plate
    $router->get('/plate/{id}', 'PlateController:getPlate');
    $router->post('/plate/edit', 'PlateController:editPlate');
    $router->post('/plate/delete', 'PlateController:deletePlate');

    // Cart
    $router->get('/cart', 'CartController:index', 'name.cart');
    $router->post('/cart', 'CartController:getCart');
    $router->post('/cart/choose-payment', 'CartController:choosePayment');
    
    // Purchase
    $router->post('/cart/checkout', 'PurchaseController:checkout');
    
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

    