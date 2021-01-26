<?php
namespace App\Controllers;

use App\Models\Restaurant;
use App\Models\Category;
use App\Models\Filter;
use App\Models\User;

class HomeController extends Controller {  
    public function index($request) {
        $restaurant = new Restaurant();
        $category = new Category();
        $filter = new Filter();
        $user = new User();
        
        $data = [];
        $filtersSelected = [];
        $currentPage = 1;
        $offset = 0;
        $limit = 9;

        if (!empty($_GET['filters']) && is_array($_GET['filters'])) {
            $filtersSelected = $_GET['filters'];
        }

        if (!empty($_GET['category'])) {
            $filtersSelected['category'] = $_GET['category'];
        }

        if (!empty($_GET['p'])) {
            $currentPage = $_GET['p'];
        }

        $offset = ($currentPage * $limit) - $limit;

        $data = [
            'restaurants' => $restaurant->getListRestaurants($offset, $limit, $filtersSelected),
            'restaurantsOpen' => $restaurant->getTotalRestaurantsOpen($filtersSelected, 'list'),
            'restaurantsClosed' => $restaurant->getTotalRestaurantsClosed($filtersSelected, 'list'),
            'restaurantsInPromotion' => $restaurant->getListRestaurants($offset, $limit, ['promotion' => 1]),
            'totalItens' => $restaurant->getTotalRestaurants($filtersSelected),
            'numberPages' => ceil($restaurant->getTotalRestaurants($filtersSelected) / $limit),
            'currentPage' => $currentPage,
            'categories' => $category->getListCategories(),
            'filtersSelected' => $filtersSelected,
            'filters' => $filter->getFilters($filtersSelected),
            'sidebarWidgetsFeatureds' => $restaurant->getListRestaurants(0, 5, ['featured' => 1], true),
            'footerWidgetsOnSale' => $restaurant->getListRestaurants(0, 3, ['promotion' => 1], true),
            'footerWidgetsTopRateds' => $restaurant->getListRestaurants(0, 3, ['top_rated' => 1], true),
            'footerWidgetsNew' => $restaurant->getListRestaurants(0, 3, ['new' => 1], true),
            'language' => $this->language->getLanguage(),
            'iniDicionary' => $this->language->getIniDicionary(),
            'userLogged' => $user->isLogged()
        ];

        // print_r($data['userLogged']);
        // exit;
        $this->loadView('pages/home/home', $data);
    }
} 
