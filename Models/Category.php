<?php
namespace Models;

use Core\Model;

class Category extends Model {
    public function getListCategories($orderBy = '') {
        $data = [];
        
        if (!empty($orderBy)) {
            $orderBy = 'ORDER BY' .$orderBy;
        }

        $stm = $this->db->query('SELECT * FROM category '.$orderBy);
        
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