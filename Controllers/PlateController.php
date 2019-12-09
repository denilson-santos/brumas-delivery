<?php 
namespace Controllers;

use Core\Controller;
use Models\Category;
use Models\Filter;
use Models\Plate;

class PlateController extends Controller {
    public function index() {
        header('Location: '.BASE_URL);        
    }

    public function open($id) {
        $plate = new Plate();
        $category = new Category();
        
        $data = [];
       
        $plateInfo = $plate->getPlate($id);
 
        if (!empty($plateInfo)) {
            $data = [
                'plateInfo' => $plateInfo,
                'plateImages' => $plate->getImagesByPlateId($id),
                'categories' => $category->getListCategories()
            ];

            $this->loadTemplateHeaderFooter('pages/plate/plate', $data);
        } else {
            header('Location: '.BASE_URL);
        }
    }

    public function setQuantity($quantity) {
        $plate = new Plate();
        $plate->setQuantity($quantity);
    }
}
