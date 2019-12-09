<?php
namespace Core;

use Config\Config;

class Model {
    protected $db;

    public function __construct() {
        $config = new Config();

        $this->db = $config->getConnection();
        
    }
}