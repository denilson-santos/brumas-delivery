<?php
namespace App\Models;

use App\Models\Model;

class Restaurant extends Model {
    private $data;

    public function __construct($data = []) {
        parent::__construct();
        $this->data = $data;
    }

    public function saveRestaurant() {
        try {
            $stm = $this->db->prepare('INSERT INTO restaurant
                SET user_id = :user_id,
                    address_id = :address_id,
                    name = :name,
                    cnpj = :cnpj,
                    email = :email,
                    main_categories = :main_categories,
                    image = :image
            ');

            $stm->bindValue(':user_id', $this->data['user_id']);
            $stm->bindValue(':address_id', $this->data['address_id']);
            $stm->bindValue(':name', $this->data['name']);
            $stm->bindValue(':cnpj', $this->data['cnpj']);
            $stm->bindValue(':email', $this->data['email']);
            $stm->bindValue(':main_categories', $this->data['main_categories']);

            // Save restaurant brand
            $restaurantPath = '/var/www/projects/brumas-delivery/media/users/'.$this->data['user_id'].'/restaurant';

            $relativeRestaurantPath = '/media/users/'.$this->data['user_id'].'/restaurant';

            $name = $this->data['brand']['name'];
            $type = $this->data['brand']['type'];
            $tempPath = $this->data['brand']['tmp_name'];
            $newPath = "$restaurantPath/brand/$name";

            $this->saveImage($tempPath, $newPath, $type);
            $this->resizeImage($newPath, 250);

            $stm->bindValue(':image', $relativeRestaurantPath);

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
    
    public function getListRestaurants($offset = 0, $limit = 9, $filters = [], $random = false, $staticFilters = ['analized' => 1, 'approved' => 1,'random' => 0]) {
        $data = [];
        $orderByRandom = ''; 

        if ($random) {
            $orderByRandom = 'ORDER BY RAND()';
        }

        if (!empty($filters['top_rated']) && !$random) {
            $orderByRandom = 'ORDER BY rating DESC';
        }

        $where = $this->buildWhere($filters, 'none');     

        $stm = $this->db->prepare(
            'SELECT * FROM restaurant WHERE '.implode(" AND ", $where).' '.$orderByRandom.' LIMIT :offset, :limit');
        // print_r($stm); exit;
        
        $this->bindWhere($filters, $stm, 'none');
        $stm->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stm->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stm->execute();

        if ($stm->rowCount() > 0) {
            $data = $stm->fetchAll(\PDO::FETCH_ASSOC);
        }

        return $data;
    }

    public function getRestaurant($id) {
        $data = [];

        if (!empty($id)) {
            $stm = $this->db->prepare('
            SELECT * FROM restaurant WHERE id_restaurant = :id_restaurant');
            $stm->bindValue(':id_restaurant', $id);
            $stm->execute();

            if ($stm->rowCount() > 0) {
                $data = $stm->fetch(\PDO::FETCH_ASSOC);
                return $data;
            }
        }

        return $data;
        
    }

    // public function getTotalRestaurantsByCategories($filters = []) {
    //     $data = [];
    //     $where = $this->buildWhere($filters, 'category');

    //     $stm = $this->db->prepare(
    //         'SELECT category_id, COUNT(*) AS total_restaurants_by_categories FROM restaurant WHERE '.implode(" AND ", $where).' GROUP BY restaurant_id');
    //     // print_r($stm); exit;

    //     $this->bindWhere($filters, $stm, 'category');
    //     $stm->execute();
            
    //     if ($stm->rowCount() > 0) {
    //         $data = $stm->fetchAll(\PDO::FETCH_ASSOC);
    //     }  

    //     return $data;      
    // } 

    public function getTotalRestaurantsByNeighborhoods($filters = []) {
        $data = [];

        $where = $this->buildWhere($filters, 'neighborhood');

        $stm = $this->db->prepare(
            'SELECT neighborhood_id, COUNT(*) AS total_restaurants_by_neighborhoods FROM address WHERE id_address 
        in(SELECT address_id from restaurant WHERE '.implode(" AND ", $where).') GROUP BY neighborhood_id');
        // print_r($stm); exit;

        $this->bindWhere($filters, $stm, 'neighborhood');
        $stm->execute();
            
        // Pega o total de restaurants por bairro
        if ($stm->rowCount() > 0) {
            $data = $stm->fetchAll(\PDO::FETCH_ASSOC);
            
        }

        return $data;
    }

    public function getTotalRatingsByStars($filters = []) {
        $data = [];
        $where = $this->buildWhere($filters, 'rating');

        $stm = $this->db->prepare(
            'SELECT rating, COUNT(*) AS total_ratings_by_star FROM restaurant WHERE '.implode(" AND ", $where).' GROUP BY rating');
        // print_r($stm); exit;   
            
        $this->bindWhere($filters, $stm, 'rating');
        $stm->execute();

        if ($stm->rowCount() > 0) {
            $data = $stm->fetchAll(\PDO::FETCH_ASSOC);
        }  

        return $data;   
    }

    public function getTotalRestaurantsInPromotion($filters = []) {
        $data = 0;

        $where = $this->buildWhere($filters, 'promotion');
        $stm = $this->db->prepare(
            'SELECT COUNT(*) AS total_restaurants_promotion FROM restaurant WHERE '.implode(" AND ", $where));
        $this->bindWhere($filters, $stm, 'promotion');
        // print_r($stm); exit;
        $stm->execute();

        if ($stm->rowCount() > 0) {
            $data = $stm->fetch(\PDO::FETCH_ASSOC);
        }  

        return $data['total_restaurants_promotion'];  
    }

    public function getTotalRestaurantsByWeekDays($filters = []) {
        $data = [];

        $where = $this->buildWhere($filters, 'weekDay');

        $stm = $this->db->prepare(
            'SELECT week_day_id, COUNT(*) AS total_restaurants_by_week_days FROM restaurant_operation WHERE restaurant_id IN(SELECT id_restaurant from restaurant WHERE '.implode(" AND ", $where).') GROUP BY week_day_id');
        // print_r($stm); exit;

        $this->bindWhere($filters, $stm, 'weekDay');
        $stm->execute();
            
        // Pega o total de restaurants que estão funcionando por dia da semana
        if ($stm->rowCount() > 0) {
            $data = $stm->fetchAll(\PDO::FETCH_ASSOC);
            
        }  

        return $data;
    }

    public function getTotalRestaurantsOpen($filters = [], $queryType = 'count') {
        // alterar hora de teste para function current_time

        $data = [];
        $count = 0;

        $where = $this->buildWhere($filters, 'status');

        $stm = $this->db->prepare(
            'SELECT * FROM restaurant_operation WHERE "19:41:00" BETWEEN open_1 AND close_1 OR "19:41:00" BETWEEN open_2 AND close_2 
            AND restaurant_id IN(SELECT id_restaurant FROM restaurant WHERE '.implode(" AND ", $where).')');
        // print_r($stm); exit;

        $this->bindWhere($filters, $stm, 'status');
        $stm->execute();
            
        if ($stm->rowCount() > 0) {
            $data = $stm->fetchAll(\PDO::FETCH_ASSOC);
        }  

        if ($queryType == 'list') {
            return $data;
        }
        
        $data['total_restaurants_open'] = count($data);

        return $data['total_restaurants_open'] ?? $count;
    }

    public function getTotalRestaurantsClosed($filters = [], $queryType = 'count') {
        // alterar hora de teste para function current_time

        $data = [];
        $count = 0;

        $where = $this->buildWhere($filters, 'status');

        $stm = $this->db->prepare(
            'SELECT * FROM restaurant_operation WHERE "19:41:00" NOT BETWEEN open_1 AND close_1 AND "19:41:00" NOT BETWEEN open_2 AND close_2 
            AND restaurant_id IN(SELECT id_restaurant FROM restaurant WHERE '.implode(" AND ", $where).')');
        // print_r($stm); exit;

        $this->bindWhere($filters, $stm, 'status');
        $stm->execute();
            
        // Pega o total de restaurants que estão funcionando por dia da semana
        if ($stm->rowCount() > 0) {
            $data = $stm->fetchAll(\PDO::FETCH_ASSOC);
        }  

        if ($queryType == 'list') {
            return $data;
        }
        
        $data['total_restaurants_closed'] = count($data);

        return $data['total_restaurants_closed'] ?? $count;

    }

    public function getTotalRestaurantsByPaymentTypes($filters = []) {
        $data = [];
        
        $where = $this->buildWhere($filters, 'paymentType');
        
        $stm = $this->db->prepare(
            'SELECT payment_types_id, count(*) AS total_restaurants_by_payment_types FROM restaurant_payment_types 
            WHERE restaurant_id IN(SELECT id_restaurant FROM restaurant WHERE '.implode(" AND ", $where).') GROUP BY payment_types_id');

        $this->bindWhere($filters, $stm, 'paymentType');
        $stm->execute();

        if ($stm->rowCount() > 0) {
            $data = $stm->fetchAll(\PDO::FETCH_ASSOC);
        }  

        return $data;  
    }

    public function getTotalRestaurants($filters = []) {
        $where = $this->buildWhere($filters, '');

        $stm = $this->db->prepare(
            'SELECT COUNT(*) AS total_restaurant FROM restaurant WHERE '.implode(' AND ', $where));

        $this->bindWhere($filters, $stm, '');
        $stm->execute();
        
        $data = $stm->fetch(\PDO::FETCH_ASSOC);

        return $data['total_restaurant'];
 
    }

    // Monta a clausula 'where' a partir dos filtros
    private function buildWhere($filters, $filtersRemoved) {
        $where = ['1=1'];
        $inParamsRestaurant = '';

        if (!empty($filters['category']) && $filtersRemoved != 'category') {
            $where[] = 'id_restaurant IN(SELECT restaurant_id FROM plate WHERE category_id = :categoryId )';
        }

        if (!empty($filters['neighborhood']) && $filtersRemoved != 'neighborhood') {
            $inParamsRestaurant = $this->buildIN($filters['neighborhood'], 'neighborhood');
            $where[] = 'address_id IN(SELECT id_address FROM address WHERE neighborhood_id IN('.$inParamsRestaurant.'))';
        }

        if (isset($filters['rating']) && $filtersRemoved != 'rating') {
            $where[] = 'rating BETWEEN :rating0 AND :rating'.$filters['rating'];
        }

        if (!empty($filters['promotion']) || $filtersRemoved == 'promotion') {
            $where[] = 'id_restaurant IN(SELECT restaurant_id FROM plate WHERE promo = :promo1 GROUP BY restaurant_id)';
        } 

        if (!empty($filters['weekDay']) && $filtersRemoved != 'weekDay') {
            $inParamsRestaurant = $this->buildIN($filters['weekDay'], 'weekDay');
            $where[] = 'id_restaurant IN(SELECT restaurant_id FROM restaurant_operation WHERE week_day_id IN('.$inParamsRestaurant.'))';
        }

        if (!empty($filters['status']) && $filtersRemoved != 'status') {
            $inParamsRestaurant = $this->buildIN($filters['status'], 'weekDay');

            if (count($filters['status']) == 1 && $filters['status'][0] == 1) {
                // Restaurants open
                $where[] = 'id_restaurant IN(SELECT restaurant_id FROM restaurant_operation WHERE "19:41:00" BETWEEN open_1 AND close_1 OR "19:41:00" BETWEEN open_2 AND close_2)';
            } else if (count($filters['status']) == 1 && $filters['status'][0] == 0) {
                // Restaurants closed
                $where[] = 'id_restaurant IN(SELECT restaurant_id FROM restaurant_operation WHERE "19:41:00" NOT BETWEEN open_1 AND close_1 AND "19:41:00" NOT BETWEEN open_2 AND close_2)';
            }
        }

        if (!empty($filters['paymentType']) && !empty(array_values(array_filter($filters['paymentType']))) && $filtersRemoved != 'paymentType') {
            $inParamsRestaurant = $this->buildIN(array_values(array_filter($filters['paymentType'])), 'paymentType');
            $where[] = 'id_restaurant 
            IN(SELECT restaurant_id FROM restaurant_payment_types WHERE payment_types_id IN('.$inParamsRestaurant.') GROUP BY restaurant_id)';
        }

        if (!empty($filters['featured'])) {
            $where[] = 'featured = :featured';  
        } 

        if (!empty($filters['new'])) {
            $where[] = 'new = :new';  
        } 

        if (!empty($filters['option']) && $filtersRemoved != 'option') {
            $inParams = $this->buildIN($filters['option'], 'option');

            $where[] = 'id_restaurant IN(SELECT restaurant_id FROM restaurant_option WHERE restaurant_option.value IN ('.$inParams.'))';
        }

        if(!empty($filters['search'])) {
            $where[] = 'restaurant.name LIKE :search OR '.implode(" AND ", $where).' AND id_restaurant IN(SELECT restaurant_id FROM plate WHERE plate.name LIKE :search OR category_id IN(SELECT id_category FROM category WHERE category.name LIKE :search))';
        }

        return $where;
    }

    //  montagem dos parametros para cada item dentro da clausula do in, pois o preprare não suporta um array por params no in, é necessário definir o mesmo numero de params que o array tem
    private function buildIN($array, $prefix) {
        $inParams = '';
        
        foreach ($array as $key => $value) { 
            if ($key == 0) {
                $inParams = ':'.$prefix.$key;
            } else {
                $inParams .= ', :'.$prefix.$key;
            }
        }

        return $inParams;        
    }

    // Adiciona ou não um bind depedendo da existencia de um filtro que foi adicionado na clausula where
    private function bindWhere($filters, &$stm, $filtersRemoved) {       
        if (!empty($filters['category']) && $filtersRemoved != 'category') {
            $stm->bindValue(':categoryId', $filters['category']);
        }

        if (!empty($filters['neighborhood']) && $filtersRemoved != 'neighborhood') {
            $this->bindIN($filters['neighborhood'], 'neighborhood', $stm);
        }

        if (isset($filters['rating']) && $filtersRemoved != 'rating') {
            $stm->bindValue(':rating0', 0);  
            $stm->bindValue(':rating'.$filters['rating'], $filters['rating']);  
        }

        if (!empty($filters['promotion']) || $filtersRemoved == 'promotion') {
            $stm->bindValue(':promo1', 1);  
        }

        if (!empty($filters['weekDay']) && $filtersRemoved != 'weekDay') {
            $this->bindIN($filters['weekDay'], 'weekDay', $stm);
        }

        if (!empty($filters['paymentType']) && !empty(array_values(array_filter($filters['paymentType']))) && $filtersRemoved != 'paymentType') {
            $this->bindIN(array_values(array_filter($filters['paymentType'])), 'paymentType', $stm);
        }

        if (!empty($filters['featured'])) {
            $stm->bindValue(':featured', 1);  
        } 

        if (!empty($filters['new'])) {
            $stm->bindValue(':new', 1);  
        }

        if(!empty($filters['option']) && $filtersRemoved != 'option') {
            $this->bindIN($filters['option'], 'option', $stm);
        }

        if(!empty($filters['search'])) {
            $stm->bindValue(':search', '%'.$filters['search'].'%');  
        }

        // if (isset($filters['rangePrice0']) || isset($filters['rangePrice1'])) {
        //     $stm->bindValue(':range_price0', $filters['rangePrice0']);            
        //     $stm->bindValue(':range_price1', $filters['rangePrice1']);         
            
        //     if (!empty($filters['promotion']) || $filtersRemoved == 'promotion') {
        //       //    
        //     } else {
        //         $stm->bindValue(':promo0', 0);                
        //         $stm->bindValue(':promo1', 1);                
        //     }
        // }
    }

    // bind para cada item dentro da clausula in
    private function bindIN($array, $prefix, $stm) {
        if (is_array($prefix)) {
            foreach ($prefix as $k => $p) {
                foreach ($array as $key => $value) {
                    $inParam = ':'.$p.$key;
                    $stm->bindValue($inParam, $value);
                }               
            }
        } else {
            foreach ($array as $key => $value) {
                $inParam = ':'.$prefix.$key;
                // echo('INPARAM - '.$inParam);

                $stm->bindValue($inParam, $value);
            }
        }
    }

    // Relationships
    public function getRestaurantPhones($id) {
        try {        
            $stm = $this->db->prepare('SELECT * FROM restaurant_phone 
                WHERE restaurant_id = :restaurantId
            ');
            
            $stm->bindValue(':restaurantId', $id);
            
            $stm->execute();

            if ($stm->rowCount() > 0) {
                $phones = $stm->fetchAll(\PDO::FETCH_ASSOC);

                return $phones;              
            }

        } catch (\PDOException $error) {
            return false; 
            // For debug
            // echo "Message: " . $error->getMessage() . "<br>";
            // echo "Name of file: ". $error->getFile() . "<br>";
            // echo "Row: ". $error->getLine() . "<br>";
        }
    }

    public function getRestaurantOperation($id) {
        try {        
            $stm = $this->db->prepare('SELECT * FROM restaurant_operation 
                WHERE restaurant_id = :restaurantId
            ');
            
            $stm->bindValue(':restaurantId', $id);
            
            $stm->execute();

            if ($stm->rowCount() > 0) {
                $operation = $stm->fetchAll(\PDO::FETCH_ASSOC);

                return $operation;              
            }

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