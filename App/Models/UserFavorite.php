<?php
namespace App\Models;

use App\Models\Model;

class UserFavorite extends Model {
    private $data;

    public function __construct($data = []) {
        parent::__construct();
        $this->data = $data;
    }

    public function saveUserFavorite() {
        try {
            $stm = $this->db->prepare('INSERT INTO user_favorite
                SET user_id = :user_id,
                    restaurant_id = :restaurant_id
            ');

            $stm->bindValue(':user_id', $this->data['user_id']);
            $stm->bindValue(':restaurant_id', $this->data['restaurant_id']);

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

    public function deleteUserFavorite() {
        try {    
            $stm = $this->db->prepare("DELETE FROM user_favorite
                WHERE user_id = :user_id
                AND restaurant_id = :restaurant_id
            ");

            $stm->bindValue(':user_id', $this->data['user_id']);
            $stm->bindValue(':restaurant_id', $this->data['restaurant_id']);

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

    public function getUserFavorite() {
        $data = [];

        $stm = $this->db->prepare('SELECT * FROM user_favorite 
            WHERE user_id = :user_id
            AND   restaurant_id = :restaurant_id
        ');
        
        $stm->bindValue(':user_id', $this->data['user_id']);
        $stm->bindValue(':restaurant_id', $this->data['restaurant_id']);
        $stm->execute();

        if ($stm->rowCount() > 0) {
            $data = $stm->fetch(\PDO::FETCH_ASSOC);
        }

        return $data;
    }

    public function setData($data) {
        $this->data = $data;
    }
}