<?php 
namespace App\Controllers;

use App\Models\Complement;
use App\Models\Plate;
use App\Models\Restaurant;

class PlateController extends Controller {
    public function getPlate($request) {
        $id = $request['id'];
        
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

    public function editPlate($request) {        
        if (!empty($_FILES['plateImage']))  {
            $request['plateImage'] = $_FILES['plateImage'];
        };

        $request = $this->sanitizeInputs($request);
        
        if (!empty($request['complementRow'])) {
            $request['complements'] = [
                'idComplement' => $request['idComplement'] ?? '',
                'complementRow' => $request['complementRow'],
                'complementName' => $request['complementName'],
                'complementRequired' => $request['complementRequired'],
                'complementMultiple' => $request['complementMultiple']
            ];
            
            unset($request['idComplement']);
            unset($request['complementRow']);
            unset($request['complementName']);
            unset($request['complementRequired']);
            unset($request['complementMultiple']);
        }

        if (!empty($request['complementRowDeleted'])) {
            $request['complementsDeleteds'] = [
                'idComplement' => $request['idComplementDeleted'],
                'complementRow' => $request['complementRowDeleted']
            ];
            
            unset($request['idComplementDeleted']);
            unset($request['complementRowDeleted']);
        }

        if (!empty($request['itemRow'])) {
            $request['items'] = [
                'idItem' => $request['idItem'] ?? '',
                'itemRow' => $request['itemRow'],
                'itemComplementId' => $request['itemComplementId'] ?? '',
                'itemComplementRow' => $request['itemComplementRow'],
                'itemName' => $request['itemName'],
                'itemPrice' => $request['itemPrice'],
            ];
            
            unset($request['idItem']);
            unset($request['itemRow']);
            unset($request['itemComplementId']);
            unset($request['itemComplementRow']);
            unset($request['itemName']);
            unset($request['itemPrice']);
        }

        if (!empty($request['itemRowDeleted'])) {
            $request['itemsDeleteds'] = [
                'idItem' => $request['idItemDeleted'],
                'itemRow' => $request['itemRowDeleted']
            ];
            
            unset($request['idItemDeleted']);
            unset($request['itemRowDeleted']);
        }
        
        $restaurant = new Restaurant($request);
        
        $validation = $restaurant->validateRestaurantEditPlateForm();

        if ($validation['validate']) {
            $restaurant->saveRestaurantPlateEditForm();

            echo json_encode($validation);
        } else {
            echo json_encode($validation);
        }
    }

    public function deletePlate($request) {
        $plate = new Plate();
        $complement = new Complement();

        $complements = $plate->getComplements($request['plate_id']);

        foreach ($complements as $complementData) {
            $complement->deleteItems($complementData['id_complement']);
            $complement->deleteComplement($complementData['id_complement']);
        }

        $plate->deletePlate($request['plate_id']);    
    }
}