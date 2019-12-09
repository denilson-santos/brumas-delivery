<?php
namespace Core;

class Core {
    public function run() {
        $url = (isset($_GET['url']) ? addslashes($_GET['url']) : '/');
        $params = [];
        
        if(!empty($url) && $url != '/') {
            $url = explode('/', $url);
            // Remove o primeiro item de um array 
            // Os itens do array correspondem ao Controller, Action e Core, respectivamente
            array_shift($url);
            $currentController = ucfirst($url[0]).'Controller';
            array_shift($url);
            
            if(!empty($url[0])) {
                $currentAction = $url[0];
                array_shift($url);
            } else {
                $currentAction = 'index';
            }
            
            if(count($url) > 0) {
                $params = $url;
            }
            
        } else {
            $currentController = 'HomeController';
            $currentAction = 'index';
        }
        
        // como estou criando outros controllers, precisei setar a namespace padrão deles
        $prefix = '\Controllers\\';
        
        if(!file_exists('Controllers/'.$currentController.'.php') || !method_exists($prefix.$currentController, $currentAction)) {
            $currentController = 'NotFoundController';
            $currentAction = 'index';
        }
        
        $newCurrentController = $prefix.$currentController;
        
        $controller = new $newCurrentController();
        
        // Executa a action(metodo) dentro da classe do controller passando parametros
        // Colocar try/catch para tratar o limite de parametros recebidos
        call_user_func_array([$controller, $currentAction], $params);
        // echo 'CONTROLLER: '.$currentController;
        // echo '<br>ACTION: '.$currentAction;
        // echo '<br>PARAMS: ';
        // print_r($params);
    }
}