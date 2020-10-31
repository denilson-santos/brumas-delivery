<?php

namespace App\Models;

class UserPhone extends Model {
    private $data;

    public function __construct($data = []) {
        parent::__construct();
        $this->data = $data;
    }

    public function saveUserPhone() {
        try {
            $stm = $this->db->prepare('INSERT INTO user_phone
                SET user_id = :user_id,
                    phone_type_id = :phone_type_id,
                    number = :number
            ');

            $stm->bindValue(':user_id', $this->data['user_id']);
            $stm->bindValue(':phone_type_id', $this->data['phone_type_id']);
            $stm->bindValue(':number', $this->data['number']);

            $stm->execute();
            
            return $this->db->lastInsertId();
        } catch (\PDOException $error) {
            // return false;
            // For debug
            echo "Message: " . $error->getMessage() . "<br>";
            echo "Name of file: ". $error->getFile() . "<br>";
            echo "Row: ". $error->getLine() . "<br>";
        }
    }

    public function setData($data) {
        $this->data = $data;
    }
}