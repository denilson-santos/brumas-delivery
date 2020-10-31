<?php
namespace App\Database;

use PDOException;

class Db {
    private static $instance = null;
    private $connection; 
    
    private function __construct() {
        $utf = array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8');

        $this->connection = new \PDO("mysql:host=".SERVER."; dbname=".DB_NAME, USER, PASSWORD, $utf);
        $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public static function getInstance() {
        if (empty(self::$instance)) {           
            try{
                self::$instance = new Db();    
            }catch(PDOException $error) {
                // For debug
                echo "Message: " . $error->getMessage() . "<br>";
                echo "Name of file: ". $error->getFile() . "<br>";
                echo "Row: ". $error->getLine() . "<br>";
            }
        }

        return self::$instance;
    }

    public static function getConnection() {
        return Db::getInstance()->connection;
    }
}