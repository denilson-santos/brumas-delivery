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

    public function updateComplement($complementId) {
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
        
            $stm = $this->db->prepare("UPDATE complement
                SET $setColumns 
                WHERE id_complement = :id_complement
            ");
            
            // Replacing named params
            foreach ($complementColumns as $key => $column) {
                $stm->bindValue(':' . $column, $this->data[$column]); 
            }

            $stm->bindValue(':id_complement', $complementId);

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

    public function deleteComplement($idComplement) {
        try {    
            $stm = $this->db->prepare("DELETE FROM item
                WHERE complement_id = :complement_id
            ");

            $stm->bindValue(':complement_id', $idComplement);

            $stm->execute();

            $stm = $this->db->prepare("DELETE FROM complement
                WHERE id_complement = :id_complement
            ");

            $stm->bindValue(':id_complement', $idComplement);

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

    public function getComplement($id) {
        $data = [];

        if (!empty($id)) {
            $stm = $this->db->prepare(
            'SELECT * FROM complement WHERE id_complement = :id_complement');
            
            $stm->bindValue(':id_complement', $id);
            $stm->execute();

            if ($stm->rowCount() > 0) {
                $data = $stm->fetch(\PDO::FETCH_ASSOC);
            }
        }

        return $data;
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
    
    public function deleteItems($id) {
        try {        
            $stm = $this->db->prepare('DELETE FROM item 
                WHERE complement_id = :complement_id
            ');
            
            $stm->bindValue(':complement_id', $id);
            
            $stm->execute();
            
            return true;

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