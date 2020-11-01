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
            $stm->bindValue(':open_1', $this->data['open_1']);
            $stm->bindValue(':close_1', $this->data['close_1']);
            $stm->bindValue(':open_2', $this->data['open_2']);
            $stm->bindValue(':close_2', $this->data['close_2']);

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