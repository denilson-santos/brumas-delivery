<?php
namespace App\Controllers\admin;

use App\Controllers\Controller;
use App\Models\Category;
use App\Models\Restaurant;
use App\Models\User;

class RestaurantController extends Controller {  
    public function __construct($router) {
        parent::__construct($router);
        
        $user = new User();
        
        if (!$user->isLogged()) $router->redirect('name.login');
        if (!$user->isAuthorized(['admin', 'partner'])) $router->redirect('name.home');
    }

    public function index($request) {
        $user = new User();
        $restaurant = new Restaurant();
                
        $data = [];

        $data = [
            // 'restaurants' => $restaurant->getListRestaurants($offset, $limit, $filtersSelected),
            // 'restaurantsOpen' => $restaurant->getTotalRestaurantsOpen($filtersSelected, 'list'),
            // 'restaurantsClosed' => $restaurant->getTotalRestaurantsClosed($filtersSelected, 'list'),
            // 'restaurantsInPromotion' => $restaurant->getListRestaurants($offset, $limit, ['promotion' => 1]),
            // 'totalItens' => $restaurant->getTotalRestaurants($filtersSelected),
            // 'numberPages' => ceil($restaurant->getTotalRestaurants($filtersSelected) / $limit),
            // 'currentPage' => $currentPage,
            // 'categories' => $category->getListCategories(),
            // 'filtersSelected' => $filtersSelected,
            // 'filters' => $filter->getFilters($filtersSelected),
            // 'sidebarWidgetsFeatureds' => $restaurant->getListRestaurants(0, 5, ['featured' => 1], true),
            // 'footerWidgetsOnSale' => $restaurant->getListRestaurants(0, 3, ['promotion' => 1], true),
            // 'footerWidgetsTopRateds' => $restaurant->getListRestaurants(0, 3, ['top_rated' => 1], true),
            // 'footerWidgetsNew' => $restaurant->getListRestaurants(0, 3, ['new' => 1], true),
            'language' => $this->language->getLanguage(),
            'iniDicionary' => $this->language->getIniDicionary(),
            'userLogged' => $user->isLogged()
        ];

        $this->loadView('admin/pages/restaurant/details/details', $data);
    }

    public function restaurantEdit($request) {
        $user = new User();
        $restaurant = new Restaurant();
        $category = new Category();
                
        $data = [];

        $data = [
            // 'restaurants' => $restaurant->getListRestaurants($offset, $limit, $filtersSelected),
            // 'restaurantsOpen' => $restaurant->getTotalRestaurantsOpen($filtersSelected, 'list'),
            // 'restaurantsClosed' => $restaurant->getTotalRestaurantsClosed($filtersSelected, 'list'),
            // 'restaurantsInPromotion' => $restaurant->getListRestaurants($offset, $limit, ['promotion' => 1]),
            // 'totalItens' => $restaurant->getTotalRestaurants($filtersSelected),
            // 'numberPages' => ceil($restaurant->getTotalRestaurants($filtersSelected) / $limit),
            // 'currentPage' => $currentPage,
            'categories' => $category->getListCategories(),
            // 'filtersSelected' => $filtersSelected,
            // 'filters' => $filter->getFilters($filtersSelected),
            // 'sidebarWidgetsFeatureds' => $restaurant->getListRestaurants(0, 5, ['featured' => 1], true),
            // 'footerWidgetsOnSale' => $restaurant->getListRestaurants(0, 3, ['promotion' => 1], true),
            // 'footerWidgetsTopRateds' => $restaurant->getListRestaurants(0, 3, ['top_rated' => 1], true),
            // 'footerWidgetsNew' => $restaurant->getListRestaurants(0, 3, ['new' => 1], true),
            'language' => $this->language->getLanguage(),
            'iniDicionary' => $this->language->getIniDicionary(),
            'userLogged' => $user->isLogged()
        ];

        $this->loadView('admin/pages/restaurant/edit/edit', $data);
    }

    public function getRestaurantPlates($request) {
        $user = new User();
        $restaurant = new Restaurant();
                
        $data = [];

        $data = [
            // 'restaurants' => $restaurant->getListRestaurants($offset, $limit, $filtersSelected),
            // 'restaurantsOpen' => $restaurant->getTotalRestaurantsOpen($filtersSelected, 'list'),
            // 'restaurantsClosed' => $restaurant->getTotalRestaurantsClosed($filtersSelected, 'list'),
            // 'restaurantsInPromotion' => $restaurant->getListRestaurants($offset, $limit, ['promotion' => 1]),
            // 'totalItens' => $restaurant->getTotalRestaurants($filtersSelected),
            // 'numberPages' => ceil($restaurant->getTotalRestaurants($filtersSelected) / $limit),
            // 'currentPage' => $currentPage,
            // 'categories' => $category->getListCategories(),
            // 'filtersSelected' => $filtersSelected,
            // 'filters' => $filter->getFilters($filtersSelected),
            // 'sidebarWidgetsFeatureds' => $restaurant->getListRestaurants(0, 5, ['featured' => 1], true),
            // 'footerWidgetsOnSale' => $restaurant->getListRestaurants(0, 3, ['promotion' => 1], true),
            // 'footerWidgetsTopRateds' => $restaurant->getListRestaurants(0, 3, ['top_rated' => 1], true),
            // 'footerWidgetsNew' => $restaurant->getListRestaurants(0, 3, ['new' => 1], true),
            'language' => $this->language->getLanguage(),
            'iniDicionary' => $this->language->getIniDicionary(),
            'userLogged' => $user->isLogged()
        ];

        $this->loadView('admin/pages/restaurant/plates/plates', $data);
    }

    public function getRestaurantOrders($request) {
        $user = new User();
        $restaurant = new Restaurant();
                
        $data = [];

        $data = [
            // 'restaurants' => $restaurant->getListRestaurants($offset, $limit, $filtersSelected),
            // 'restaurantsOpen' => $restaurant->getTotalRestaurantsOpen($filtersSelected, 'list'),
            // 'restaurantsClosed' => $restaurant->getTotalRestaurantsClosed($filtersSelected, 'list'),
            // 'restaurantsInPromotion' => $restaurant->getListRestaurants($offset, $limit, ['promotion' => 1]),
            // 'totalItens' => $restaurant->getTotalRestaurants($filtersSelected),
            // 'numberPages' => ceil($restaurant->getTotalRestaurants($filtersSelected) / $limit),
            // 'currentPage' => $currentPage,
            // 'categories' => $category->getListCategories(),
            // 'filtersSelected' => $filtersSelected,
            // 'filters' => $filter->getFilters($filtersSelected),
            // 'sidebarWidgetsFeatureds' => $restaurant->getListRestaurants(0, 5, ['featured' => 1], true),
            // 'footerWidgetsOnSale' => $restaurant->getListRestaurants(0, 3, ['promotion' => 1], true),
            // 'footerWidgetsTopRateds' => $restaurant->getListRestaurants(0, 3, ['top_rated' => 1], true),
            // 'footerWidgetsNew' => $restaurant->getListRestaurants(0, 3, ['new' => 1], true),
            'language' => $this->language->getLanguage(),
            'iniDicionary' => $this->language->getIniDicionary(),
            'userLogged' => $user->isLogged()
        ];

        $this->loadView('admin/pages/restaurant/orders/orders', $data);
    }

    public function getRestaurantRates($request) {
        $user = new User();
                
        $data = [
            // 'restaurants' => $restaurant->getListRestaurants($offset, $limit, $filtersSelected),
            // 'restaurantsOpen' => $restaurant->getTotalRestaurantsOpen($filtersSelected, 'list'),
            // 'restaurantsClosed' => $restaurant->getTotalRestaurantsClosed($filtersSelected, 'list'),
            // 'restaurantsInPromotion' => $restaurant->getListRestaurants($offset, $limit, ['promotion' => 1]),
            // 'totalItens' => $restaurant->getTotalRestaurants($filtersSelected),
            // 'numberPages' => ceil($restaurant->getTotalRestaurants($filtersSelected) / $limit),
            // 'currentPage' => $currentPage,
            // 'categories' => $category->getListCategories(),
            // 'filtersSelected' => $filtersSelected,
            // 'filters' => $filter->getFilters($filtersSelected),
            // 'sidebarWidgetsFeatureds' => $restaurant->getListRestaurants(0, 5, ['featured' => 1], true),
            // 'footerWidgetsOnSale' => $restaurant->getListRestaurants(0, 3, ['promotion' => 1], true),
            // 'footerWidgetsTopRateds' => $restaurant->getListRestaurants(0, 3, ['top_rated' => 1], true),
            // 'footerWidgetsNew' => $restaurant->getListRestaurants(0, 3, ['new' => 1], true),
            'language' => $this->language->getLanguage(),
            'iniDicionary' => $this->language->getIniDicionary(),
            'userLogged' => $user->isLogged()
        ];

        $this->loadView('admin/pages/restaurant/rates/rates', $data);
    }
} 
