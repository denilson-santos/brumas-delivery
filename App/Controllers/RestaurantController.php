<?php 
namespace App\Controllers;

use App\Models\Category;
use App\Models\Restaurant;
use App\Models\User;

class RestaurantController extends Controller {
    public function index() {
        header('Location: '.BASE_URL);        
    }

    public function getRestaurant($request) {
        $id = $request['id'];

        $restaurant = new Restaurant();
        $category = new Category();
        $user = new User();
        
        $data = [];
       
        $restaurantInfo = $restaurant->getRestaurant($id);
 
        if (!empty($restaurantInfo)) {
            $data = [
                'restaurantInfo' => $restaurantInfo,
                // 'restaurantImage' => $restaurant->getImagesByRestaurantId($id),
                'categories' => $category->getListCategories(),
                'language' => $this->language->getLanguage(),
                'iniDicionary' => $this->language->getIniDicionary(),
                'userLogged' => $user->isLogged()
            ];

            $this->loadView('pages/restaurant/restaurant', $data);
        } else {
            header('Location: '.BASE_URL);
        }
    }
}
