<?php
namespace Controllers;

use Core\Controller;
use Mailgun\Mailgun;

class NewsletterController extends Controller {
    public function subscribe() {
        try {
            $data = [];
            $email = (!empty($_POST['email']) ? addslashes($_POST['email']) : '');
            
            $mgClient = new Mailgun($_ENV['MAILGUN_API_KEY_PRIVATE']);
            $listAddress = $_ENV['MAILGUN_LIST_ADDRESS'];

            $result = $mgClient->post("lists/$listAddress/members", array(
                'address' => $email,
                'subscribed' => 'yes'
            ));
            
            if ($result->http_response_body->message == 'Mailing list member has been created') {

                $data['message'] = 'Inscrição feita com sucesso! Fique atento as ofertas que mandaremos por email!'; // traduzir para outras langs depois
                $data['status'] = 1; // criado
            }

            echo json_encode($data);
        } catch (\Throwable $th) {
            // trata a excessao pra quando o mailgun retornar uma mensagem de erro de conta duplicada
            if ($th->getMessage() == 'The parameters passed to the API were invalid. Check your inputs! Address already exists \''.$email.'\'') {

                $data['message'] = 'Você já está cadastrado em nossa newsletter. Fique atento as ofertas que mandaremos por email!'; // traduzir para outras langs depois
                $data['status'] = 0; // ja existe

            } else {
                $data['message'] = 'Desculpe! Houve algum erro ao efetuar a sua inscrição em nossa newsletter. Tente novamente mais tarde!'; // traduzir para outras langs depois
                $data['status'] = -1; // algum erro
            }
            
            echo json_encode($data);
        }
    }
}