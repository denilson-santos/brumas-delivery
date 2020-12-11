<?php
namespace App\Models;

use App\Database\Db;

class Model {
    protected $db;

    public function __construct() {
        $this->db = Db::getConnection();
    }

    public function rrmdir($path) {
        $files = glob($path . '/*');
        foreach ($files as $file) {
            is_dir($file) ? $this->rrmdir($file) : unlink($file);
        }
        rmdir($path);
    
        return;
    }
}