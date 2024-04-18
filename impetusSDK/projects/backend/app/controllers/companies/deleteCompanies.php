<?php

use app\utils\ImpetusJWT;
use app\utils\ImpetusUtils;
use app\models\Companies;
use app\models\Auth;
use app\models\Log;

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

    //Validar ID informado
    $urlParams = ImpetusUtils::urlParams();
    if(!isset($urlParams["id"])){
        $response = [
            "code" => "400 Bad Request",
            "response" => [
                "status" => 1,
                "info" => "Parâmetro (id) não informado"
            ]
        ];
        return (object)$response;
    }

    $validate = ImpetusUtils::validator("id", $urlParams["id"], ["type(int)"]);
    if($validate["status"] == 0){
        $response = [
            "code" => "400 Bad Request",
            "response" => $validate
        ];
        return (object)$response;
    }

    //Realizar busca
    $deletar = Companies::deleteCompanies($urlParams["id"]);
    if($deletar->status == 0){
        $response = [
            "code" => "400 Bad request",
            "response" => $deletar
        ];
    }else{
        $response = [
            "code" => "200 OK",
            "response" => $deletar
        ];
    }

    //Registrar log
    Log::createLog([
        "tag" => "companies",
        "endpoint" => "companies/delete",
        "method" => "DELETE",
        "request" => json_encode($urlParams),
        "response" => json_encode($response),
        "description" => $deletar->info,
        "userId" => $jwt->payload->id
    ]);

    return (object)$response;
    
}

$response = webserviceMethod();
header("HTTP/1.1 " . $response->code);
header("Content-Type: application/json");
echo json_encode($response->response);

