<?php 
namespace Controllers;

use Core\Controller;
use Models\Category;
use Models\Filter;
use Models\Restaurant;

class SearchController extends Controller {
    public function index() {
        $restaurant = new Plate();
        $category = new Category();
        $filter = new Filter();
        
        $data = [];
        $filtersSelected = [];
        $currentPage = 1;
        $offset = 0;
        $limit = 6;

        if (!empty($_GET['term'])) {
            $searchTerm = $_GET['term'];
            $categorySearch = $_GET['category'];

            if (!empty($_GET['filters']) && is_array($_GET['filters'])) {
                $filtersSelected = $_GET['filters'];
            }
            
            $filtersSelected['searchTerm'] = $searchTerm;
            $filtersSelected['category'] = $categorySearch;

            if (!empty($_GET['p'])) {
                $currentPage = $_GET['p'];
            }
                
            $offset = ($currentPage * $limit) - $limit;

            $data = [
                'restaurants' => $restaurant->getListRestaurants($offset, $limit, $filtersSelected),
                'totalItens' => $restaurant->getTotalRestaurants($filtersSelected),
                'numberPages' => ceil($restaurant->getTotalRestaurants($filtersSelected) / $limit),
                'currentPage' => $currentPage,
                'categories' => $category->getListCategories(),
                'filters' => $filter->getFilters($filtersSelected),
                'filtersSelected' => $filtersSelected,
                'searchTerm' => $searchTerm,
                'category' => (!empty($categorySearch) ? $categorySearch : ''),
                'categoryFilter' => (!empty($categorySearch) ? $category->getCategoryTree($categorySearch) : '')
            ];

            $this->loadTemplate('search', $data);
        } else {
            header('location: '.BASE_URL);
        }
    }
}
