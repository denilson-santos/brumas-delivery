<?php
namespace App\Models;

use App\Models\Model;

class Item extends Model {
    private $data;

    public function __construct($data = []) {
        parent::__construct();
        $this->data = $data;
    }

    public function saveItem() {
        try {
            $stm = $this->db->prepare('INSERT INTO item
                SET complement_id = :complement_id, 
                    name = :name, 
                    price = :price
            ');

            $stm->bindValue(':complement_id', $this->data['complement_id']);
            $stm->bindValue(':name', $this->data['name']);
            $stm->bindValue(':price', $this->data['price']);

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

    public function updateItem($itemId) {
        try {    
            $setColumns = '';
    
            $complementColumns = array_keys($this->data);

            // Generate named params
            foreach ($complementColumns as $key => $column) {
                if ($key == count($complementColumns) -1) {
                    $setColumns .= $column . ' = :' . $column;
                } else {
                    $setColumns .= $column . ' = :' . $column . ', ';
                }
            }
        
            $stm = $this->db->prepare("UPDATE item
                SET $setColumns 
                WHERE id_item = :id_item
            ");
            
            // Replacing named params
            foreach ($complementColumns as $key => $column) {
                $stm->bindValue(':' . $column, $this->data[$column]); 
            }

            $stm->bindValue(':id_item', $itemId);

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

    public function deleteItem($idItem) {
        try {    
            $stm = $this->db->prepare("DELETE FROM item
                WHERE id_item = :id_item
            ");

            $stm->bindValue(':id_item', $idItem);

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

    public function getItem($id) {
        $data = [];

        if (!empty($id)) {
            $stm = $this->db->prepare(
            'SELECT * FROM item WHERE id_item = :id_item');
            
            $stm->bindValue(':id_item', $id);
            $stm->execute();

            if ($stm->rowCount() > 0) {
                $data = $stm->fetch(\PDO::FETCH_ASSOC);
            }
        }

        return $data;
    }

    public function setData($data) {
        $this->data = $data;
    }
}