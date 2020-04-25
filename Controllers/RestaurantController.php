<?php 
namespace Controllers;

use Core\Controller;
use Models\Category;
use Models\Filter;
use Models\Restaurant;

class RestaurantController extends Controller {
    public function index() {
        header('Location: '.BASE_URL);        
    }

    public function open($id) {
        $restaurant = new Restaurant();
        $category = new Category();
        
        $data = [];
       
        $restaurantInfo = $restaurant->getRestaurant($id);
 
        if (!empty($restaurantInfo)) {
            $data = [
                'restaurantInfo' => $restaurantInfo,
                // 'restaurantImage' => $restaurant->getImagesByRestaurantId($id),
                'categories' => $category->getListCategories()
            ];

            $this->loadTemplateHeaderFooter('pages/restaurant/restaurant', $data);
        } else {
            header('Location: '.BASE_URL);
        }
    }
}
