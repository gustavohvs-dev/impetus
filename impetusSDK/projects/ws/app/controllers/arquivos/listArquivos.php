<?php

use app\utils\ImpetusJWT;
use app\utils\ImpetusUtils;
use app\models\Arquivos;
use app\models\Auth;

function webserviceMethod(){

    require "../config.php";
    $secret = $systemConfig["api"]["token"];

    
    //Coletar bearer token
    $bearer = ImpetusJWT::getBearerToken();
    $jwt = ImpetusJWT::decode($bearer, $secret);

    if($jwt->status == 0){
        $response = [
            "code" => "401 Unauthorized",
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
            $urlParams = ImpetusUtils::urlParams();
            $buscar = Arquivos::listArquivos($urlParams);
            $response = [
                "code" => "200 OK",
                "response" => $buscar
            ];
            return (object)$response;
        }
    }

}

$response = webserviceMethod();
header("HTTP/1.1 " . $response->code);
header("Content-Type: application/json");
echo json_encode($response->response);
