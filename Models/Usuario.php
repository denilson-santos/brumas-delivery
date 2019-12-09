<?php
namespace Models;

use Core\Model;

class Usuario extends Model {
    public function getTotalUsuarios() {
        $stm = $this->connection->prepare("SELECT COUNT(*) as quant_usuarios FROM usuario");
        $stm->execute();

        $rows = $stm->fetch();
        
        return $rows['quant_usuarios'];
    }

    public function cadastrar($nome, $email, $telefone, $senha) {      
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
}
?>