<?php
namespace App\Models;
use Valitron\Validator;

class User extends Model {
    public function register($request) {      
        $stm = $this->connection->prepare("SELECT id_usuario FROM usuario WHERE email = ?");
        $stm->execute([$email]);

        if($stm->rowCount() == 0) {
            $stm = $this->connection->prepare("INSERT INTO usuario SET nome = ?, email = ?, telefone = ?, senha = ?");
            $stm->execute([$nome, $email, $telefone, $senha]);

            return true;
        } else {
            return false;
        }
    }

    public function logar($email, $senha) {
        $stm = $this->connection->prepare("SELECT id_usuario, nome FROM usuario WHERE email = ? and senha = ?");
        $stm->execute([$email, $senha]);

        if($stm->rowCount() > 0) {
            $row = $stm->fetch();
            $_SESSION['id_usuario'] = $row["id_usuario"];
            $_SESSION['nome'] = $row["nome"];
                
            return true;
        } else {
            return false;
        }
    }

    public function validateRegister($request) {
        $validator = new Validator($request);

        // Restaurant Partner level
        if ($request['userLevel'] === 2) {

        // Customer level
        } else if ($request['userLevel'] === 3) {
            // firstName
            $validator->rule('required', 'firstName')->message('Digite seu primeiro nome');
            $validator->rule('lengthMin', 'firstName', 2)->message('O seu primeiro nome precisa ter no mínimo 2 caracteres');
            $validator->rule('lengthMax', 'firstName', 30)->message('O seu primeiro nome precisa ter no máximo 30 caracteres');

            // lastName
            $validator->rule('required', 'lastName')->message('Digite seu sobrenome');
            $validator->rule('lengthMin', 'lastName', 4)->message('O seu sobrenome precisa ter no mínimo 4 caracteres');
            $validator->rule('lengthMax', 'lastName', 30)->message('O seu sobrenome precisa ter no máximo 30 caracteres');

            // email
            $validator->rule('required', 'email')->message('Digite seu email');
            $validator->rule('lengthMin', 'email', 7)->message('O email precisa ter no mínimo 7 caracteres');
            $validator->rule('lengthMax', 'email', 100)->message('O email precisa ter no máximo 30 caracteres');
            $validator->rule('email', 'email')->message('Digite um email válido');

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
            $validator->rule('required', 'accountUserName')->message('Digite seu usuário');
            $validator->rule('lengthMin', 'accountUserName', 2)->message('O usuário precisa ter no mínimo 2 caracteres');
            $validator->rule('lengthMax', 'accountUserName', 30)->message('O usuário precisa ter no máximo 30 caracteres');

            // password
            $validator->rule('required', 'accountPassword')->message('Digite sua senha');
            $validator->rule('lengthMin', 'accountPassword', 4)->message('A senha precisa ter no mínimo 4 caracteres');
            $validator->rule('lengthMax', 'accountPassword', 50)->message('A senha precisa ter no máximo 50 caracteres');

            // confirmPassword
            $validator->rule('required', 'accountConfirmPassword')->message('Digite novamente sua senha');
            $validator->rule('lengthMin', 'accountConfirmPassword', 4)->message('A senha precisa ter no mínimo 4 caracteres');
            $validator->rule('lengthMax', 'accountConfirmPassword', 50)->message('A senha precisa ter no máximo 50 caracteres');
            $validator->rule('equals', 'accountPassword', 'accountConfirmPassword')->message('As senhas não conferem, tente novamente');

            // terms
            $validator->rule('required', 'accountTerms')->message('Aceite os termos');

            if($validator->validate()) {
                return ['validate' => true];
            } else {
                // Errors
                return ['validate' => false, 'errors' => $validator->errors()];
            }

          

            // $this->validator->add('firstName', 'required({label} must have at least {min} characters)(First Name)');

            // if ($this->validator->validate(['firstName' => 'a', 'lastName' => ''])) {

                // send notifications to stakeholders
                // save the form data to a database
            
            // } else {
            //     $messages = $this->validator->getRuleFactory();
            //     // $fieldsError = [];

            //     print_r($messages);
            //     // foreach ($messages as $field => $message) {
                    
            //     //     foreach ($message as $error) {
            //     //         $fieldsError[$field] = (string) $error;
            //     //     }
            //     // }

            //     // echo json_encode($fieldsError);
            
            // }
        }
    }
}
?>