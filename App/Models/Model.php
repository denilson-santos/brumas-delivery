<?php
namespace App\Models;

use App\Config\Config;

class Model {
    protected $db;
    protected $validator;

    public function __construct() {
        $config = new Config();
        $this->db = $config->getConnection();
    }
}