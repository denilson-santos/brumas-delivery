<?php
namespace App\Models;

use App\Models\Model;

class PaymentTypes extends Model {
    
    public function getListPaymentTypes() {
        $data = [];

        $stm = $this->db->query('SELECT * FROM payment_types');
        // print_r($stm); exit;
        
        if ($stm->rowCount() > 0) {
            $data = $stm->fetchAll(\PDO::FETCH_ASSOC);
        }

        return $data;
    }
}