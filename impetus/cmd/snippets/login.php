<?php

function loginSnippet($appName, $dbName){

$snippet = 
'<?php

//Importando models e middlewares
include_once "app/models/impetus/ImpetusJWT.php";
include_once "app/middlewares/Auth.php";
use app\models\impetus\ImpetusJWT;
use app\middlewares\Auth;

function webserviceMethod(){

    switch($_SERVER["REQUEST_METHOD"]){

        case "POST":
    
            //Coleta dados enviados via JSON
            $data = json_decode(file_get_contents("php://input"),false);
    
            //Autentica usuário
            $responseLogin = AppWebservice::login($data->username, $data->password);
    
            if($responseLogin->status == 0){
                $response = [
                    "code" => "401 Unauthorized",
                    "response" => $responseLogin
                ];
                return (object)$response;
            }else{
                $jwt = JWT::encode($responseLogin->data["id_motorista"], $responseLogin->data["nome_motorista"], ["cpf" => $responseLogin->data["cpf_motorista"], "empresa" => $responseLogin->data["empresa_motorista"]] , 24, "E8Y!h_Ugv+X26{271Pg9Gzeqv!GWwo&s");
                $response = [
                    "code" => "200 OK",
                    "response" => [
                        "status" => $responseLogin->status,
                        "motorista" => [
                            "nome" => $responseLogin->data["nome_motorista"],
                            "empresa" => $responseLogin->data["empresa_motorista"],
                        ],
                        "token" => $jwt,
                    ],
                    
                ];
                return (object)$response;
            }
            
        break;
    
        default:
    
            $response = [
                "code" => "401 Unauthorized",
                "response" => [
                    "status" => 0,
                    "info" => "Método não encontrado",
                ]
            ];
            return (object)$response;
    
        break;
    
    }

}

$response = webserviceMethod();
header("HTTP/1.1 " . $response->code);
header("Content-Type: application/json");
echo json_encode($response->response);
';

return $snippet;

}