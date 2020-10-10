<?php
namespace App\Models;

use Valitron\Validator;

class User extends Model {
    private $data;

    public function __construct($data = []) {
        $this->data = $data;
    }

    public function register($data) {      
        $stm = $this->connection->prepare("SELECT id_usuario FROM usuario WHERE accountEmail = ?");
        $stm->execute([$accountEmail]);

        if($stm->rowCount() == 0) {
            $stm = $this->connection->prepare("INSERT INTO usuario SET nome = ?, accountEmail = ?, telefone = ?, senha = ?");
            $stm->execute([$nome, $accountEmail, $telefone, $senha]);

            return true;
        } else {
            return false;
        }
    }

    public function logar($accountEmail, $senha) {
        $stm = $this->connection->prepare("SELECT id_usuario, nome FROM usuario WHERE accountEmail = ? and senha = ?");
        $stm->execute([$accountEmail, $senha]);

        if($stm->rowCount() > 0) {
            $row = $stm->fetch();
            $_SESSION['id_usuario'] = $row["id_usuario"];
            $_SESSION['nome'] = $row["nome"];
                
            return true;
        } else {
            return false;
        }
    }

    public function validateUserRegister() {        
        $validator = new Validator($this->data);

        $validator->addRule('arrayLengthMax', function($field, $value, array $params, array $fields) {
            if (count($value) > $params[0]) {
                return false;
            }
            return true;
        }, 'is array length max items');

        $validator->addRule('arrayLengthMin', function($field, $value, array $params, array $fields) {
            if (count($value) <= $params[0]) {
                return true;
            }
            return false;
        }, 'is array length min items');

        $validator->addRule('cnpj', function($field, $value, array $params, array $fields) {
            if (!$this->validateCnpj($value)) {
                return false;
            }
            return true;
        }, 'is invalid cnpj');

        $validator->addRule('operation', function($field, $value, array $params, array $fields) {            
            if (!$this->validateOperation($value)) {
                return false;
            }
            return true;
        }, 'is invalid operation');

        // Restaurant Partner level
        if ($this->data['userLevel'] === 2) {            
            // firstName
            $validator->rule('required', 'accountFirstName')->message('Digite seu primeiro nome');
            $validator->rule('lengthMin', 'accountFirstName', 2)->message('O seu primeiro nome precisa ter no mínimo 2 caracteres');
            $validator->rule('lengthMax', 'accountFirstName', 30)->message('O seu primeiro nome precisa ter no máximo 30 caracteres');

            // accountLastName
            $validator->rule('required', 'accountLastName')->message('Digite seu sobrenome');
            $validator->rule('lengthMin', 'accountLastName', 4)->message('O seu sobrenome precisa ter no mínimo 4 caracteres');
            $validator->rule('lengthMax', 'accountLastName', 30)->message('O seu sobrenome precisa ter no máximo 30 caracteres');

            // accountEmail
            $validator->rule('required', 'accountEmail')->message('Digite seu email');
            $validator->rule('lengthMin', 'accountEmail', 7)->message('O email precisa ter no mínimo 7 caracteres');
            $validator->rule('lengthMax', 'accountEmail', 100)->message('O accountEmail precisa ter no máximo 30 caracteres');
            $validator->rule('email', 'accountEmail')->message('Digite um email válido');

            // cellPhone
            $validator->rule('required', 'accountCellPhone')->message('Digite seu celular');
            $validator->rule('lengthMin', 'accountCellPhone', 11)->message('O celular precisa ter no mínimo o DDD + 9 dígitos');
            $validator->rule('lengthMax', 'accountCellPhone', 11)->message('O celular precisa ter no máximo o DDD + 9 dígitos');

            // address
            $validator->rule('required', 'accountAddress')->message('Digite seu endereço');
            $validator->rule('lengthMin', 'accountAddress', 4)->message('O endereço precisa ter no mínimo 4 caracteres');
            $validator->rule('lengthMax', 'accountAddress', 50)->message('O endereço precisa ter no máximo 50 caracteres');

            // neighborhood
            $validator->rule('required', 'accountNeighborhood')->message('Digite seu bairro');
            $validator->rule('lengthMin', 'accountNeighborhood', 4)->message('O bairro precisa ter no mínimo 4 caracteres');
            $validator->rule('lengthMax', 'accountNeighborhood', 50)->message('O bairro precisa ter no máximo 50 caracteres');

            // number
            $validator->rule('required', 'accountNumber')->message('Digite seu número');
            $validator->rule('lengthMax', 'accountNumber', 11)->message('O seu número precisa ter no máximo 11 caracteres');

            // state
            $validator->rule('required', 'accountState')->message('Informe seu estado');
            $validator->rule('integer', 'accountState')->message('Informe um estado válido');

            // city
            $validator->rule('required', 'accountCity')->message('Informe sua cidade');
            $validator->rule('integer', 'accountCity')->message('Informe uma cidade válida');

            // complement
            $validator->rule('lengthMax', 'accountComplement', 50)->message('O complemento precisa ter no máximo 50 caracteres');

            // restaurantName
            $validator->rule('required', 'restaurantName')->message('Digite o nome do restaurante');
            $validator->rule('lengthMin', 'restaurantName', 2)->message('O nome do restaurante precisa ter no mínimo 2 caracteres');
            $validator->rule('lengthMax', 'restaurantName', 50)->message('O nome do restaurant precisa ter no máximo 50 caracteres');

            // restaurantCnpj
            $validator->rule('required', 'restaurantCnpj')->message('Digite o cnpj do restaurante');
            $validator->rule('lengthMin', 'restaurantCnpj', 14)->message('O cnpj precisa ter no mínimo 14 dígitos');
            $validator->rule('lengthMax', 'restaurantCnpj', 14)->message('O cnpj precisa ter no máximo 14 dígitos');
            $validator->rule('cnpj', 'restaurantCnpj')->message('Digite um cnpj válido');

            // restaurantEmail
            $validator->rule('required', 'restaurantEmail')->message('Digite o email do restaurante');
            $validator->rule('lengthMin', 'restaurantEmail', 7)->message('O email precisa ter no mínimo 7 caracteres');
            $validator->rule('lengthMax', 'restaurantEmail', 100)->message('O email precisa ter no máximo 100 caracteres');
            $validator->rule('email', 'restaurantEmail')->message('Digite um email válido');

            // restaurantPhone
            $validator->rule('required', 'restaurantPhone')->message('Digite o telefone do restaurante');
            $validator->rule('lengthMin', 'restaurantPhone', 10)->message('O telefone precisa ter no mínimo o DDD + 8 dígitos');
            $validator->rule('lengthMax', 'restaurantPhone', 10)->message('O telefone precisa ter no máximo o DDD + 8 dígitos');

            // restaurantCellPhone
            $validator->rule('required', 'restaurantCellPhone')->message('Digite o celular do restaurante');
            $validator->rule('lengthMin', 'restaurantCellPhone', 11)->message('O celular precisa ter no mínimo o DDD + 9 dígitos');
            $validator->rule('lengthMax', 'restaurantCellPhone', 11)->message('O celular precisa ter no máximo o DDD + 9 dígitos');

            // restaurantMainCategories 
            $validator->rule('required', 'restaurantMainCategories')->message('Selecione 1 ou no máx 2 categorias principais para o restaurante');
            $validator->rule('arrayLengthMax', 'restaurantMainCategories', 2)->message('Selecione no máximo 2 categorias');

            // operation
            $validator->rule('required', 'operation')->message('Informe os hórarios de funcionamento do restaurante');
            $validator->rule('operation', 'operation')->message('Informe hórarios válidos');
            $validator->rule('arrayLengthMax', 'restaurantMainCategories', 7)->message('Informe hórarios válidos');
            $validator->rule('arrayLengthMin', 'restaurantMainCategories', 7)->message('Informe hórarios válidos');

            // restaurantAddress
            $validator->rule('required', 'restaurantAddress')->message('Digite o endereço do restaurante');
            $validator->rule('lengthMin', 'restaurantAddress', 4)->message('O endereço precisa ter no mínimo 4 caracteres');
            $validator->rule('lengthMax', 'restaurantAddress', 50)->message('O endereço precisa ter no máximo 50 caracteres');

            // restaurantNeighborhood
            $validator->rule('required', 'restaurantNeighborhood')->message('Digite o bairro do restaurante');
            $validator->rule('lengthMin', 'restaurantNeighborhood', 4)->message('O bairro precisa ter no mínimo 4 caracteres');
            $validator->rule('lengthMax', 'restaurantNeighborhood', 50)->message('O bairro precisa ter no máximo 50 caracteres');

            // restaurantNumber
            $validator->rule('required', 'restaurantNumber')->message('Digite o número do restaurante');
            $validator->rule('lengthMax', 'restaurantNumber', 11)->message('O número precisa ter no máximo 11 caracteres');

            // restaurantState
            $validator->rule('required', 'restaurantState')->message('Informe o estado do restaurante');
            $validator->rule('integer', 'restaurantState')->message('Informe um estado válido');

            // restaurantCity
            $validator->rule('required', 'restaurantCity')->message('Informe a cidade do restaurante');
            $validator->rule('integer', 'restaurantCity')->message('Informe uma cidade válida');

            // restaurantComplement
            $validator->rule('lengthMax', 'restaurantComplement', 50)->message('O complemento precisa ter no máximo 50 caracteres');

            // accountaccountUserName
            $validator->rule('required', 'accountUserName')->message('Digite seu usuário');
            $validator->rule('lengthMin', 'accountUserName', 2)->message('O usuário precisa ter no mínimo 2 caracteres');
            $validator->rule('lengthMax', 'accountUserName', 30)->message('O usuário precisa ter no máximo 30 caracteres');

            // accountPassword
            $validator->rule('required', 'accountPassword')->message('Digite sua senha');
            $validator->rule('lengthMin', 'accountPassword', 4)->message('A senha precisa ter no mínimo 4 caracteres');
            $validator->rule('lengthMax', 'accountPassword', 50)->message('A senha precisa ter no máximo 50 caracteres');

            // confirmaccountPassword
            $validator->rule('required', 'accountConfirmPassword')->message('Digite novamente sua senha');
            $validator->rule('lengthMin', 'accountConfirmPassword', 4)->message('A senha precisa ter no mínimo 4 caracteres');
            $validator->rule('lengthMax', 'accountConfirmPassword', 50)->message('A senha precisa ter no máximo 50 caracteres');
            $validator->rule('equals', 'accountPassword', 'accountConfirmPassword')->message('As senhas não conferem, tente novamente');

            // terms
            $validator->rule('required', 'accountTerms')->message('Aceite os termos');

        // Customer level
        } else if ($this->data['userLevel'] === 3) {          
            // firstName
            $validator->rule('required', 'firstName')->message('Digite seu primeiro nome');
            $validator->rule('lengthMin', 'firstName', 2)->message('O seu primeiro nome precisa ter no mínimo 2 caracteres');
            $validator->rule('lengthMax', 'firstName', 30)->message('O seu primeiro nome precisa ter no máximo 30 caracteres');

            // accountLastName
            $validator->rule('required', 'accountLastName')->message('Digite seu sobrenome');
            $validator->rule('lengthMin', 'accountLastName', 4)->message('O seu sobrenome precisa ter no mínimo 4 caracteres');
            $validator->rule('lengthMax', 'accountLastName', 30)->message('O seu sobrenome precisa ter no máximo 30 caracteres');

            // accountEmail
            $validator->rule('required', 'accountEmail')->message('Digite seu email');
            $validator->rule('lengthMin', 'accountEmail', 7)->message('O email precisa ter no mínimo 7 caracteres');
            $validator->rule('lengthMax', 'accountEmail', 100)->message('O email precisa ter no máximo 30 caracteres');
            $validator->rule('email', 'accountEmail')->message('Digite um email válido');

            // cellPhone
            $validator->rule('required', 'cellPhone')->message('Digite seu celular');
            $validator->rule('lengthMin', 'cellPhone', 15)->message('O celular precisa ter no mínimo 11 caracteres');
            $validator->rule('lengthMax', 'cellPhone', 15)->message('O seu celular precisa ter no máximo 11 caracteres');
            
            // address
            $validator->rule('required', 'address')->message('Digite seu endereço');
            $validator->rule('lengthMin', 'address', 4)->message('O endereço precisa ter no mínimo 4 caracteres');
            $validator->rule('lengthMax', 'address', 50)->message('O endereço precisa ter no máximo 50 caracteres');

            // neighborhood
            $validator->rule('required', 'neighborhood')->message('Digite seu bairro');
            $validator->rule('lengthMin', 'neighborhood', 4)->message('O bairro precisa ter no mínimo 4 caracteres');
            $validator->rule('lengthMax', 'neighborhood', 50)->message('O bairro precisa ter no máximo 50 caracteres');

            // number
            $validator->rule('required', 'number')->message('Digite seu número');
            $validator->rule('lengthMax', 'number', 11)->message('O seu número precisa ter no máximo 11 caracteres');

            // state
            $validator->rule('required', 'state')->message('Informe seu estado');
            $validator->rule('integer', 'state')->message('Informe um estado válido');

            // city
            $validator->rule('required', 'city')->message('Informe sua cidade');
            $validator->rule('integer', 'city')->message('Informe uma cidade válida');

            // complement
            $validator->rule('lengthMax', 'complement', 50)->message('O complemento precisa ter no máximo 50 caracteres');

            // login
            $validator->rule('required', 'accountaccountUserName')->message('Digite seu usuário');
            $validator->rule('lengthMin', 'accountaccountUserName', 2)->message('O usuário precisa ter no mínimo 2 caracteres');
            $validator->rule('lengthMax', 'accountaccountUserName', 30)->message('O usuário precisa ter no máximo 30 caracteres');

            // accountPassword
            $validator->rule('required', 'accountaccountPassword')->message('Digite sua senha');
            $validator->rule('lengthMin', 'accountaccountPassword', 4)->message('A senha precisa ter no mínimo 4 caracteres');
            $validator->rule('lengthMax', 'accountaccountPassword', 50)->message('A senha precisa ter no máximo 50 caracteres');

            // confirmaccountPassword
            $validator->rule('required', 'accountConfirmaccountPassword')->message('Digite novamente sua senha');
            $validator->rule('lengthMin', 'accountConfirmaccountPassword', 4)->message('A senha precisa ter no mínimo 4 caracteres');
            $validator->rule('lengthMax', 'accountConfirmaccountPassword', 50)->message('A senha precisa ter no máximo 50 caracteres');
            $validator->rule('equals', 'accountaccountPassword', 'accountConfirmaccountPassword')->message('As senhas não conferem, tente novamente');

            // terms
            $validator->rule('required', 'accountTerms')->message('Aceite os termos');
        }

        if($validator->validate()) {
            return ['validate' => true];
        } else {
            // Errors
            return ['validate' => false, 'errors' => $validator->errors()];
        }
    }

    public function validateCnpj($cnpj)	{
        //Etapa 1: Cria um array com apenas os digitos numéricos, isso permite receber o cnpj em diferentes formatos como "00.000.000/0000-00", "00000000000000", "00 000 000 0000 00" etc...
        $j=0;
        for($i=0; $i<(strlen($cnpj)); $i++)
            {
                if(is_numeric($cnpj[$i]))
                    {
                        $num[$j]=$cnpj[$i];
                        $j++;
                    }
            }
        //Etapa 2: Conta os dígitos, um Cnpj válido possui 14 dígitos numéricos.
        if(count($num)!=14)
            {
                $isCnpjValid=false;
            }
        //Etapa 3: O número 00000000000 embora não seja um cnpj real resultaria um cnpj válido após o calculo dos dígitos verificares e por isso precisa ser filtradas nesta etapa.
        if ($num[0]==0 && $num[1]==0 && $num[2]==0 && $num[3]==0 && $num[4]==0 && $num[5]==0 && $num[6]==0 && $num[7]==0 && $num[8]==0 && $num[9]==0 && $num[10]==0 && $num[11]==0)
            {
                $isCnpjValid=false;
            }
        //Etapa 4: Calcula e compara o primeiro dígito verificador.
        else
            {
                $j=5;
                for($i=0; $i<4; $i++)
                    {
                        $multiplica[$i]=$num[$i]*$j;
                        $j--;
                    }
                $soma = array_sum($multiplica);
                $j=9;
                for($i=4; $i<12; $i++)
                    {
                        $multiplica[$i]=$num[$i]*$j;
                        $j--;
                    }
                $soma = array_sum($multiplica);	
                $resto = $soma%11;			
                if($resto<2)
                    {
                        $dg=0;
                    }
                else
                    {
                        $dg=11-$resto;
                    }
                if($dg!=$num[12])
                    {
                        $isCnpjValid=false;
                    } 
            }
        //Etapa 5: Calcula e compara o segundo dígito verificador.
        if(!isset($isCnpjValid))
            {
                $j=6;
                for($i=0; $i<5; $i++)
                    {
                        $multiplica[$i]=$num[$i]*$j;
                        $j--;
                    }
                $soma = array_sum($multiplica);
                $j=9;
                for($i=5; $i<13; $i++)
                    {
                        $multiplica[$i]=$num[$i]*$j;
                        $j--;
                    }
                $soma = array_sum($multiplica);	
                $resto = $soma%11;			
                if($resto<2)
                    {
                        $dg=0;
                    }
                else
                    {
                        $dg=11-$resto;
                    }
                if($dg!=$num[13])
                    {
                        $isCnpjValid=false;
                    }
                else
                    {
                        $isCnpjValid=true;
                    }
            }
        
        //Etapa 6: Retorna o Resultado em um valor booleano.
        return $isCnpjValid;			
	}

    public function validateOperation($rows) {
        $operationRows = count($rows['row']);

        for ($i=0; $i < $operationRows; $i++) { 
            $weekDay = $rows['dayIndex'][$i];
            $dayOpen1 = $rows['open1'][$i];
            $dayClose1 = $rows['close1'][$i];
            $dayOpen2 = $rows['open2'][$i];
            $dayClose2 = $rows['close2'][$i];     

            // echo '<br>';
            // print_r($weekDay);
            // echo '<br>';
            // print_r($dayOpen1);
            // echo '<br>';
            // print_r($dayOpen2);
            // echo '<br>';
            // print_r($dayClose1);
            // echo '<br>';
            // print_r($dayClose2);
            
            $validateWeekDay = $weekDay ?? false;
            $validateSchedule1 = $dayOpen1 && $dayClose1;
            $validateSchedule2 = $validateSchedule1 && ($dayOpen2 && $dayClose2 || !$dayOpen2 && !$dayClose2 );

            // Parse schedules to mins and validate range of schedules
            if ($validateSchedule1) {
                if ($dayOpen1 >= $dayClose1) {
                    $validateSchedule1 = false;
                    $validateSchedule2 = false;
                }

                if ($dayOpen1 && $dayClose2) {
                    if ($dayOpen2 > $dayClose2) {
                        $validateSchedule1 = false;
                        $validateSchedule2 = false;
                    } else if ($dayOpen2 <= $dayOpen1 || $dayOpen2 <= $dayClose1 ) {
                        $validateSchedule2 = false;
                    }

                }
            }

            $validateRow = $validateWeekDay && $validateSchedule1 && $validateSchedule2;
            
            $validation = [
                'validateWeekDay' => $validateWeekDay,
                'validateSchedule1' => $validateSchedule1,
                'validateSchedule2' => $validateSchedule2,
                'validateRow' => $validateRow
            ];
            
            // print_r($validation);

            if (!$validation['validateRow']) return false;
            
            return true;
        }
    } 
}