<?php
namespace App\Models;

use App\Models\Model;

class RestaurantOperation extends Model {
    private $data;

    public function __construct($data = []) {
        parent::__construct();
        $this->data = $data;
    }

    public function saveRestaurantOperation() {
        try {
            $stm = $this->db->prepare('INSERT INTO restaurant_operation
                SET restaurant_id = :restaurant_id,
                    week_day_id = :week_day_id,
                    open_1 = :open_1,
                    close_1 = :close_1,
                    open_2 = :open_2,
                    close_2 = :close_2
            ');

            $stm->bindValue(':restaurant_id', $this->data['restaurant_id']);
            $stm->bindValue(':week_day_id', $this->data['week_day_id']);

            if ($this->data['open_1']) {
                $stm->bindValue(':open_1', $this->data['open_1']);
            } else {
                $stm->bindValue(':open_1', NULL, \PDO::PARAM_INT);
            }

            if ($this->data['close_1']) {
                $stm->bindValue(':close_1', $this->data['close_1']);
            } else {
                $stm->bindValue(':close_1', NULL, \PDO::PARAM_INT);
            }

            if ($this->data['open_2']) {
                $stm->bindValue(':open_2', $this->data['open_2']);
            } else {
                $stm->bindValue(':open_2', NULL, \PDO::PARAM_INT);
            }

            if ($this->data['close_2']) {
                $stm->bindValue(':close_2', $this->data['close_2']);
            } else {
                $stm->bindValue(':close_2', NULL, \PDO::PARAM_INT);
            }

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

    public function updateRestaurantOperation($restaurantId, $idRestaurantOperation) {
        try {    
            $setColumns = '';
    
            $operationColumns = array_keys($this->data);

            // Generate named params
            foreach ($operationColumns as $key => $column) {
                if ($key == count($operationColumns) -1) {
                    $setColumns .= $column . ' = :' . $column;
                } else {
                    $setColumns .= $column . ' = :' . $column . ', ';
                }
            }
        
            $stm = $this->db->prepare("UPDATE restaurant_operation
                SET $setColumns 
                WHERE restaurant_id = :restaurantId
                AND id_restaurant_operation = :idRestaurantOperation
            ");
            
            // Replacing named params
            foreach ($operationColumns as $key => $column) {
                if (!$this->data[$column]) {
                    $stm->bindValue(':' . $column, NULL, \PDO::PARAM_INT ); 
                } else {
                    $stm->bindValue(':' . $column, $this->data[$column]); 
                }
            }

            $stm->bindValue(':restaurantId', $restaurantId);
            $stm->bindValue(':idRestaurantOperation', $idRestaurantOperation);

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

    public function deleteRestaurantOperation($restaurantId, $idRestaurantOperation) {
        try {    
            $stm = $this->db->prepare("DELETE FROM restaurant_operation
                WHERE restaurant_id = :restaurantId
                AND id_restaurant_operation = :idRestaurantOperation
            ");

            $stm->bindValue(':restaurantId', $restaurantId);
            $stm->bindValue(':idRestaurantOperation', $idRestaurantOperation);

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