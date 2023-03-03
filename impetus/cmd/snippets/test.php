<?php

function testSnippet(){

$snippet = 
'<?php

//Importando models e middlewares
include_once "app/models/impetus/ImpetusJWT.php";
include_once "app/middlewares/Auth.php";
use app\models\impetus\ImpetusJWT;
use app\middlewares\Auth;

function webserviceMethod(){

    require "app/config/config.php";
    $secret = $systemConfig["api"]["token"];

    switch($_SERVER["REQUEST_METHOD"]){

        default:
    
            $response = [
                "code" => "401 Unauthorized",
                "response" => [
                    "status" => 0,
                    "code" => 401,
                    "info" => "Método não encontrado",
                ]
            ];
            return (object)$response;

        break;

        case "POST":

            //Coletar bearer token
            $bearer = ImpetusJWT::getBearerToken();
            $jwt = ImpetusJWT::decode($bearer, $secret);

            if($jwt->status == 0){
                $response = [
                    "code" => "400 Bad request",
                    "response" => [
                        "status" => 0,
                        "code" => 400,
                        "info" => $jwt->error,
                    ]
                ];
                return (object)$response;
            }else{
                $auth = Auth::validate($jwt->payload->id, $jwt->payload->username);
                if($auth->status == 0){
                    $response = [
                        "code" => "401 Unauthorized",
                        "response" => [
                            "status" => 0,
                            "code" => 401,
                            "info" => "Falha ao autenticar",
                        ]
                    ];
                    return (object)$response;
                }else{
                    $response = [
                        "code" => "200 OK",
                        "response" => [
                            "status" => 1,
                            "info" => "Autenticação JWT funcionando perfeitamente"
                        ]
                    ];
                    return (object)$response;
                }
            }
            
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