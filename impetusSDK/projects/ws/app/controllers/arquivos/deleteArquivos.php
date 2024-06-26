<?php

use app\utils\ImpetusJWT;
use app\utils\ImpetusUtils;
use app\models\Arquivos;
use app\models\Auth;
use app\models\Log;

function webserviceMethod(){

    require "../config.php";
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
    if($auth->data["permission"] != "admin" && $auth->data["permission"] != "comercial"){
        $response = [
            "code" => "401 Unauthorized",
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
            ["id", $urlParams["id"], ["type(int)"]]
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
    $deletar = Arquivos::deleteArquivos($urlParams["id"]);
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
        "tag" => "arquivos",
        "code" => $urlParams["id"],
        "endpoint" => "arquivos/delete",
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

