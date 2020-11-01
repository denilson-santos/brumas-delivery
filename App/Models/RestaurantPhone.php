<?php

namespace App\Models;

class RestaurantPhone extends Model {
    private $data;

    public function __construct($data = []) {
        parent::__construct();
        $this->data = $data;
    }

    public function saveRestaurantPhone() {
        try {
            $stm = $this->db->prepare('INSERT INTO restaurant_phone
                SET restaurant_id = :restaurant_id,
                    phone_type_id = :phone_type_id,
                    number = :number
            ');

            $stm->bindValue(':restaurant_id', $this->data['restaurant_id']);
            $stm->bindValue(':phone_type_id', $this->data['phone_type_id']);
            $stm->bindValue(':number', $this->data['number']);

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