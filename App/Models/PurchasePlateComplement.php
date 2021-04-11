<?php
namespace App\Models;

class PurchasePlateComplement extends Model {
    private $data;

    public function __construct($data = []) {
        parent::__construct();
        $this->data = $data;
    }

    public function savePurchasePlateComplement() {
        try {
            $stm = $this->db->prepare('INSERT INTO purchase_plate_complement
                SET purchase_plate_id = :purchase_plate_id, 
                    name = :name, 
                    required = :required,
                    multiple = :multiple
            ');

            $stm->bindValue(':purchase_plate_id', $this->data['purchase_plate_id']);
            $stm->bindValue(':name', $this->data['name']);
            $stm->bindValue(':required', $this->data['required']);
            $stm->bindValue(':multiple', $this->data['multiple']);

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