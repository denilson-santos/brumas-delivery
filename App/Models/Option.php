<?php
namespace App\Models;

use App\Core\Model;

class Option extends Model {
    public function getOptionName($id) {
        $stm = $this->db->prepare('SELECT name FROM `option` WHERE id_option = :id_option');
        $stm->bindValue(':id_option', $id);
        $stm->execute();

        if($stm->rowCount() > 0) {
            $data = $stm->fetch(\PDO::FETCH_ASSOC);
            return $data['name'];
        } else {
            return [];
        }
    }
}
