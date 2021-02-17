<?php

namespace App\Models;

class Address extends Model {
    private $data;

    public function __construct($data = []) {
        parent::__construct();
        $this->data = $data;
    }

    public function saveAddress() {
        try {
            $stm = $this->db->prepare('INSERT INTO address
                SET neighborhood_id = :neighborhood_id, 
                    user_id = :user_id, 
                    name = :name, 
                    number = :number, 
                    complement = :complement
            ');

            $stm->bindValue(':neighborhood_id', $this->data['neighborhood_id']);
            $stm->bindValue(':user_id', $this->data['user_id']);
            $stm->bindValue(':name', $this->data['name']);
            $stm->bindValue(':number', $this->data['number']);
            $stm->bindValue(':complement', $this->data['complement']);

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

    public function updateAddress($idAddress) {
        try {
            $databaseColumns = [
                'accountAddress' => 'name',
                'accountComplement' => 'complement',
                'accountNumber' => 'number',
                'accountNeighborhood' => 'neighborhood_id', 

                'restaurantAddress' => 'name',
                'restaurantComplement' => 'complement',
                'restaurantNumber' => 'number',
                'restaurantNeighborhood' => 'neighborhood_id'
            ];
    
            $columnsChanged = array_keys($this->data);
    
            $setColumns = '';
    
            // Generate named params
            foreach ($columnsChanged as $key => $column) {
                if ($key == count($columnsChanged) -1) {
                    $setColumns .= $databaseColumns[$column] . ' = :' . $databaseColumns[$column];
                } else {
                    $setColumns .= $databaseColumns[$column] . ' = :' . $databaseColumns[$column] . ', ';
                }
            }
        
            $stm = $this->db->prepare("UPDATE address
                SET $setColumns 
                WHERE id_address = :idAddress
            ");
            
            // Replacing named params
            foreach ($columnsChanged as $key => $column) {
                $stm->bindValue(':' . $databaseColumns[$column], $this->data[$column]); 
            }

            $stm->bindValue(':idAddress', $idAddress);

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

    public function setData($data) {
        $this->data = $data;
    }
}