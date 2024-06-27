<?php

use app\utils\ImpetusJWT;
use app\utils\ImpetusUtils;
use app\models\Pessoas;
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
    }

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
    }
    
    //Validar permissão de usuário
    if($auth->data["permission"] != "admin" && $auth->data["permission"] != "comercial"){
        $response = [
            "code" => "403 Forbidden",
            "response" => [
                "status" => 0,
                "info" => "Usuário não possui permissão para realizar ação"
            ]
        ];
        return (object)$response;
    }

    $urlParams = ImpetusUtils::urlParams();

    //Validação de campos
    $bodyCheckFields = ImpetusUtils::bodyCheckFields(
        [
            ["nome", $urlParams["nome"], ["type(string)"]]
        ]
    );
    if($bodyCheckFields["status"] == 0){
        $response = [
            "code" => "400 Bad Request",
            "response" => $bodyCheckFields
        ];
        return (object)$response;
    } 

    //Realizar busca
    $request = Pessoas::selectPessoas($urlParams);
    $response = [
        "code" => "200 OK",
        "response" => $request
    ];
    return (object)$response;
    
}

$response = webserviceMethod();
header("HTTP/1.1 " . $response->code);
header("Content-Type: application/json");
echo json_encode($response->response);

