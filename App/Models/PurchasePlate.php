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
                    quantity = :quantity,
                    total_price = :total_price,
                    comments = :comments
            ');

            $stm->bindValue(':purchase_id', $this->data['purchase_id']);
            $stm->bindValue(':plate_id', $this->data['plate_id']);
            $stm->bindValue(':quantity', $this->data['quantity']);
            $stm->bindValue(':total_price', $this->data['total_price']);
            $stm->bindValue(':comments', $this->data['comments']);

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

    // Relationships
    public function getPurchasePlateComplements($id) {
        try {        
            $stm = $this->db->prepare('SELECT * FROM purchase_plate_complement 
                WHERE purchase_plate_id = :purchase_plate_id
            ');
            
            $stm->bindValue(':purchase_plate_id', $id);
            
            $stm->execute();

            if ($stm->rowCount() > 0) {
                $purchasePlateComplements = $stm->fetchAll(\PDO::FETCH_ASSOC);

                return $purchasePlateComplements;              
            }

        } catch (\PDOException $error) {
            return false; 
            // For debug
            // echo "Message: " . $error->getMessage() . "<br>";
            // echo "Name of file: ". $error->getFile() . "<br>";
            // echo "Row: ". $error->getLine() . "<br>";
        }
    }

    public function getPurchasePlateItems($id, $purchasePlateComplementId) {
        try {        
            $stm = $this->db->prepare('SELECT * FROM purchase_plate_item 
                WHERE purchase_plate_id = :purchase_plate_id
                AND purchase_plate_complement_id = :purchase_plate_complement_id
            ');
            
            $stm->bindValue(':purchase_plate_id', $id);
            $stm->bindValue(':purchase_plate_complement_id', $purchasePlateComplementId);
            
            $stm->execute();

            if ($stm->rowCount() > 0) {
                $purchasePlateItems = $stm->fetchAll(\PDO::FETCH_ASSOC);

                return $purchasePlateItems;              
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