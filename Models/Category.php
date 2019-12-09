<?php
namespace Models;

use Core\Model;

class Category extends Model {
    public function getListCategories() {
        $data = [];
        
        $stm = $this->db->query('SELECT * FROM category ORDER BY name');
        
        if ($stm->rowCount() > 0) {
            $data = $stm->fetchAll(\PDO::FETCH_ASSOC);
        }

        return $data;
    }

    public function getCategory($id) {
        $data = [];

        $stm = $this->db->prepare('SELECT * FROM category WHERE id_category = :id_categorie');

        $stm->bindValue(':id_categorie', $id);
        $stm->execute();

        if ($stm->rowCount() > 0) {
            $data = $stm->fetch(\PDO::FETCH_ASSOC);
        }
        
        return $data;
    }
}