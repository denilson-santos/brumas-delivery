<?php
namespace App\Models;

use Intervention\Image\ImageManager;
use Valitron\Validator;

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
            $tempPath = $this->data['brand']['tmp_name'];
            $newPath = "$restaurantPath/brand/$name";
            $newRelativePath = "$relativeRestaurantPath/brand/$name";

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

    public function updateRestaurant($restaurantId, $userId) {
        try {
            $databaseColumns = [
                'restaurantBrand' => 'image',
                'restaurantName' => 'name',
                'restaurantCnpj' => 'cnpj',
                'restaurantEmail' => 'email',
                'restaurantMainCategories' => 'main_categories'
            ];
    
            $columnsChanged = array_keys($this->data);
    
            $setColumns = '';
    
            // Generate named params
            foreach ($columnsChanged as $key => $column) {
                if ($key == count($columnsChanged) -1) {
                    $setColumns .= $databaseColumns[$column] . ' = :' . $databaseColumns[$column];
                } else {
                    $setColumns .= $databaseColumns[$column] . ' = :' . $databaseColumns[$column] . ', ';
                }
            }
        
            $stm = $this->db->prepare("UPDATE restaurant
                SET $setColumns 
                WHERE id_restaurant = :idRestaurant
            ");
            
            // Replacing named params
            foreach ($columnsChanged as $key => $column) {
                if ($column == 'restaurantBrand') {
                    // Save user image
                    $userPath = '/var/www/projects/brumas-delivery/media/users/'.$userId;
    
                    // Delete old Image
                    $this->deleteAllFilesInFolder("$userPath/restaurant/brand");
        
                    $relativeUserPath = '/media/users/'.$userId;
        
                    $name = $this->data['restaurantBrand']['name'];
                    $tempPath = $this->data['restaurantBrand']['tmp_name'];
                    $newPath = "$userPath/restaurant/brand/$name";
                    $newRelativePath = "$relativeUserPath/restaurant/brand/$name";
        
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

                    $stm->bindValue(':' . $databaseColumns[$column], $newRelativePath);
                } else if ($column == 'restaurantMainCategories') {
                    $stm->bindValue(':' . $databaseColumns[$column], $this->data[$column][0]); 
                } else {
                    $stm->bindValue(':' . $databaseColumns[$column], $this->data[$column]); 
                }
            }

            $stm->bindValue(':idRestaurant', $restaurantId);

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
        // unset($filters['paymentType'][1]);
        // print_r($filters); exit;

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

        // if (!empty($filters['paymentType']) && !empty(array_values(array_filter($filters['paymentType']))) && $filtersRemoved != 'paymentType') {
        //     $inParamsRestaurant = $this->buildIN(array_values(array_filter($filters['paymentType'])), 'paymentType');
        //     $where[] = 'id_restaurant 
        //     IN(SELECT restaurant_id FROM restaurant_payment_types WHERE payment_types_id IN('.$inParamsRestaurant.') GROUP BY restaurant_id)';
        // }

        if (!empty($filters['paymentType']) && !empty(array_values(array_filter($filters['paymentType']))) && $filtersRemoved != 'paymentType') {
            $inParamsRestaurant = $this->buildIN(array_values(array_filter($filters['paymentType'])), 'paymentType');
            $where[] = 'id_restaurant 
            IN(SELECT restaurant_id FROM restaurant_payment WHERE payment_id IN(SELECT id_payment FROM payment WHERE payment_types_id IN('.$inParamsRestaurant.')))';
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

    public function validateRestaurantEditForm() {  
        // print_r($this->data); exit; 
        $validator = new Validator($this->data);

        // Add new rules in plugin validation
        $validator->addRule('arrayLengthMax', function($field, $value, array $params, array $fields) {
            return $value && (count($value) <= $params[0]);
        }, 'is array length max items');

        $validator->addRule('arrayLengthMin', function($field, $value, array $params, array $fields) {
            return $value && (count($value) >= $params[0]);
        }, 'is array length min items');

        $validator->addRule('cnpj', function($field, $value, array $params, array $fields) {
            return $this->validateCnpj($value);
        }, 'is invalid cnpj');

        $validator->addRule('uniqueEmail', function($field, $value, array $params, array $fields) {
            return $this->validateUniqueEmail($value);
        }, 'is exist email');

        $validator->addRule('operation', function($field, $value, array $params, array $fields) {            
            if ($this->validateOperation($value)) {
                return true;
            }
            return false;
        }, 'is invalid operation');
        
        $validator->addRule('socialMedias', function($field, $value, array $params, array $fields) {            
            if ($this->validateSocialMedias($value)) {
                return true;
            }
            return false;
        }, 'is invalid social medias');
        
        $validator->addRule('accept', function($field, $value, array $params, array $fields) {
            return !empty($value['type']) && in_array($value['type'], $params[0]);
        }, 'Formato inválido, use: (.jpg, .jpeg ou .png)');
        
        $validator->addRule('filesize', function($field, $value, array $params, array $fields) {
            return !empty($value['size']) && ($value['size'] <= ($params[0] * 1000000));
        }, 'The upload limit is 30mb');

        if (array_key_exists('restaurantBrand', $this->data)) {
            // restaurantBrand
            $validator->rule('required', 'restaurantBrand')->message('Adicione uma logo para o restaurante');
            $validator->rule('filesize', 'restaurantBrand', 30)->message('O limite para upload é de 30mb');
            $validator->rule('accept', 'restaurantBrand', ['image/jpg', 'image/jpeg', 'image/png'])->message('Formato inválido, use: (.jpg, .jpeg ou .png)');
        }

        if (array_key_exists('restaurantName', $this->data)) {
            // restaurantName
            $validator->rule('required', 'restaurantName')->message('Digite o nome do restaurante');
            $validator->rule('lengthMin', 'restaurantName', 2)->message('O nome do restaurante precisa ter no mínimo 2 caracteres');
            $validator->rule('lengthMax', 'restaurantName', 50)->message('O nome do restaurante precisa ter no máximo 50 caracteres');
        }

        if (array_key_exists('restaurantCnpj', $this->data)) {
            // restaurantCnpj
            $validator->rule('required', 'restaurantCnpj')->message('Digite o cnpj do restaurante');
            $validator->rule('lengthMin', 'restaurantCnpj', 14)->message('O cnpj precisa ter no mínimo 14 dígitos');
            $validator->rule('lengthMax', 'restaurantCnpj', 14)->message('O cnpj precisa ter no máximo 14 dígitos');
            $validator->rule('cnpj', 'restaurantCnpj')->message('Digite um cnpj válido');
        }

        if (array_key_exists('restaurantEmail', $this->data)) {
            // restaurantEmail
            $validator->rule('required', 'restaurantEmail')->message('Digite o email do restaurante');
            $validator->rule('lengthMin', 'restaurantEmail', 7)->message('O email precisa ter no mínimo 7 caracteres');
            $validator->rule('lengthMax', 'restaurantEmail', 100)->message('O email precisa ter no máximo 100 caracteres');
            $validator->rule('email', 'restaurantEmail')->message('Digite um email válido');
            $validator->rule('uniqueEmail', 'restaurantEmail')->message('Email já cadastrado');
        }

        if (array_key_exists('restaurantPhone', $this->data)) {
            // restaurantPhone
            $validator->rule('required', 'restaurantPhone')->message('Digite o telefone do restaurante');
            $validator->rule('lengthMin', 'restaurantPhone', 10)->message('O telefone precisa ter no mínimo o DDD + 8 dígitos');
            $validator->rule('lengthMax', 'restaurantPhone', 10)->message('O telefone precisa ter no máximo o DDD + 8 dígitos');
        }

        if (array_key_exists('restaurantCellPhone', $this->data)) {
            // restaurantCellPhone
            $validator->rule('required', 'restaurantCellPhone')->message('Digite o celular do restaurante');
            $validator->rule('lengthMin', 'restaurantCellPhone', 11)->message('O celular precisa ter no mínimo o DDD + 9 dígitos');
            $validator->rule('lengthMax', 'restaurantCellPhone', 11)->message('O celular precisa ter no máximo o DDD + 9 dígitos');
        }

        if (array_key_exists('restaurantMainCategories', $this->data)) {
            // restaurantMainCategories 
            $validator->rule('required', 'restaurantMainCategories')->message('Selecione 1 ou no máx 2 categorias principais para o restaurante');
            $validator->rule('arrayLengthMax', 'restaurantMainCategories', 2)->message('Selecione no máximo 2 categorias');
        }

        if (array_key_exists('operation', $this->data)) {
            // operation
            $validator->rule('required', 'operation')->message('Informe os hórarios de funcionamento do restaurante');
            $validator->rule('operation', 'operation')->message('Informe hórarios válidos');
            $validator->rule('arrayLengthMax', 'operation', 8)->message('Informe hórarios válidos');
            $validator->rule('arrayLengthMin', 'operation', 8)->message('Informe hórarios válidos');
        }

        if (array_key_exists('restaurantAddress', $this->data)) {
            // restaurantAddress
            $validator->rule('required', 'restaurantAddress')->message('Digite o endereço do restaurante');
            $validator->rule('lengthMin', 'restaurantAddress', 4)->message('O endereço precisa ter no mínimo 4 caracteres');
            $validator->rule('lengthMax', 'restaurantAddress', 50)->message('O endereço precisa ter no máximo 50 caracteres');
        }

        if (array_key_exists('restaurantNeighborhood', $this->data)) {
            // restaurantNeighborhood
            $validator->rule('required', 'restaurantNeighborhood')->message('Informe o bairro do restaurante');
            $validator->rule('integer', 'restaurantNeighborhood')->message('Informe um bairro válido');
        }

        if (array_key_exists('restaurantNumber', $this->data)) {
            // restaurantNumber
            $validator->rule('required', 'restaurantNumber')->message('Digite o número do restaurante');
            $validator->rule('lengthMax', 'restaurantNumber', 11)->message('O número precisa ter no máximo 11 caracteres');
        }

        if (array_key_exists('restaurantState', $this->data)) {
            // restaurantState
            $validator->rule('required', 'restaurantState')->message('Informe o estado do restaurante');
            $validator->rule('integer', 'restaurantState')->message('Informe um estado válido');
        }

        if (array_key_exists('restaurantCity', $this->data)) {
            // restaurantCity
            $validator->rule('required', 'restaurantCity')->message('Informe a cidade do restaurante');
            $validator->rule('integer', 'restaurantCity')->message('Informe uma cidade válida');
        }

        if (array_key_exists('restaurantComplement', $this->data)) {
            // restaurantComplement
            $validator->rule('lengthMax', 'restaurantComplement', 50)->message('O complemento precisa ter no máximo 50 caracteres');
        }

        if (array_key_exists('socialMedias', $this->data)) {
            // socialMedias
            $validator->rule('socialMedias', 'socialMedias')->message('Informe mídias sociais válidas');
            $validator->rule('arrayLengthMax', 'socialMedias', 5)->message('Informe mídias sociais válidas');
            $validator->rule('arrayLengthMin', 'socialMedias', 5)->message('Informe mídias sociais válidas');
        }

        if (array_key_exists('paymentMethods', $this->data)) {
            // paymentMethods
            $validator->rule('arrayLengthMax', 'paymentMethods', 1)->message('Informe métodos de pagamento válidos');
            $validator->rule('arrayLengthMin', 'paymentMethods', 1)->message('Informe métodos de pagamento válidos');
        }

        if($validator->validate()) {
            return ['validate' => true];
        } else {
            // Errors
            return ['validate' => false, 'errors' => $validator->errors()];
        }
    }

    public function validateCnpj($cnpj)	{
        if (!$cnpj) return false;

        //Etapa 1: Cria um array com apenas os digitos numéricos, isso permite receber o cnpj em diferentes formatos como "00.000.000/0000-00", "00000000000000", "00 000 000 0000 00" etc...
        $j=0;
        for($i=0; $i<(strlen($cnpj)); $i++)
            {
                if(is_numeric($cnpj[$i]))
                    {
                        $num[$j]=$cnpj[$i];
                        $j++;
                    }
            }
        //Etapa 2: Conta os dígitos, um Cnpj válido possui 14 dígitos numéricos.
        if(count($num)!=14)
            {
                $isCnpjValid=false;
            }
        //Etapa 3: O número 00000000000 embora não seja um cnpj real resultaria um cnpj válido após o calculo dos dígitos verificares e por isso precisa ser filtradas nesta etapa.
        if ($num[0]==0 && $num[1]==0 && $num[2]==0 && $num[3]==0 && $num[4]==0 && $num[5]==0 && $num[6]==0 && $num[7]==0 && $num[8]==0 && $num[9]==0 && $num[10]==0 && $num[11]==0)
            {
                $isCnpjValid=false;
            }
        //Etapa 4: Calcula e compara o primeiro dígito verificador.
        else
            {
                $j=5;
                for($i=0; $i<4; $i++)
                    {
                        $multiplica[$i]=$num[$i]*$j;
                        $j--;
                    }
                $soma = array_sum($multiplica);
                $j=9;
                for($i=4; $i<12; $i++)
                    {
                        $multiplica[$i]=$num[$i]*$j;
                        $j--;
                    }
                $soma = array_sum($multiplica);	
                $resto = $soma%11;			
                if($resto<2)
                    {
                        $dg=0;
                    }
                else
                    {
                        $dg=11-$resto;
                    }
                if($dg!=$num[12])
                    {
                        $isCnpjValid=false;
                    } 
            }
        //Etapa 5: Calcula e compara o segundo dígito verificador.
        if(!isset($isCnpjValid))
            {
                $j=6;
                for($i=0; $i<5; $i++)
                    {
                        $multiplica[$i]=$num[$i]*$j;
                        $j--;
                    }
                $soma = array_sum($multiplica);
                $j=9;
                for($i=5; $i<13; $i++)
                    {
                        $multiplica[$i]=$num[$i]*$j;
                        $j--;
                    }
                $soma = array_sum($multiplica);	
                $resto = $soma%11;			
                if($resto<2)
                    {
                        $dg=0;
                    }
                else
                    {
                        $dg=11-$resto;
                    }
                if($dg!=$num[13])
                    {
                        $isCnpjValid=false;
                    }
                else
                    {
                        $isCnpjValid=true;
                    }
            }
        
        //Etapa 6: Retorna o Resultado em um valor booleano.
        return $isCnpjValid;			
	}

    public function validateOperation($rows) {
        $operationRows = count($rows['row']);

        for ($i=0; $i < $operationRows; $i++) { 
            $weekDay = $rows['dayIndex'][$i];
            $dayOpen1 = $rows['open1'][$i];
            $dayClose1 = $rows['close1'][$i];
            $dayOpen2 = $rows['open2'][$i];
            $dayClose2 = $rows['close2'][$i];     
            
            $validateWeekDay = $weekDay ?? false;
            $validateSchedule1 = $dayOpen1 && $dayClose1;
            $validateSchedule2 = $validateSchedule1 && ($dayOpen2 && $dayClose2 || !$dayOpen2 && !$dayClose2 );

            if (strlen($dayOpen1) === 4) {
                $dayOpen1 = "0$dayOpen1";
            }
            
            if (strlen($dayClose1) === 4) {
                $dayClose1 = "0$dayClose1";
            }
            
            if (strlen($dayOpen2) === 4) {
                $dayOpen2 = "0$dayOpen2";
            }
            
            if (strlen($dayClose2) === 4) {
                $dayClose2 = "0$dayClose2";
            }

            // Parse schedules to mins and validate range of schedules
            if ($validateSchedule1) {
                if ($dayOpen1 >= $dayClose1) {
                    $validateSchedule1 = false;
                    $validateSchedule2 = false;
                }

                if ($dayOpen1 && $dayClose2) {
                    if ($dayOpen2 >= $dayClose2) {
                        $validateSchedule2 = false;
                    } else if ($dayOpen2 <= $dayOpen1 || $dayOpen2 <= $dayClose1 ) {
                        $validateSchedule2 = false;
                    }

                }
            }

            $validateRow = $validateWeekDay && $validateSchedule1 && $validateSchedule2;
            
            $validation = [
                'validateWeekDay' => $validateWeekDay,
                'validateSchedule1' => $validateSchedule1,
                'validateSchedule2' => $validateSchedule2,
                'validateRow' => $validateRow
            ];
            
            // print_r($validation);

            if (!$validation['validateRow']) return false;
            
            return true;
        }
    }   
    
    public function validateSocialMedias($rows) {
        $socialMediasRows = count($rows['socialMediaRow']);
        
        for ($i=0; $i < $socialMediasRows; $i++) { 
            $socialMedia = $rows['socialMediaIndex'][$i];
            $linkOrPhone = $rows['linkOrPhone'][$i];
            
            $validateSocialMedia = $socialMedia ?? false;
            $validateLinkOrPhone = $linkOrPhone ?? false;
            
            $validateRow = $validateSocialMedia && $validateLinkOrPhone;
            
            $validation = [
                'validateSocialMedia' => $validateSocialMedia,
                'validateLinkOrPhone' => $validateLinkOrPhone,
                'validateRow' => $validateRow
            ];
            
            if (!$validation['validateRow']) return false;
            
            return true;
        }
    }   

    public function validateUniqueEmail($email) {
        try {
            $stm = $this->db->prepare('SELECT * FROM user
                WHERE email = :email
            ');
            
            $stm->bindValue(':email', $email);
            $stm->execute();

            if ($stm->rowCount() > 0) return false;
            return true;
        } catch(\PDOException $error) {
            return false; 
            
            // For debug
            // echo "Message: " . $error->getMessage() . "<br>";
            // echo "Name of file: ". $error->getFile() . "<br>";
            // echo "Row: ". $error->getLine() . "<br>";
        }
    }

    public function saveRestaurantEditForm($restaurantId, $userId, $addressId) {
        try {
            $this->db->beginTransaction();

            $restaurantColumns = [
                'restaurantBrand',
                'restaurantName',
                'restaurantCnpj',
                'restaurantEmail',
                'restaurantMainCategories'
            ];
            
            $dataRestaurant = [];

            foreach ($restaurantColumns as $column) {
                if (in_array($column, array_keys($this->data))) {
                    $dataRestaurant[$column] = $this->data[$column];
                }
            }

            if (count($dataRestaurant) > 0) {
                $restaurant = new Restaurant($dataRestaurant);
                $restaurant->updateRestaurant($restaurantId, $userId);
            }

            $restaurantAddressColumns = [
                'restaurantAddress',
                'restaurantComplement',
                'restaurantNumber',
                'restaurantNeighborhood'
            ];
            
            $dataRestaurantAddress = [];

            foreach ($restaurantAddressColumns as $column) {
                if (in_array($column, array_keys($this->data))) {
                    $dataRestaurantAddress[$column] = $this->data[$column];
                }
            }

            if (count($dataRestaurantAddress) > 0) {
                $address = new Address($dataRestaurantAddress);
                $address->updateAddress($addressId);
            }

            $restaurantPhonesColumns = [
                'restaurantPhone',
                'restaurantCellPhone'
            ];
            
            $dataRestaurantPhones = [];

            foreach ($restaurantPhonesColumns as $column) {
                if (in_array($column, array_keys($this->data))) {
                    $dataRestaurantPhones[$column] = $this->data[$column];
                }
            }

            if (count($dataRestaurantPhones) > 0) {
                $restaurantPhones = new RestaurantPhone($dataRestaurantPhones);
                $restaurantPhones->updateRestaurantPhones($restaurantId);
            }
            
            if (!empty($this->data['operation'])) {
                $countOperationRows = count($this->data['operation']['row']);
                $restaurantOperation = new RestaurantOperation();
    
                for ($i=0; $i < $countOperationRows; $i++) { 
                    $dataRestaurantOperation = [
                        'restaurant_id' => $restaurantId,
                        'week_day_id' => $this->data['operation']['dayIndex'][$i],
                        'open_1' => $this->data['operation']['open1'][$i],
                        'close_1' => $this->data['operation']['close1'][$i],
                        'open_2' => $this->data['operation']['open2'][$i],
                        'close_2' => $this->data['operation']['close2'][$i]
                    ];    
    
                    $restaurantOperation->setData($dataRestaurantOperation);

                    $idOperation = $this->data['operation']['idOperation'][$i];

                    if ($idOperation) {
                        $restaurantOperation->updateRestaurantOperation($restaurantId, $idOperation);
                    } else {
                        $restaurantOperation->saveRestaurantOperation();
                    }
                }
            }

            if (!empty($this->data['operationDeleteds'])) {
                $countOperationRowsDeleteds = count($this->data['operationDeleteds']['row']);
                $restaurantOperation = new RestaurantOperation();
    
                for ($i=0; $i < $countOperationRowsDeleteds; $i++) {    
                    $idOperation = $this->data['operationDeleteds']['idOperation'][$i];

                    $restaurantOperation->deleteRestaurantOperation($restaurantId, $idOperation);
                }
            }

            if (!empty($this->data['socialMedias'])) {
                $countSocialMediasRows = count($this->data['socialMedias']['socialMediaRow']);
                $restaurantSocialMedia = new RestaurantSocialMedia();
    
                for ($i=0; $i < $countSocialMediasRows; $i++) { 
                    $dataRestaurantSocialMedia = [
                        'restaurant_id' => $restaurantId,
                        'social_media_id' => $this->data['socialMedias']['socialMediaIndex'][$i],
                        'value' => $this->data['socialMedias']['linkOrPhone'][$i]
                    ];    
    
                    $restaurantSocialMedia->setData($dataRestaurantSocialMedia);

                    $idSocialMedia = $this->data['socialMedias']['idSocialMedia'][$i];

                    if ($idSocialMedia) {
                        $restaurantSocialMedia->updateRestaurantSocialMedia($restaurantId, $idSocialMedia);
                    } else {
                        $restaurantSocialMedia->saveRestaurantSocialMedia();
                    }
                }
            }

            if (!empty($this->data['socialMediasDeleteds'])) {
                $countSocialMediasRowsDeleteds = count($this->data['socialMediasDeleteds']['row']);
                $restaurantSocialMedia = new RestaurantSocialMedia();
    
                for ($i=0; $i < $countSocialMediasRowsDeleteds; $i++) {    
                    $idSocialMedia = $this->data['socialMediasDeleteds']['idSocialMedia'][$i];

                    $restaurantSocialMedia->deleteRestaurantSocialMedia($restaurantId, $idSocialMedia);
                }
            }

            if (!empty($this->data['payments'])) {
                $countPaymentRows = count($this->data['payments']['idPayment']);
                $restaurantPayment = new RestaurantPayment();
    
                for ($i=0; $i < $countPaymentRows; $i++) { 
                    $dataRestaurantPayment = [
                        'restaurant_id' => $restaurantId,
                        'payment_id' => $this->data['payments']['idPayment'][$i]
                    ];    
    
                    $restaurantPayment->setData($dataRestaurantPayment);

                    $paymentSaved = $this->data['payments']['paymentSaved'][$i];
                    $idPayment = $dataRestaurantPayment['payment_id'];

                    if (!$paymentSaved) {
                        $restaurantPayment->saveRestaurantPayment();
                    }
                }
            }

            if (!empty($this->data['paymentsDeleteds'])) {
                $countPaymentRowsDeleteds = count($this->data['paymentsDeleteds']['idPayment']);
                $restaurantPayment = new RestaurantPayment();
    
                for ($i=0; $i < $countPaymentRowsDeleteds; $i++) {    
                    $idPayment = $this->data['paymentsDeleteds']['idPayment'][$i];

                    $restaurantPayment->deleteRestaurantPayment($restaurantId, $idPayment);
                }
            }

            $this->db->commit();
        } catch (\PDOException $error) {            
            $this->db->rollback();

            // For debug
            // echo "Message: " . $error->getMessage() . "<br>";
            // echo "Name of file: ". $error->getFile() . "<br>";
            // echo "Row: ". $error->getLine() . "<br>";

            throw new \PDOException("Error in statement", 0);
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

    public function getRestaurantSocialMedias($id) {
        try {        
            $stm = $this->db->prepare('SELECT * FROM restaurant_social_media 
                WHERE restaurant_id = :restaurantId
            ');
            
            $stm->bindValue(':restaurantId', $id);
            
            $stm->execute();

            if ($stm->rowCount() > 0) {
                $socialMedias = $stm->fetchAll(\PDO::FETCH_ASSOC);

                return $socialMedias;              
            }

        } catch (\PDOException $error) {
            return false; 
            // For debug
            // echo "Message: " . $error->getMessage() . "<br>";
            // echo "Name of file: ". $error->getFile() . "<br>";
            // echo "Row: ". $error->getLine() . "<br>";
        }
    }

    public function getRestaurantPayments($id) {
        try {        
            $stm = $this->db->prepare('SELECT * FROM restaurant_payment 
                WHERE restaurant_id = :restaurantId
            ');
            
            $stm->bindValue(':restaurantId', $id);
            
            $stm->execute();

            if ($stm->rowCount() > 0) {
                $payments = $stm->fetchAll(\PDO::FETCH_ASSOC);

                return $payments;              
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