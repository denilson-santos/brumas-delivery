<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Brumas Delivery - Cadastre-se</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="../../../bower_components/bootstrap/dist/css/bootstrap.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../../../bower_components/font-awesome/css/all.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="../../../bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../../../dist/css/AdminLTE.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="../../../plugins/iCheck/square/grey.css">^
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <link rel="stylesheet" href="../css/cliente.css">

  </head>
  
  <body class="hold-transition register-page" cz-shortcut-listen="true">
    <div class="register-box">
      <div class="register-box-body">
        <div class="register-logo">
          <img width="270" src="../../../../imagens/Brumas-delivery-cinza.jpg">
        </div>
        <p class="login-box-msg">Criar Nova Conta</p>
        <p class="subtitle-parceiro"><i class="fa fa-genderless cor-icon"></i>Informações do Cliente</p>
        
        <?php
          if(!empty($_SESSION['msg'])) {
            echo $_SESSION['msg'];
            unset($_SESSION['msg']); //Destroi a variavel global criada...
          }
        ?>
        
        <!-- Formulário de Cadastro -->
        <form id="formCliente" action="formulario.php" method="post">
          <div class="row">
            <div class="form-group has-feedback col-md-7">
              <input name="nome" type="text" class="form-control" id="nome" placeholder="Nome">
              <span class="tal fa fa-user form-control-feedback"></span>
            </div>

            <div class="form-group has-feedback col-md-5">
              <input name="sobrenome" type="text" class="form-control" id="sobrenome" placeholder="Sobrenome">
              <span class="tal fa fa-signature form-control-feedback"></span>
            </div>
          </div>

          <div class="row">
            <div class="form-group has-feedback col-md-7">
              <input name="email" type="email" class="form-control" id="email" placeholder="Email">
              <span class="tal fa fa-envelope form-control-feedback"></span>
            </div>

            <div class="form-group has-feedback col-md-5">
              <input name="usuario" type="text" class="form-control" id="usuario" placeholder="Usuário">
              <span class="tal fa fa-user-circle form-control-feedback"></span>
            </div>
          </div>

          <div class="row">
            <div class="form-group has-feedback col-md-7">
              <input name="cpf" type="text" class="form-control" id="cpf" placeholder="CPF">
              <span class="tal fa fa-id-card form-control-feedback"></span>
            </div>

            <div class="form-group has-feedback col-md-5">
              <input name="nascimento" type="text" class="form-control" id="nascimento" placeholder="Nascimento">
              <span class="tal fa fa-calendar-alt form-control-feedback"></span>
            </div>
          </div>
            
          <div class="row">
            <div class="form-group has-feedback col-md-5">
              <input name="logradouro" type="text" class="form-control" id="logradouro" placeholder="Logradouro">
              <span class="tal fa fa-map-marker-alt form-control-feedback"></span>
            </div>

            <div class="form-group has-feedback col-md-4">
              <input name="bairro" type="text" class="form-control" id="bairro" placeholder="Bairro">
              <span class="tal fa fa-location-arrow form-control-feedback"></span>
            </div>

            <div class="form-group has-feedback col-md-3">
              <input name="numero" type="text" class="form-control" id="numero" placeholder="N.º">
              <span class="tal fa fa-home form-control-feedback"></span>
            </div>
          </div>

          <div class="row">
            <div class="form-group col-md-3">
              <select name="estado" class="form-control" id="estado">
                <option value="">UF</option>
                <?php
                  include_once('../../../connection/connection.php');
                  
                  $stm = $connection->query("SELECT * FROM estado ORDER BY nome");                  

                  while($row = $stm->fetch(PDO::FETCH_ASSOC)) {
                    $id = $row['id_estado'];
                    $sigla = $row['sigla'];

                    echo "<option value='$id'>$sigla</option>";
                  }
                  
                  $stm = null;
                
                ?>
              </select>
            </div>

            <div class="form-group col-md-4">
              <select name="cidade" class="form-control" id="cidade">
                <option value="">Cidade</option>
              </select>
            </div>

            <div class="form-group has-feedback col-md-5">
              <input name="complemento" type="text" class="form-control" id="complemento" placeholder="Complemento (opcional)">
              <span class=" tal fa fa-question form-control-feedback"></span>
            </div>
          </div>
          
          <div class="row">
            <div class="form-group has-feedback col-md-6">
              <input name="cep" type="text" class="form-control" id="cep" placeholder="Cep">
              <span class=" tal fa fa-map-marked-alt form-control-feedback"></span>
            </div>

            <div class="col-md-6">
              <u><a id="naoSeiCep" href="http://www.buscacep.correios.com.br/sistemas/buscacep/">Não sei meu Cep</a></u>
            </div> 
          </div>

          <div class="row">
            <div class="form-group has-feedback col-md-6">
              <input name="telefone" type="tel" class="form-control" id="telefone" placeholder="Telefone (opcional)">
              <span class="tal fa fa-phone form-control-feedback"></span>
            </div>

            <div class="form-group has-feedback col-md-6">
              <input name="celular" type="tel" class="form-control" id="celular" placeholder="Celular">
              <span class="tal fa fa-mobile-alt form-control-feedback"></span>
            </div>
          </div>

          <div class="row">
            <div class="form-group has-feedback col-md-6">
              <input name="senha" type="password" class="form-control inputs" id="senha" placeholder="Senha">
              <span class="tal fa fa-lock form-control-feedback"></span>
            </div>

            <div class="form-group has-feedback col-md-6">
              <input name="confSenha" type="password" class="form-control" id="confSenha" placeholder="Confirmar Senha">
              <span class="tal fa fa-lock form-control-feedback"></span>
            </div>
          </div>
          
          <div class="row">
            <!-- /.col -->
            <div class="form-group col-md-6">
              <button id="btCadastrar" type="submit" class="btn btn-red btn-block form-control">Cadastrar</button>
            </div>
            <!-- /.col -->

            <div class="form-group">
              <div class="col-md-6">
                <div class="checkbox icheck">
                  <label>
                    <div class="icheckbox_square-grey" aria-checked="false" aria-disabled="false" style="position: relative;">
                      <input name="termos" id="termos" value="checked" type="checkbox" style="position: absolute; top: 10%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;">
                    </div>
                    <!-- <input type="checkbox" id="termos" name="termos" value="termos" />               -->
                    <span style="margin-left:5px;"> Aceitar <a href="#"><u>Termos de Uso</u></a></span>
                  </label>
                </div>
              </div>
            </div>
          </div> 
          
          <div class="row">
            <div class="col-md-12">
              <a href="login.html"><u>Já tenho uma conta</u></a>
            </div>
          </div>
        </form>
      </div>
      <!-- /.form-box -->
    </div>
  </body>
</html>