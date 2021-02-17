<?php
namespace App\Models;

use App\Models\Model;

class RestaurantPayment extends Model {
    private $data;

    public function __construct($data = []) {
        parent::__construct();
        $this->data = $data;
    }

    public function saveRestaurantPayment() {
        try {
            $stm = $this->db->prepare('INSERT INTO restaurant_payment
                SET restaurant_id = :restaurant_id,
                    payment_id = :payment_id
            ');

            $stm->bindValue(':restaurant_id', $this->data['restaurant_id']);
            $stm->bindValue(':payment_id', $this->data['payment_id']);

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

    public function updateRestaurantPayment($restaurantId, $idRestaurantPayment) {
        try {    
            $setColumns = '';
    
            $paymentColumns = array_keys($this->data);

            // Generate named params
            foreach ($paymentColumns as $key => $column) {
                if ($key == count($paymentColumns) -1) {
                    $setColumns .= $column . ' = :' . $column;
                } else {
                    $setColumns .= $column . ' = :' . $column . ', ';
                }
            }
        
            $stm = $this->db->prepare("UPDATE restaurant_payment
                SET $setColumns 
                WHERE restaurant_id = :restaurantId
                AND payment_id = :paymentId
            ");
            
            // Replacing named params
            foreach ($paymentColumns as $key => $column) {
                $stm->bindValue(':' . $column, $this->data[$column]); 
            }

            $stm->bindValue(':restaurantId', $restaurantId);
            $stm->bindValue(':paymentId', $idRestaurantPayment);

            $stm->execute();
            
            return true;
        } catch (\PDOException $error) {

            // For debug
            // echo "Message: " . $error->getMessage() . "<br>";
            // echo "Name of file: ". $error->getFile() . "<br>";
            // echo "Row: ". $error->getLine() . "<br>";

            throw new \PDOException("Error in statement", 0);
        }
    }

    public function deleteRestaurantPayment($restaurantId, $idRestaurantPayment) {
        try {    
            $stm = $this->db->prepare("DELETE FROM restaurant_payment
                WHERE restaurant_id = :restaurantId
                AND payment_id = :paymentId
            ");

            $stm->bindValue(':restaurantId', $restaurantId);
            $stm->bindValue(':paymentId', $idRestaurantPayment);

            $stm->execute();
            
            return true;
        } catch (\PDOException $error) {

            // For debug
            // echo "Message: " . $error->getMessage() . "<br>";
            // echo "Name of file: ". $error->getFile() . "<br>";
            // echo "Row: ". $error->getLine() . "<br>";

            throw new \PDOException("Error in statement", 0);
        }
    }
    
    public function setData($data) {
        $this->data = $data;
    }
}