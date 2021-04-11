<?php
namespace App\Controllers\admin;

use App\Models\User;
use App\Controllers\Controller;
use App\Models\Category;
use App\Models\Complement;
use App\Models\Filter;
use App\Models\Neighborhood;
use App\Models\Plate;
use App\Models\Purchase;
use App\Models\PurchasePlate;
use App\Models\Restaurant;

class UserController extends Controller {
    public function __construct($router) {
        parent::__construct($router);
        
        $user = new User();
        
        if (!$user->isLogged()) $router->redirect('name.login');
        if (!$user->isAuthorized(['admin', 'partner', 'customer'])) $router->redirect('name.home');
    }

    public function getProfile($request) {
        $user = new User();
        $neighborhood = new Neighborhood();

        $data = [
            // 'restaurants' => $restaurant->getListRestaurants($offset, $limit, $filtersSelected),
            // 'restaurantsOpen' => $restaurant->getTotalRestaurantsOpen($filtersSelected, 'list'),
            // 'restaurantsClosed' => $restaurant->getTotalRestaurantsClosed($filtersSelected, 'list'),
            // 'restaurantsInPromotion' => $restaurant->getListRestaurants($offset, $limit, ['promotion' => 1]),
            // 'totalItens' => $restaurant->getTotalRestaurants($filtersSelected),
            // 'numberPages' => ceil($restaurant->getTotalRestaurants($filtersSelected) / $limit),
            // 'currentPage' => $currentPage,
            // 'categories' => $category->getListCategories(),
            // 'filtersSelected' => $filtersSelected,
            // 'filters' => $filter->getFilters($filtersSelected),
            // 'sidebarWidgetsFeatureds' => $restaurant->getListRestaurants(0, 5, ['featured' => 1], true),
            // 'footerWidgetsOnSale' => $restaurant->getListRestaurants(0, 3, ['promotion' => 1], true),
            // 'footerWidgetsTopRateds' => $restaurant->getListRestaurants(0, 3, ['top_rated' => 1], true),
            // 'footerWidgetsNew' => $restaurant->getListRestaurants(0, 3, ['new' => 1], true),
            'neighborhoods' => $neighborhood->getListNeighborhoods(['city' => 336]),
            'language' => $this->language->getLanguage(),
            'iniDicionary' => $this->language->getIniDicionary(),
            'userLogged' => $user->isLogged()
        ];

        $this->loadView('admin/pages/account/profile/profile', $data);
    }

    public function editProfile($request) {        
        $headers = getallheaders();

        if (array_key_exists('accountPhoto', $_FILES)) {
            $request['accountPhoto'] = $_FILES['accountPhoto'];
        }
        
        $request = $this->sanitizeInputs($request);

        if (array_key_exists('accountCellPhone', $request)) {
            $maskedFields = ['accountCellPhone'];
            
            $request = $this->clearMasks($request, $maskedFields);
        }

        $user = new User($request);
        
        $validation = $user->validateEditProfileForm();

        if ($validation['validate']) {
            $user->saveEditProfileForm($headers['User-Id'], $headers['Address-Id']);

            echo json_encode($validation);
        } else {
            echo json_encode($validation);
        }
    }

    public function getRates($request) {
        $user = new User();
                
        $data = [];

        $data = [
            // 'restaurants' => $restaurant->getListRestaurants($offset, $limit, $filtersSelected),
            // 'restaurantsOpen' => $restaurant->getTotalRestaurantsOpen($filtersSelected, 'list'),
            // 'restaurantsClosed' => $restaurant->getTotalRestaurantsClosed($filtersSelected, 'list'),
            // 'restaurantsInPromotion' => $restaurant->getListRestaurants($offset, $limit, ['promotion' => 1]),
            // 'totalItens' => $restaurant->getTotalRestaurants($filtersSelected),
            // 'numberPages' => ceil($restaurant->getTotalRestaurants($filtersSelected) / $limit),
            // 'currentPage' => $currentPage,
            // 'categories' => $category->getListCategories(),
            // 'filtersSelected' => $filtersSelected,
            // 'filters' => $filter->getFilters($filtersSelected),
            // 'sidebarWidgetsFeatureds' => $restaurant->getListRestaurants(0, 5, ['featured' => 1], true),
            // 'footerWidgetsOnSale' => $restaurant->getListRestaurants(0, 3, ['promotion' => 1], true),
            // 'footerWidgetsTopRateds' => $restaurant->getListRestaurants(0, 3, ['top_rated' => 1], true),
            // 'footerWidgetsNew' => $restaurant->getListRestaurants(0, 3, ['new' => 1], true),
            'language' => $this->language->getLanguage(),
            'iniDicionary' => $this->language->getIniDicionary(),
            'userLogged' => $user->isLogged()
        ];

        $this->loadView('admin/pages/account/rates/rates', $data);
    }

    public function getFavorites($request) {
        $user = new User();
        $restaurant = new Restaurant();
        $category = new Category();
        $filter = new Filter();
        
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

        $this->loadView('admin/pages/account/favorites/favorites', $data);
    }

    public function getOrders($request) {
        $user = new User();
        $plate = new Plate();
        $purchase = new Purchase();
        $purchasePlate = new PurchasePlate();
                
        $data = [
            // 'restaurants' => $restaurant->getListRestaurants($offset, $limit, $filtersSelected),
            // 'restaurantsOpen' => $restaurant->getTotalRestaurantsOpen($filtersSelected, 'list'),
            // 'restaurantsClosed' => $restaurant->getTotalRestaurantsClosed($filtersSelected, 'list'),
            // 'restaurantsInPromotion' => $restaurant->getListRestaurants($offset, $limit, ['promotion' => 1]),
            // 'totalItens' => $restaurant->getTotalRestaurants($filtersSelected),
            // 'numberPages' => ceil($restaurant->getTotalRestaurants($filtersSelected) / $limit),
            // 'currentPage' => $currentPage,
            // 'categories' => $category->getListCategories(),
            // 'filtersSelected' => $filtersSelected,
            // 'filters' => $filter->getFilters($filtersSelected),
            // 'sidebarWidgetsFeatureds' => $restaurant->getListRestaurants(0, 5, ['featured' => 1], true),
            // 'footerWidgetsOnSale' => $restaurant->getListRestaurants(0, 3, ['promotion' => 1], true),
            // 'footerWidgetsTopRateds' => $restaurant->getListRestaurants(0, 3, ['top_rated' => 1], true),
            // 'footerWidgetsNew' => $restaurant->getListRestaurants(0, 3, ['new' => 1], true),
            'purchases' => $user->getPurchases($user->isLogged()['id_user']),
            'language' => $this->language->getLanguage(),
            'iniDicionary' => $this->language->getIniDicionary(),
            'userLogged' => $user->isLogged()
        ];

        if (!empty($data['purchases'])) {
            foreach ($data['purchases'] as $pkey => $purchaseData) {
                $data['purchases'][$pkey]['purchasePlates'] = $purchase->getPurchasePlates($purchaseData['id_purchase']);
                
                foreach ($data['purchases'][$pkey]['purchasePlates'] as $pukey => $purchasePlateData) {
                    $data['purchases'][$pkey]['purchasePlates'][$pukey]['plate'] = $plate->getPlate($purchasePlateData['plate_id']);
                    
                    $data['purchases'][$pkey]['purchasePlates'][$pukey]['plate']['complements'] = $purchasePlate->getPurchasePlateComplements($purchasePlateData['id_purchase_plate']);
                        
                    if (empty($data['purchases'][$pkey]['purchasePlates'][$pukey]['plate']['complements'])) continue;                
                    
                    foreach ($data['purchases'][$pkey]['purchasePlates'][$pukey]['plate']['complements'] as $ckey => $complementData) {
                        $data['purchases'][$pkey]['purchasePlates'][$pukey]['plate']['complements'][$ckey]['items'] = $purchasePlate->getPurchasePlateItems($purchasePlateData['id_purchase_plate'], $complementData['id_purchase_plate_complement']);
                    }
                }
            }
        }

        $this->loadView('admin/pages/account/orders/orders', $data);
    }
}
