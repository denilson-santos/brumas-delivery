<?php
namespace App\Models;

use App\Models\Model;

class Complement extends Model {
    private $data;

    public function __construct($data = []) {
        parent::__construct();
        $this->data = $data;
    }

    public function saveComplement() {
        try {
            $stm = $this->db->prepare('INSERT INTO complement
                SET plate_id = :plate_id, 
                    name = :name, 
                    required = :required,
                    multiple = :multiple
            ');

            $stm->bindValue(':plate_id', $this->data['plate_id']);
            $stm->bindValue(':name', $this->data['name']);
            $stm->bindValue(':required', $this->data['required']);
            $stm->bindValue(':multiple', $this->data['multiple']);

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

    // Relationships
    public function getItems($id) {
        try {        
            $stm = $this->db->prepare('SELECT * FROM item 
                WHERE complement_id = :complement_id
            ');
            
            $stm->bindValue(':complement_id', $id);
            
            $stm->execute();

            if ($stm->rowCount() > 0) {
                $items = $stm->fetchAll(\PDO::FETCH_ASSOC);

                return $items;              
            }

        } catch (\PDOException $error) {
            return false; 
            // For debug
            // echo "Message: " . $error->getMessage() . "<br>";
            // echo "Name of file: ". $error->getFile() . "<br>";
            // echo "Row: ". $error->getLine() . "<br>";
        }
    }

    public function setData($data) {
        $this->data = $data;
    }
}