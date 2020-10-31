<?php
namespace App\Models;

class Neighborhood extends Model {
    public function getListNeighborhoods($filters = []) {
        $data = [];

        $where = $this->buildWhere($filters);
        
        $stm = $this->db->prepare('SELECT * FROM neighborhood WHERE '.implode(' AND ', $where));

        $this->bindWhere($filters, $stm);
        $stm->execute();

        if($stm->rowCount() > 0) {
            $data = $stm->fetchAll(\PDO::FETCH_ASSOC);
        }
        
        return $data;
    }

    private function buildWhere($filters) {
        $where = ['1=1'];

        if (!empty($filters['city'])) {
            $where[] = 'city_id = :city_id';
        }

        return $where;
    }

    private function bindWhere($filters, &$stm) {       
        if (!empty($filters['city'])) {
            $stm->bindValue(':city_id', $filters['city']);
        }
    }
    
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
}