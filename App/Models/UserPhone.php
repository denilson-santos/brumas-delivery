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
            // For debug
            // echo "Message: " . $error->getMessage() . "<br>";
            // echo "Name of file: ". $error->getFile() . "<br>";
            // echo "Row: ". $error->getLine() . "<br>";

            throw new \PDOException("Error in statement", 0);
        }
    }

    public function updateUserPhones($userId) {
        try {
            $databaseColumns = [
                'number'
            ];
    
            $columnsChanged = array_keys($this->data);
    
            $setColumns = '';
    
            // Generate named params
            foreach ($databaseColumns as $key => $column) {
                if ($key == count($databaseColumns) -1) {
                    $setColumns .= $column . ' = :' . $column;
                } else {
                    $setColumns .= $column . ' = :' . $column . ', ';
                }
            }

            // Replacing named params
            foreach ($columnsChanged as $key => $column) {   
                $stm = $this->db->prepare("UPDATE user_phone
                    SET $setColumns 
                    WHERE user_id = :userId
                    AND phone_type_id = :phoneTypeId
                ");

                foreach ($databaseColumns as $databaseColumn) {
                    $stm->bindValue(':' . $databaseColumn, $this->data[$column]); 
                }

                $stm->bindValue(':phoneTypeId', 2);
                $stm->bindValue(':restaurantId', $userId);

                $stm->execute();
            }
            
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