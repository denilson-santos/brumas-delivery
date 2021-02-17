<?php
namespace App\Models;

use App\Models\Model;

class RestaurantSocialMedia extends Model {
    private $data;

    public function __construct($data = []) {
        parent::__construct();
        $this->data = $data;
    }

    public function saveRestaurantSocialMedia() {
        try {
            $stm = $this->db->prepare('INSERT INTO restaurant_social_media
                SET restaurant_id = :restaurant_id,
                    social_media_id = :social_media_id,
                    value = :value
            ');

            $stm->bindValue(':restaurant_id', $this->data['restaurant_id']);
            $stm->bindValue(':social_media_id', $this->data['social_media_id']);
            $stm->bindValue(':value', $this->data['value']);

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

    public function updateRestaurantSocialMedia($restaurantId, $idRestaurantSocialMedia) {
        try {    
            $setColumns = '';
    
            $socialMediaColumns = array_keys($this->data);

            // Generate named params
            foreach ($socialMediaColumns as $key => $column) {
                if ($key == count($socialMediaColumns) -1) {
                    $setColumns .= $column . ' = :' . $column;
                } else {
                    $setColumns .= $column . ' = :' . $column . ', ';
                }
            }
        
            $stm = $this->db->prepare("UPDATE restaurant_social_media
                SET $setColumns 
                WHERE restaurant_id = :restaurantId
                AND id_restaurant_social_media = :idRestaurantSocialMedia
            ");
            
            // Replacing named params
            foreach ($socialMediaColumns as $key => $column) {
                $stm->bindValue(':' . $column, $this->data[$column]); 
            }

            $stm->bindValue(':restaurantId', $restaurantId);
            $stm->bindValue(':idRestaurantSocialMedia', $idRestaurantSocialMedia);

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

    public function deleteRestaurantSocialMedia($restaurantId, $idRestaurantSocialMedia) {
        try {    
            $stm = $this->db->prepare("DELETE FROM restaurant_social_media
                WHERE restaurant_id = :restaurantId
                AND id_restaurant_social_media = :idRestaurantSocialMedia
            ");

            $stm->bindValue(':restaurantId', $restaurantId);
            $stm->bindValue(':idRestaurantSocialMedia', $idRestaurantSocialMedia);

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