<?php
namespace App\Models;

use App\Database\Db;

class Model {
    protected $db;
    protected $userLevels;

    public function __construct() {
        $this->db = Db::getConnection();

        $this->roles = [
            'admin' => 1,
            'partner' => 2,
            'customer' => 3
        ];
    }

    public function rrmdir($path) {
        $files = glob($path . '/*');
        foreach ($files as $file) {
            is_dir($file) ? $this->rrmdir($file) : unlink($file);
        }
        rmdir($path);
    
        return;
    }

    public function deleteAllFilesInFolder($path) {
        $files = glob("$path/*"); // get all file names
        
        foreach($files as $file){ // iterate files
            if(is_file($file)) {
                unlink($file); // delete file
            }
        }
    }
}