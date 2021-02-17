<?php
namespace App\Models;

use App\Models\Model;

class SocialMedia extends Model {
    public function getListSocialMedias() {
        $data = [];

        $stm = $this->db->query('SELECT * FROM social_media');

        if($stm->rowCount() > 0) {
            $data = $stm->fetchAll(\PDO::FETCH_ASSOC);
        }
        
        return $data;
    }
}