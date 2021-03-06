<?php 
namespace App\Controllers;

use App\Models\Category;
use App\Models\Neighborhood;
use App\Models\Plate;
use App\Models\Restaurant;
use App\Models\SocialMedia;
use App\Models\User;
use App\Models\WeekDay;

class RestaurantController extends Controller {
    public function index() {
        header('Location: '.BASE_URL);        
    }

    public function getRestaurant($request) {
        $id = $request['id'];

        $restaurant = new Restaurant();
        $category = new Category();
        $user = new User();
        $neighborhood = new Neighborhood();
        $plate = new Plate();
        $socialMedia = new SocialMedia();
        $weekDay = new WeekDay();
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
                'restaurantFavorites' => $user->getFavorites($user->isLogged()['id_user']),
                // 'restaurantImage' => $restaurant->getImagesByRestaurantId($id),
                'neighborhoods' => $neighborhood->getListNeighborhoods(),
                'categories' => $category->getListCategories(),
                'categoriesOfRestaurant' => $plate->getCategoriesOfRestaurant($restaurantInfo['id_restaurant']),
                'weekDays' => $weekDay->getListWeekDays(),
                'socialMedias' => $socialMedia->getListSocialMedias(),
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
