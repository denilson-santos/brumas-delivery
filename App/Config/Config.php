<?php
namespace App\Config;

class Config {
    private $server;
    private $dbName;
    private $user;
    private $password;
    private $environment;
    private $baseUrl;
    private $defaultLang;
    
    public function __construct() {
        $this->environment = $_ENV['ENVIRONMENT'];
        $this->defaultLang = $_ENV['DEFAULT_LANG'];
        
        if($this->environment == 'development') {
            $this->server = $_ENV['SERVER_LOCAL'];
            $this->dbName = $_ENV['DB_NAME_LOCAL'];
            $this->user = $_ENV['USER_NAME_LOCAL'];
            $this->password = $_ENV['PASSWORD_LOCAL'];
            $this->baseUrl = $_ENV['BASE_URL_LOCAL'];
        } else {
            $this->server = $_ENV['SERVER'];
            $this->dbName = $_ENV['DB_NAME'];
            $this->user = $_ENV['USER_NAME'];
            $this->password = $_ENV['PASSWORD'];
            $this->baseUrl = $_ENV['BASE_URL'];
        }
    }

    public function getServer() {
        return $this->server;
    }
    
    public function getDbName() {
        return $this->dbName;
    }

    public function getUser() {
        return  $this->user;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getCurrentBaseUrl() {
        return $this->baseUrl;
    }

    public function getDefaultLang() {
        if ($this->defaultLang == 'pt-br') {
            setlocale(LC_MONETARY, 'pt_BR');
        } else if ($this->defaultLang == 'en') {
            setlocale(LC_MONETARY, 'en_US');
        }

        return $this->defaultLang;
    }

    public function getEnvironment() {
        return $this->environment;
    }
}