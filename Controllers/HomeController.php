<?php
namespace Controllers;

use Core\Controller;
use Models\Restaurant;
use Models\Category;
use Models\Filter;

class HomeController extends Controller {  
    public function index() {
        $restaurant = new Restaurant();
        $category = new Category();
        $filter = new Filter();
        
        $data = [];
        $filtersSelected = [];
        $currentPage = 1;
        $offset = 0;
        $limit = 9;

        if (isset($_GET['filters']) && is_array($_GET['filters'])) {
            $filtersSelected = $_GET['filters'];
        }

        if (isset($_GET['p'])) {
            $currentPage = $_GET['p'];
        }
            
        $offset = ($currentPage * $limit) - $limit;

        $data = [
            'restaurants' => $restaurant->getListRestaurants($offset, $limit, $filtersSelected),
            'restaurantsOpen' => $restaurant->getTotalRestaurantsOpen($filtersSelected, 'list'),
            'restaurantsClosed' => $restaurant->getTotalRestaurantsClosed($filtersSelected, 'list'),
            'totalItens' => $restaurant->getTotalRestaurants($filtersSelected),
            'numberPages' => ceil($restaurant->getTotalRestaurants($filtersSelected) / $limit),
            'currentPage' => $currentPage,
            'categories' => $category->getListCategories(),
            'filtersSelected' => $filtersSelected,
            'filters' => $filter->getFilters($filtersSelected),
            'sidebarWidgetsFeatureds' => $restaurant->getListRestaurants(0, 5, ['featured' => 1], true),
            'footerWidgetsOnSale' => $restaurant->getListRestaurants(0, 3, ['promotion' => 1], true),
            'footerWidgetsTopRateds' => $restaurant->getListRestaurants(0, 3, ['top_rated' => 1], true),
            'footerWidgetsNew' => $restaurant->getListRestaurants(0, 3, ['new' => 1], true)
        ];

        // print_r($data['categories']);
        // exit;
        $this->loadTemplateDefault('pages/home/home', $data);
    }

} 
