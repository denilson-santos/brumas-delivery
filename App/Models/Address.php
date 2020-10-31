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

    public function setData($data) {
        $this->data = $data;
    }
}