<?php

function createUserSnippet($appName, $dbName){

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

            //Coletar bearer token
            $bearer = JWT::getBearerToken();
            $jwt = JWT::decode($bearer, "E5Z!h_Ugv+X26{832Pg9Gzefhd!IHgs&r");

            if($jwt->status == 0){
                $response = [
                    "code" => "400 Bad request",
                    "response" => [
                        "status" => 0,
                        "info" => $jwt->error,
                    ]
                ];
                return (object)$response;
            }else{
                $viagem = AppWebservice::obterViagem($jwt->payload->sub, $jwt->payload->name, $jwt->payload->empresa);
                if($viagem->status == 0){
                    $response = [
                        "code" => "400 Bad request",
                        "response" => [
                            "status" => 0,
                            "info" => $viagem->error,
                        ]
                    ];
                    return (object)$response;
                }else{

                    //Coleta dados enviados via JSON
                    $data = json_decode(file_get_contents("php://input"),false);
                    
                    if(!isset($data->lat) || !isset($data->long) || !isset($data->data)){
                        $response = [
                            "code" => "400 Bad request",
                            "response" => [
                                "status" => 0,
                                "info" => "Falha ao enviar requisição, verifique o JSON enviado",
                            ]
                        ];
                        return (object)$response;
                    }else{

                        //Enviar posição
                        $request = [
                            "id_sm" => $viagem->idViagem,
                            "id_motorista" => $jwt->payload->sub,
                            "lat_gps" => $data->lat,
                            "long_gps" => $data->long,
                            "data_posicao" => $data->data,
                            "categoria_gps" => "APP"
                        ];
                        $posicao = AppWebservice::enviarPosicao($request);
                        if($posicao->status == 0){
                            $response = [
                                "code" => "400 Bad request",
                                "response" => [
                                    "status" => 0,
                                    "info" => $posicao->info,
                                ]
                            ];
                            return (object)$response;
                        }else{
                            $response = [
                                "code" => "200 OK",
                                "response" => [
                                    "status" => 1,
                                    "info" => $posicao->info,
                                ]
                            ];
                            return (object)$response;
                        }
                    }
                    
                }
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