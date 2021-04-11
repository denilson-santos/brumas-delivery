<?php
namespace App\Models;

class Purchase extends Model {
    private $data;

    public function __construct($data = []) {
        parent::__construct();
        $this->data = $data;
    }

    public function savePurchase() {
        try {
            $stm = $this->db->prepare('INSERT INTO purchase
                SET user_id = :user_id, 
                    restaurant_id = :restaurant_id, 
                    coupon_id = :coupon_id, 
                    total_amount = :total_amount, 
                    payment_id = :payment_id, 
                    status = :status
            ');

            $stm->bindValue(':user_id', $this->data['user_id']);
            $stm->bindValue(':restaurant_id', $this->data['restaurant_id']);
            $stm->bindValue(':coupon_id', $this->data['coupon_id']);
            $stm->bindValue(':total_amount', $this->data['total_amount']);
            $stm->bindValue(':payment_id', $this->data['payment_id']);
            $stm->bindValue(':status', $this->data['status']);

            $stm->execute();
            
            return $this->db->lastInsertId();
        } catch (\PDOException $error) {
            // For debug
            // echo "Message: " . $error->getMessage() . "<br>";
            // echo "Name of file: ". $error->getFile() . "<br>";
            // echo "Row: ". $error->getLine() . "<br>";

            throw new \PDOException("Error in statement", 0);
        }
    }
    
    public function getListPurchases() {
        $data = [];

        $stm = $this->db->query(
            'SELECT * FROM purchase
            WHERE restaurant_id = id_restaurant');
        // print_r($stm); exit;
        
        if ($stm->rowCount() > 0) {
            $data = $stm->fetchAll(\PDO::FETCH_ASSOC);
        }

        return $data;
    }

    // Relationships
    public function getPurchasePlates($id) {
        try {        
            $stm = $this->db->prepare('SELECT * FROM purchase_plate 
                WHERE purchase_id = :purchase_id
            ');
            
            $stm->bindValue(':purchase_id', $id);
            
            $stm->execute();

            if ($stm->rowCount() > 0) {
                $purchasePlates = $stm->fetchAll(\PDO::FETCH_ASSOC);

                return $purchasePlates;              
            }

        } catch (\PDOException $error) {
            return false; 
            // For debug
            // echo "Message: " . $error->getMessage() . "<br>";
            // echo "Name of file: ". $error->getFile() . "<br>";
            // echo "Row: ". $error->getLine() . "<br>";
        }
    }

    public function setData($data) {
        $this->data = $data;
    }
}