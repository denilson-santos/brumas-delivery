<?php 
namespace Controllers;

use Core\Controller;
use Models\Category;
use Models\Filter;
use Models\Restaurant;

class SearchController extends Controller {
    public function index() {
        $restaurant = new Restaurant();
        $category = new Category();
        $filter = new Filter();
        
        $data = [];
        $filtersSelected = [];
        $currentPage = 1;
        $offset = 0;
        $limit = 6;

        if (!empty($_GET['term'])) {
            $searchTerm = $_GET['term'];

            if (!empty($_GET['filters']) && is_array($_GET['filters'])) {
                $filtersSelected = $_GET['filters'];
            }
            
            $filtersSelected['searchTerm'] = $searchTerm;

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
                'footerWidgetsNew' => $restaurant->getListRestaurants(0, 3, ['new' => 1], true)
            ];

            $this->loadTemplateDefault('pages/home/home', $data);
        } else {
            header('location: '.BASE_URL);
        }
    }
}
