<?php

use app\utils\ImpetusJWT;
use app\utils\ImpetusUtils;
use app\models\Companies;
use app\models\Auth;

function webserviceMethod(){

    require "app/config/config.php";
    $secret = $systemConfig["api"]["token"];

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
            /**
             * Regra de negócio do método
             */
            
            //Validar permissão de usuário
            if($auth->data["permission"] != "admin"){
                $response = [
                    "code" => "401 Unauthorized",
                    "response" => [
                        "status" => 1,
                        "info" => "Usuário não possui permissão para realizar ação"
                    ]
                ];
                return (object)$response;
            }

            //Coletar query params
            $queryParams = ImpetusUtils::urlParams();

            //Validação de campos
            $validate = ImpetusUtils::validator("status", $queryParams['status'], ['type(string)', 'length(256)']);
            if($validate["status"] == 0){
                $response = [
                    "code" => "400 Bad Request",
                    "response" => $validate
                ];
                return (object)$response;
            }

            $validate = ImpetusUtils::validator("corporateName", $queryParams['corporateName'], ['type(string)', 'length(2048)']);
            if($validate["status"] == 0){
                $response = [
                    "code" => "400 Bad Request",
                    "response" => $validate
                ];
                return (object)$response;
            }

            //Atualiza dados
            $request = Companies::selectCompanies($queryParams);
            $response = [
                "code" => "200 OK",
                "response" => $request
            ];
            return (object)$response;
        }
    }

}

$response = webserviceMethod();
header("HTTP/1.1 " . $response->code);
header("Content-Type: application/json");
echo json_encode($response->response);