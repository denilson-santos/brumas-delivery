<?php
namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\Category;
use App\Models\Complement;
use App\Models\Item;
use App\Models\Neighborhood;
use App\Models\Payment;
use App\Models\Plate;
use App\Models\Restaurant;
use App\Models\RestaurantPayment;
use App\Models\SocialMedia;
use App\Models\User;
use App\Models\WeekDay;

class CartController extends Controller {  
    public function __construct($router) {
        parent::__construct($router);
        
        $user = new User();
        
        if (!$user->isLogged()) $router->redirect('name.login');
        if (!$user->isAuthorized(['admin', 'partner', 'customer'])) $router->redirect('name.home');
    }

    public function index($request) {
        $user = new User();
                
        $data = [];

        $data = [
            'language' => $this->language->getLanguage(),
            'iniDicionary' => $this->language->getIniDicionary(),
            'userLogged' => $user->isLogged()
        ];

        $this->loadView('pages/cart/cart', $data);
    }

    public function getCart($request) {
        $plate = new Plate();
        $complement = new Complement();
        $item = new Item();
                
        $data = [];

        foreach ($request['cart_items'] as $key => $cartItem) {
            $data['plates'][$key] = $plate->getPlate($cartItem['plate_id']);
            
            $request['cart_items'][$key]['plate_total_price'] = 0;
            
            if (!empty($request['cart_items'][$key]['plate_complements'])) {
                foreach($request['cart_items'][$key]['plate_complements'] as $ckey => $plateComplement) {
                    $data['plates'][$key]['complements'][$ckey] = $complement->getComplement($plateComplement);
                }
            }

            if (!empty($request['cart_items'][$key]['plate_items'])) {
                foreach ($request['cart_items'][$key]['plate_items'] as $ikey => $plateItem) {
                    $data['plates'][$key]['items'][$ikey] = $item->getItem($plateItem);

                    $request['cart_items'][$key]['plate_total_price'] += $data['plates'][$key]['items'][$ikey]['price'];
                }

            } else if (!empty($request['cart_items'][$key]['promo'])) {
                $request['cart_items'][$key]['plate_total_price'] = $data['plates'][$key]['promo_price'];
            } else {
                $request['cart_items'][$key]['plate_total_price'] = $data['plates'][$key]['price'];
            }
        }

        $data['cart_items'] = $request['cart_items'];

        echo json_encode($data, JSON_NUMERIC_CHECK);
    }

    public function choosePayment($request) {
        $restaurant = new Restaurant();
        $payment = new Payment();

        $data = [];

        $data = [
            'payments' => $restaurant->getRestaurantPayments($request['restaurant_id']),
            'paymentNames' => $payment->getListPayments()
        ];

        echo json_encode($data, JSON_NUMERIC_CHECK);
    }
} 