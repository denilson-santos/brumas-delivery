<?php
namespace App\Controllers\admin;

use App\Controllers\Controller;
use App\Models\Category;
use App\Models\Neighborhood;
use App\Models\Plate;
use App\Models\Purchase;
use App\Models\PurchasePlate;
use App\Models\Restaurant;
use App\Models\SocialMedia;
use App\Models\User;
use App\Models\WeekDay;

class RestaurantController extends Controller {  
    public function __construct($router) {
        parent::__construct($router);
        
        $user = new User();
        
        if (!$user->isLogged()) $router->redirect('name.login');
        if (!$user->isAuthorized(['admin', 'partner'])) $router->redirect('name.home');
    }

    public function index($request) {
        $user = new User();
        $restaurant = new Restaurant();
                
        $data = [];

        $data = [
            'purchases' => $restaurant->getRestaurantPurchases($user->isLogged()['restaurant']['id_restaurant'], 6),
            'countRestaurantPurchases' =>  $restaurant->countRestaurantPurchases($user->isLogged()['restaurant']['id_restaurant']),
            'countRestaurantPlates' =>  $restaurant->countRestaurantPlates($user->isLogged()['restaurant']['id_restaurant']),
            'countRestaurantRatings' =>  $restaurant->countRestaurantRatings($user->isLogged()['restaurant']['id_restaurant']),
            'language' => $this->language->getLanguage(),
            'iniDicionary' => $this->language->getIniDicionary(),
            'userLogged' => $user->isLogged()
        ];

        if ($data['purchases']) {
            foreach ($data['purchases'] as $key => $purchase) {
                $userData = $user->getUser($purchase['user_id']);
    
                $data['purchases'][$key]['full_name'] = $userData['first_name'] . ' ' . $userData['last_name'];
                $data['purchases'][$key]['user_image'] = $userData['image'];
            }
        }

        $this->loadView('admin/pages/restaurant/details/details', $data);
    }

    public function restaurantEdit($request) {
        $user = new User();
        $neighborhood = new Neighborhood();
        $category = new Category();
        $weekDay = new WeekDay();
        $socialMedia = new SocialMedia();
                
        $data = [];

        $data = [
            // 'restaurants' => $restaurant->getListRestaurants($offset, $limit, $filtersSelected),
            // 'restaurantsOpen' => $restaurant->getTotalRestaurantsOpen($filtersSelected, 'list'),
            // 'restaurantsClosed' => $restaurant->getTotalRestaurantsClosed($filtersSelected, 'list'),
            // 'restaurantsInPromotion' => $restaurant->getListRestaurants($offset, $limit, ['promotion' => 1]),
            // 'totalItens' => $restaurant->getTotalRestaurants($filtersSelected),
            // 'numberPages' => ceil($restaurant->getTotalRestaurants($filtersSelected) / $limit),
            // 'currentPage' => $currentPage,
            'weekDays' => $weekDay->getListWeekDays(),
            'categories' => $category->getListCategories(),
            'socialMedias' => $socialMedia->getListSocialMedias(),
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

        $this->loadView('admin/pages/restaurant/edit/edit', $data);
    }

    public function restaurantEditAction($request) {  
        $headers = getallheaders();

        if (array_key_exists('restaurantBrand', $_FILES)) {
            $request['restaurantBrand'] = $_FILES['restaurantBrand'];
        }
        
        $request = $this->sanitizeInputs($request);

        $maskedFields = [
            'restaurantCnpj', 
            'restaurantPhone', 
            'restaurantCellPhone'
        ];

        $request = $this->clearMasks($request, $maskedFields);
        
        // Operation data
        if (array_key_exists('weekDay', $request)) {
            $request['operation'] = [
                'idOperation' => $request['idOperation'] ?? '',
                'row' => $request['row'] ?? '',
                'dayIndex' => $request['dayIndex'] ?? '',
                'weekDay' => $request['weekDay'] ?? '',
                'open1' => $request['open1'] ?? '',
                'close1' => $request['close1'] ?? '',
                'open2' => $request['open2'] ?? '',
                'close2' => $request['close2'] ?? '',
            ];

            unset($request['idOperation']);
            unset($request['row']);
            unset($request['dayIndex']);
            unset($request['weekDay']);
            unset($request['open1']);
            unset($request['close1']);
            unset($request['open2']);
            unset($request['close2']);
        }

        if (array_key_exists('idOperationDeleted', $request)) {
            $request['operationDeleteds'] = [
                'idOperation' => $request['idOperationDeleted'] ?? '',
                'row' => $request['rowDeleted'] ?? '',
            ];

            unset($request['idOperationDeleted']);
            unset($request['rowDeleted']);
        }

        // Social medias data
        if (array_key_exists('socialMedia', $request)) {
            $request['socialMedias'] = [
                'idSocialMedia' => $request['idSocialMedia'] ?? '',
                'socialMediaRow' => $request['socialMediaRow'] ?? '',
                'socialMediaIndex' => $request['socialMediaIndex'] ?? '',
                'socialMedia' => $request['socialMedia'] ?? '',
                'linkOrPhone' => $request['linkOrPhone'] ?? ''
            ];

            unset($request['idSocialMedia']);
            unset($request['socialMediaRow']);
            unset($request['socialMediaIndex']);
            unset($request['socialMedia']);
            unset($request['linkOrPhone']);
        }

        if (array_key_exists('idSocialMediaDeleted', $request)) {
            $request['socialMediasDeleteds'] = [
                'idSocialMedia' => $request['idSocialMediaDeleted'] ?? '',
                'row' => $request['socialMediaRowDeleted'] ?? '',
            ];

            unset($request['idSocialMediaDeleted']);
            unset($request['socialMediaRowDeleted']);
        }

        // PaymentMethods data
        if (array_key_exists('idPayment', $request)) {
            $request['payments'] = [
                'idPayment' => $request['idPayment'] ?? '',
                'paymentSaved' => $request['paymentSaved'] ?? ''
            ];

            unset($request['idPayment']);
        }

        if (array_key_exists('idPaymentDeleted', $request)) {
            $request['paymentsDeleteds'] = [
                'idPayment' => $request['idPaymentDeleted'] ?? ''
            ];

            unset($request['idPaymentDeleted']);
        }

        // print_r($request); exit;

        $restaurant = new Restaurant($request);
        
        $validation = $restaurant->validateRestaurantEditForm();

        if ($validation['validate']) {
            $restaurant->saveRestaurantEditForm($headers['Restaurant-Id'], $headers['User-Id'], $headers['Address-Id']);

            echo json_encode($validation);
        } else {
            echo json_encode($validation);
        }
    }

    public function saveRestaurantMenu($request) {        
        if (!empty($_FILES['plateImage']))  {
            $request['plateImage'] = $_FILES['plateImage'];
        };

        $request = $this->sanitizeInputs($request);
        
        if (!empty($request['complementRow'])) {
            $request['complements'] = [
                'complementRow' => $request['complementRow'],
                'complementName' => $request['complementName'],
                'complementRequired' => $request['complementRequired'],
                'complementMultiple' => $request['complementMultiple']
            ];
            
            unset($request['complementRow']);
            unset($request['complementName']);
            unset($request['complementRequired']);
            unset($request['complementMultiple']);
        }

        if (!empty($request['itemRow'])) {
            $request['itens'] = [
                'itemRow' => $request['itemRow'],
                'itemComplementRow' => $request['itemComplementRow'],
                'itemName' => $request['itemName'],
                'itemPrice' => $request['itemPrice'],
            ];
            
            unset($request['itemRow']);
            unset($request['itemComplementRow']);
            unset($request['itemName']);
            unset($request['itemPrice']);
        }

        $restaurant = new Restaurant($request);
    
        $validation = $restaurant->validateRestaurantNewPlateForm();

        if ($validation['validate']) {
            $restaurant->saveRestaurantPlateForm();

            echo json_encode($validation);
        } else {
            echo json_encode($validation);
        }
    }

    public function getRestaurantMenu($request) {
        $user = new User();
        $category = new Category();
        $plate = new Plate();
                
        $data = [];

        $data = [
            // 'restaurants' => $restaurant->getListRestaurants($offset, $limit, $filtersSelected),
            // 'restaurantsOpen' => $restaurant->getTotalRestaurantsOpen($filtersSelected, 'list'),
            // 'restaurantsClosed' => $restaurant->getTotalRestaurantsClosed($filtersSelected, 'list'),
            // 'restaurantsInPromotion' => $restaurant->getListRestaurants($offset, $limit, ['promotion' => 1]),
            // 'totalItens' => $restaurant->getTotalRestaurants($filtersSelected),
            // 'numberPages' => ceil($restaurant->getTotalRestaurants($filtersSelected) / $limit),
            // 'currentPage' => $currentPage,
            // 'filtersSelected' => $filtersSelected,
            // 'filters' => $filter->getFilters($filtersSelected),
            // 'sidebarWidgetsFeatureds' => $restaurant->getListRestaurants(0, 5, ['featured' => 1], true),
            'categories' => $category->getListCategories(),
            'categoriesOfRestaurant' => $plate->getCategoriesOfRestaurant($user->isLogged()['restaurant']['id_restaurant']),
            // 'footerWidgetsOnSale' => $restaurant->getListRestaurants(0, 3, ['promotion' => 1], true),
            // 'footerWidgetsTopRateds' => $restaurant->getListRestaurants(0, 3, ['top_rated' => 1], true),
            // 'footerWidgetsNew' => $restaurant->getListRestaurants(0, 3, ['new' => 1], true),
            'language' => $this->language->getLanguage(),
            'iniDicionary' => $this->language->getIniDicionary(),
            'userLogged' => $user->isLogged()
        ];

        $this->loadView('admin/pages/restaurant/menu/menu', $data);
    }

    public function getRestaurantPlates($request) {
        $user = new User();
        $restaurant = new Restaurant();
                
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

        $this->loadView('admin/pages/restaurant/plates/plates', $data);
    }

    public function getRestaurantOrders($request) {
        $user = new User();
        $restaurant = new Restaurant();
        $plate = new Plate();
        $purchase = new Purchase();
        $purchasePlate = new PurchasePlate();
                
        $data = [
            'purchases' => $restaurant->getRestaurantPurchases($user->isLogged()['restaurant']['id_restaurant']),
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

        $this->loadView('admin/pages/restaurant/orders/orders', $data);
    }

    public function getRestaurantRates($request) {
        $user = new User();
                
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

        $this->loadView('admin/pages/restaurant/rates/rates', $data);
    }

    public function changeStatusOrder($request) {
        $purchase = new Purchase();
        
        $purchase->changeStatus($request['purchase_id'], $request['status']);
    }
    
    public function deleteOrder($request) {
        $purchase = new Purchase();
        
        $purchase->deleteRestaurantPurchase($request['purchase_id']);
    }
} 
