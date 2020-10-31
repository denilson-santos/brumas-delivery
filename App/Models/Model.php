<?php
namespace App\Models;

use App\Database\Db;

class Model {
    protected $db;

    public function __construct() {
        $this->db = Db::getConnection();
    }
}