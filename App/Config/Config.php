<?php
namespace App\Config;

use PDOException;

class Config {
    private $connection;
    private $server;
    private $dbname;
    private $user;
    private $password;
    private $environment;
    private $base_url;
    private $defaultLang;
    
    public function __construct() {
        $this->environment = $_ENV['ENVIRONMENT'];
        $this->defaultLang = $_ENV['DEFAULT_LANG'];
    }

    public function getConnection() {
        if($this->environment == 'development') {
            $this->server = $_ENV['SERVER_LOCAL'];
            $this->dbname = $_ENV['DB_NAME_LOCAL'];
            $this->user = $_ENV['USER_LOCAL'];
            $this->password = $_ENV['PASSWORD_LOCAL'];
        } else {
            $this->base_url = $_ENV['BASE_URL'];
            $this->server = $_ENV['SERVER'];
            $this->dbname = $_ENV['DB_NAME'];
            $this->user = $_ENV['USER'];
            $this->password = $_ENV['PASSWORD'];
        }

        try{
            $utf = array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8');
            $this->connection = new \PDO("mysql:host=".$this->server."; dbname=".$this->dbname, $this->user, $this->password, $utf);
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            
            return $this->connection;
        }catch(PDOException $error) {
            // echo "Mensagem de erro: " . $error->getMessage() . "<br>";
            // echo "Nome do arquivo: ". $error->getFile() . "<br>";
            // echo "Linha: ". $error->getLine() . "<br>";
        }
    }

    public function getCurrentBaseUrl() {
        if($this->environment == 'development') {
            return $this->base_url = $_ENV['BASE_URL_LOCAL'];

        } else {
            return $this->base_url = $_ENV['BASE_URL'];
        }
    }

    public function getDefaultLang() {
        return $this->defaultLang;
    }

    public function setDefaultLang($language) {
        $this->defaultLang = $language;
    }
}