<?php 
namespace App\Controllers;

use App\Models\Category;
use App\Models\Plate;
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
        $plate = new Plate();
        
        $data = [];
       
        $restaurantInfo = $restaurant->getRestaurant($id);
 
        if (!empty($restaurantInfo)) {
            $restaurantId = $restaurantInfo['id_restaurant'];
                        
            $restaurantInfo['address'] = $restaurant->getRestaurantAddress($restaurantId);

            $restaurantInfo['phones'] = $restaurant->getRestaurantPhones($restaurantId);
            
            $restaurantInfo['operations'] = $restaurant->getRestaurantOperation($restaurantId);
            
            $restaurantInfo['social_medias'] = $restaurant->getRestaurantSocialMedias($restaurantId);

            $restaurantInfo['payments'] = $restaurant->getRestaurantPayments($restaurantId);
            
            $restaurantInfo['plates'] = $restaurant->getRestaurantPlates($restaurantId);

            $data = [
                'restaurantInfo' => $restaurantInfo,
                // 'restaurantImage' => $restaurant->getImagesByRestaurantId($id),
                'categories' => $category->getListCategories(),
                'categoriesOfRestaurant' => $plate->getCategoriesOfRestaurant($restaurantInfo['id_restaurant']),
                'language' => $this->language->getLanguage(),
                'iniDicionary' => $this->language->getIniDicionary(),
                'userLogged' => $user->isLogged(),
            ];

            $this->loadView('pages/restaurant/restaurant', $data);
        } else {
            header('Location: '.BASE_URL);
        }
    }
}
