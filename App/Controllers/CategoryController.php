<?php
namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\Category;
use App\Models\Filter;

class CategoryController extends Controller {  
    public function open($request) {
        $restaurant = new Restaurant();
        $category = new Category();
        $filter = new Filter();
        
        $id = $request['id'];
        $data = [];
        $filtersSelected = [];
        $currentPage = 1;
        $offset = 0;
        $limit = 9;

        if (isset($_GET['filters']) && is_array($_GET['filters'])) {
            $filtersSelected = $_GET['filters'];
        }

        $filtersSelected['category'] = $id;

        if (isset($_GET['p'])) {
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
            'iniDicionary' => $this->language->getIniDicionary()
        ];

        // print_r($data['restaurantsInPromotion']);
        // exit;
        $this->loadView('pages/home/home', $data);
    }

} 
