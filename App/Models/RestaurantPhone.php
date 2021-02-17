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

    public function updateRestaurantPhones($restaurantId) {
        try {
            $databaseColumns = [
                'number'
            ];
    
            $columnsChanged = array_keys($this->data);
    
            $setColumns = '';
    
            // Generate named params
            foreach ($databaseColumns as $key => $column) {
                if ($key == count($databaseColumns) -1) {
                    $setColumns .= $column . ' = :' . $column;
                } else {
                    $setColumns .= $column . ' = :' . $column . ', ';
                }
            }

            // Replacing named params
            foreach ($columnsChanged as $key => $column) {   
                $stm = $this->db->prepare("UPDATE restaurant_phone
                    SET $setColumns 
                    WHERE restaurant_id = :restaurantId
                    AND phone_type_id = :phoneTypeId
                ");

                foreach ($databaseColumns as $databaseColumn) {
                    $stm->bindValue(':' . $databaseColumn, $this->data[$column]); 
                }
                
                if ($column == 'restaurantPhone') $stm->bindValue(':phoneTypeId', 1); 
                if ($column == 'restaurantCellPhone') $stm->bindValue(':phoneTypeId', 2);

                $stm->bindValue(':restaurantId', $restaurantId);

                $stm->execute();
            }
            
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