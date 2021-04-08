<?php
namespace App\Models;

use App\Models\Model;

class Payment extends Model {
    
    public function getListPayments() {
        $data = [];

        $stm = $this->db->query('SELECT * FROM payment');
        
        if ($stm->rowCount() > 0) {
            $data = $stm->fetchAll(\PDO::FETCH_ASSOC);
        }

        return $data;
    }
}