<?php
namespace App\Controllers;

use App\Models\Complement;
use App\Models\Item;
use App\Models\Plate;
use App\Models\Purchase;
use App\Models\PurchasePlate;
use App\Models\PurchasePlateComplement;
use App\Models\PurchasePlateItem;
use App\Models\User;

class PurchaseController extends Controller {  
    public function __construct($router) {
        parent::__construct($router);
        
        $user = new User();
        
        if (!$user->isLogged()) $router->redirect('name.login');
        if (!$user->isAuthorized(['admin', 'partner', 'customer'])) $router->redirect('name.home');
    }

    public function checkout($request) {
        try {
            $this->db->beginTransaction();

            $user = new User();
            $complement = new Complement();
            $item = new Item();

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
            $purchasePlateComplement = new PurchasePlateComplement();
            $purchasePlateItem = new PurchasePlateItem();

            foreach ($request['cart']['items'] as $citem) {
                $purchasePlate->setData([
                    'purchase_id' => $purchaseId, 
                    'plate_id' => $citem['plate_id'], 
                    'quantity' => 1,
                    'total_price' => $citem['plate_total_price'],
                    'comments' => $citem['comments']
                ]);
                
                $purchasePlateId = $purchasePlate->savePurchasePlate();

                if (empty($citem['plate_complements'])) continue;

                foreach ($citem['plate_complements'] as $pcomplement) {
                    $complementData = $complement->getComplement($pcomplement);
                    
                    $purchasePlateComplement->setData([
                        'purchase_plate_id' => $purchasePlateId,
                        'name' => $complementData['name'],
                        'required' => $complementData['required'],
                        'multiple' => $complementData['multiple']
                    ]);
                    
                    $purchasePlateComplementId = $purchasePlateComplement->savePurchasePlateComplement(); 
                    
                    foreach ($citem['plate_items'] as $pitem) {
                        $itemData = $item->getItem($pitem);

                        if ($itemData['complement_id'] == $complementData['id_complement']) {
                            $purchasePlateItem->setData([
                                'purchase_plate_id' => $purchasePlateId,
                                'purchase_plate_complement_id' => $purchasePlateComplementId,
                                'name' => $itemData['name'],
                                'price' => $itemData['price']
                            ]);
                            
                            $purchasePlateItem->savePurchasePlateItem();     
                        }
                        
                    }
                }

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