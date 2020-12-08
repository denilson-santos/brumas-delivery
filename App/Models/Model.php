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

    public function saveImage($tempPath, $newPath, $type) {
        if ($type == 'image/jpg' || $type == 'image/jpeg') {
            $file = imagecreatefromjpeg($tempPath);
            imagejpeg($file, $newPath);
        } else if ($type == 'image/png') {
            $file = imagecreatefrompng($tempPath);
            imagepng($file, $newPath);
        }
    }

    public function resizeImage($path, $resize, $newPath = '') {
        $pathInfo = pathinfo($path);
        $extension = $pathInfo['extension'];

        if ($extension == 'jpg' || $extension == 'jpeg') {
            $file = imagecreatefromjpeg($path);
        } else if ($extension == 'png') {
            $file = imagecreatefrompng($path);
        }
        
        // Image width
        $x = imagesx($file);
        // Image height
        $y = imagesy($file);

        if ($x > $y) {
            $proportionX = round($x / $y, 2);
            $newX = $proportionX * $resize;
            $file = imagescale($file, $newX, $resize);
        } else if ($y > $x) {
            $file = imagescale($file, $resize);   
        } else {
            $file = imagescale($file, $resize, $resize);
        }
        
        // Delete old image 
        unlink($path);

        // Save new image resized
        if ($extension == 'jpg' || $extension == 'jpeg') {
            imagejpeg($file, $newPath ? $newPath : $path);
        } else if ($extension == 'png') {
            imagepng($file, $newPath ? $newPath : $path);
        }
    }
}