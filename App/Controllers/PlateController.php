<?php 
namespace App\Controllers;

use App\Models\Complement;
use App\Models\Plate;

class PlateController extends Controller {
    public function getPlate($request) {
        $id = $request['plate_id'];

        $plate = new Plate();
        $complement = new Complement();
        
        $data = [];
 
        $data['plate'] = $plate->getPlate($id);
        $data['plate']['complements'] = $plate->getComplements($id);

        foreach ($data['plate']['complements'] as $key => $c) {
            $data['plate']['complements'][$key]['itens'] = $complement->getItems($c['id_complement']);
        }
        
        echo json_encode($data, JSON_NUMERIC_CHECK);
    }
}