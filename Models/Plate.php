<?php
namespace Models;

use Core\Model;

class Plate extends Model {
    
    public function getListPlates() {
        $data = [];

        $stm = $this->db->query(
            'SELECT plate.*, restaurant.name as restaurant_name FROM plate
            JOIN restaurant ON restaurant_id = id_restaurant');
        // print_r($stm); exit;
        
        if ($stm->rowCount() > 0) {
            $data = $stm->fetchAll(\PDO::FETCH_ASSOC);
        }

        return $data;
    }

    public function getPlate($id) {
        $data = [];

        if (!empty($id)) {
            $stm = $this->db->prepare(
            'SELECT plate.*, restaurant.name as restaurant_name FROM plate
            JOIN restaurant ON restaurant_id = id_restaurant WHERE id_plate = :id_plate');
            
            $stm->bindValue(':id_plate', $id);
            $stm->execute();

            if ($stm->rowCount() > 0) {
                $data = $stm->fetch(\PDO::FETCH_ASSOC);
            }
        }

        return $data;
        
    }

    public function getTotalPlatesByRestaurants() {
        $data = [];

        $stm = $this->db->query(
            'SELECT restaurant_id, COUNT(*) AS total_plates_by_restaurant FROM plate GROUP BY restaurant_id');
        // print_r($stm); exit;

        if ($stm->rowCount() > 0) {
            $data = $stm->fetchAll(\PDO::FETCH_ASSOC);
        }  

        return $data;      
    } 

    public function getPromotionCount() {
        $stm = $this->db->query('SELECT COUNT(*) AS total_promotion FROM plate');
        // print_r($stm); exit;
        $data = $stm->fetch(\PDO::FETCH_ASSOC);

        return $data['total_promotion'];  
    }

    public function getTotalPlates() {
        $stm = $this->db->query('SELECT COUNT(*) AS total_plate FROM plate');

        $data = $stm->fetch(\PDO::FETCH_ASSOC);

        return $data['total_plate'];
 
    }

    public function getMaxPrice() {
        $data = 0;

        $stm = $this->db->query(
            'SELECT MAX(promo_price) AS max_promo_price, 
            (SELECT MAX(price) FROM plate WHERE promo = 0 ) 
            AS max_price FROM plate');

        $data = $stm->fetch(\PDO::FETCH_ASSOC);
        
        return max($data);
    }
}