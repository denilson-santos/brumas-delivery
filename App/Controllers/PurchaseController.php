<?php
namespace App\Controllers;

use App\Models\Purchase;
use App\Models\PurchasePlate;
use App\Models\User;

class PurchaseController extends Controller {  
    public function __construct($router) {
        parent::__construct($router);
        
        $user = new User();
        
        if (!$user->isLogged()) $router->redirect('name.login');
        if (!$user->isAuthorized(['admin', 'partner'])) $router->redirect('name.home');
    }

    public function checkout($request) {
        try {
            $this->db->beginTransaction();

            $user = new User();

            $purchase = new Purchase([
                'user_id' => $user->isLogged()['id_user'],
                'restaurant_id' => $request['restaurant_id'],
                'coupon_id' => NULL,
                'total_amount' => $request['cart']['total'],
                'payment_id' => $request['payment_method'],
                'status' => 0
            ]);

            $purchaseId = $purchase->savePurchase();

            $purchasePlate = new PurchasePlate();

            foreach ($request['cart']['items'] as $item) {
                $purchasePlate->setData([
                    'purchase_id' => $purchaseId, 
                    'plate_id' => $item['plate_id'], 
                    'quantity' => 1
                ]);
                
                $purchasePlate->savePurchasePlate();
            }

            $this->db->commit();

            echo json_encode(['message' => 'success']);
        } catch (\PDOException $error) {
            $this->db->rollback();
            
            echo json_encode(['message' => 'error']);

            // For debug
            // echo "Message: " . $error->getMessage() . "<br>";
            // echo "Name of file: ". $error->getFile() . "<br>";
            // echo "Row: ". $error->getLine() . "<br>";
        }
    }
}