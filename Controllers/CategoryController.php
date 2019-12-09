<?php
namespace Controllers;

use Core\Controller;
use Models\Restaurant;
use Models\Category;
use Models\Filter;

class CategoryController extends Controller {  
    public function index() {
        header('Location: '.BASE_URL);
    }

    public function enter($id) {
        $restaurant = new Restaurant();
        $category = new Category();
        $filter = new Filter();
        
        $data = [];
        $filtersSelected = [];
        $currentPage = 1;
        $offset = 0;
        $limit = 6;

        if (isset($_GET['filters']) && is_array($_GET['filters'])) {
            $filtersSelected = $_GET['filters'];
        }

        if (!empty($_GET['p'])) {
            $currentPage = $_GET['p'];
        }

        $offset = ($currentPage * $limit) - $limit;

        if (!empty($category->getCategoryName($id))) {
            $filtersSelected['category'] = $id;

            $data = [
                'categoryName' => $category->getCategoryName($id),
                // 'categories' => $category->getListCategories(),
                // 'categoryFilter' => $category->getCategoryTree($id),
                'restaurants' => $restaurant->getListRestaurants($offset, $limit, $filtersSelected),
                'totalItens' => $restaurant->getTotalRestaurants($filtersSelected),
                'numberPages' => ceil($restaurant->getTotalRestaurants($filtersSelected) / $limit),
                'currentPage' => $currentPage,
                'categoryId' => $id,
                'filtersSelected' => $filtersSelected,
                'filters' => $filter->getFilters($filtersSelected),
                // 'sidebarWidgetsFeatured' => $restaurant->getListRestaurants(0, 5, ['featured' => 1], true),
                // 'footerWidgetsFeatured' => $restaurant->getListRestaurants(0, 3, ['featured' => 1], true),
                // 'widgetsPromotion' => $restaurant->getListRestaurants(0, 3, ['promo' => 1], true),
                // 'widgetsTopRated' => $restaurant->getListRestaurants(0, 3, ['top_rated' => 1]),
            ];

            $this->loadTemplateDefault('pages/home/home', $data);
        } else {
            header('Location: '.BASE_URL);
        }
    }

} 
