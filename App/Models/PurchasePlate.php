<?php
namespace App\Models;

class PurchasePlate extends Model {
    private $data;

    public function __construct($data = []) {
        parent::__construct();
        $this->data = $data;
    }

    public function savePurchasePlate() {
        try {
            $stm = $this->db->prepare('INSERT INTO purchase_plate
                SET purchase_id = :purchase_id, 
                    plate_id = :plate_id, 
                    quantity = :quantity
            ');

            $stm->bindValue(':purchase_id', $this->data['purchase_id']);
            $stm->bindValue(':plate_id', $this->data['plate_id']);
            $stm->bindValue(':quantity', $this->data['quantity']);

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

    public function setData($data) {
        $this->data = $data;
    }
}