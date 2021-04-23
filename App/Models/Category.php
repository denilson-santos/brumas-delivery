<?php
namespace App\Models;

use App\Models\Model;

class Category extends Model {
    public function getListCategories($orderBy = '') {
        $data = [];
        
        if (!empty($orderBy)) {
            $orderBy = 'ORDER BY' .$orderBy;
        }

        $stm = $this->db->query('SELECT * FROM category '.$orderBy);
        
        if ($stm->rowCount() > 0) {
            $data = $stm->fetchAll(\PDO::FETCH_ASSOC);
        }

        return $data;
    }

    public function getCategory($id) {
        $data = [];

        $stm = $this->db->prepare('SELECT * FROM category WHERE id_category = :id_categorie');

        $stm->bindValue(':id_categorie', $id);
        $stm->execute();

        if ($stm->rowCount() > 0) {
            $data = $stm->fetch(\PDO::FETCH_ASSOC);
        }
        
        return $data;
    }

    // Relationships
    public function getPlates($id, $restaurantId) {
        try {        
            $stm = $this->db->prepare('SELECT * FROM plate 
                WHERE category_id = :category_id
                AND   restaurant_id = :restaurant_id
            ');
            
            $stm->bindValue(':category_id', $id);
            $stm->bindValue(':restaurant_id', $restaurantId);
            
            $stm->execute();

            $plates = []; 
            
            if ($stm->rowCount() > 0) {
                $plates = $stm->fetchAll(\PDO::FETCH_ASSOC);
            }

            return $plates;              

        } catch (\PDOException $error) {
            return false; 
            // For debug
            // echo "Message: " . $error->getMessage() . "<br>";
            // echo "Name of file: ". $error->getFile() . "<br>";
            // echo "Row: ". $error->getLine() . "<br>";
        }
    }
}