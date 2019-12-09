<?php
namespace models;

use Core\Model;

class Neighborhood extends Model {
    public function getListNeighborhoods() {
        $data = [];

        $stm = $this->db->query('SELECT * FROM neighborhood');

        if($stm->rowCount() > 0) {
            $data = $stm->fetchAll(\PDO::FETCH_ASSOC);
        }
        
        return $data;
    }
}