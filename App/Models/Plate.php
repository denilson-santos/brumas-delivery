<?php
namespace App\Models;

use App\Models\Model;
use Intervention\Image\ImageManager;

class Plate extends Model {
    private $data;

    public function __construct($data = []) {
        parent::__construct();
        $this->data = $data;
    }

    public function savePlate() {
        try {
            $stm = $this->db->prepare('INSERT INTO plate
                SET category_id = :category_id, 
                    restaurant_id = :restaurant_id, 
                    name = :name, 
                    description = :description, 
                    image = :image, 
                    price = :price, 
                    promo_price = :promo_price, 
                    promo = :promo
            ');

            $stm->bindValue(':category_id', $this->data['category_id']);
            $stm->bindValue(':restaurant_id', $this->data['restaurant_id']);
            $stm->bindValue(':name', $this->data['name']);
            $stm->bindValue(':description', $this->data['description']);
            $stm->bindValue(':price', $this->data['price']);

            if ($this->data['promo']) {
                $stm->bindValue(':promo', $this->data['promo']);
                $stm->bindValue(':promo_price', $this->data['promo_price']);
            } else {
                $stm->bindValue(':promo', '', \PDO::PARAM_INT);
                $stm->bindValue(':promo_price', null, \PDO::PARAM_INT);
            }

            // Save plate image
            if ($this->data['image']['name']) {
                $restaurantPath = '/var/www/projects/brumas-delivery/media/users/'.$this->data['user_id'].'/restaurant';

                $relativeRestaurantPath = '/media/users/'.$this->data['user_id'].'/restaurant';
    
                $name = time().'_'.$this->data['image']['name'];
                $tempPath = $this->data['image']['tmp_name'];
                $newPath = "$restaurantPath/plates/$name";
                $newRelativePath = "$relativeRestaurantPath/plates/$name";
                
                $image = new ImageManager(array('driver' => 'gd'));
                $image = $image->make($tempPath);
    
                // Image width
                $x = $image->width();
                // // Image height
                $y = $image->height();
    
                $resize = 250;
    
                if ($x > $y) {
                    $image->resize(null, $resize, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($newPath);
                } else if ($y > $x) {
                    $image->resize($resize, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($newPath);
                } else {
                    $image->resize($resize, $resize)->save($newPath);
                }  
    
                $stm->bindValue(':image', $newRelativePath);
            } else {
                $stm->bindValue(':image', null);
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

    public function getCategoriesOfRestaurant($id) {
        $data = [];

        $stm = $this->db->prepare(
            'SELECT category_id FROM plate WHERE restaurant_id = :restaurant_id GROUP BY category_id');

        $stm->bindValue(':restaurant_id', $id);
        $stm->execute();

        if ($stm->rowCount() > 0) {
            $data = $stm->fetchAll(\PDO::FETCH_ASSOC);
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

    // Relationships
    public function getComplements($id) {
        try {        
            $stm = $this->db->prepare('SELECT * FROM complement 
                WHERE plate_id = :plate_id
            ');
            
            $stm->bindValue(':plate_id', $id);
            
            $stm->execute();

            $complements = []; 
            
            if ($stm->rowCount() > 0) {
                $complements = $stm->fetchAll(\PDO::FETCH_ASSOC);
            }

            return $complements;              

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