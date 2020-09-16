<?php
namespace App\Models;

use App\Models\Model;

class WeekDay extends Model {
    public function getListWeekDays() {
        $data = [];

        $stm = $this->db->query('SELECT * FROM week_day');

        if($stm->rowCount() > 0) {
            $data = $stm->fetchAll(\PDO::FETCH_ASSOC);
        }
        
        return $data;
    }
}