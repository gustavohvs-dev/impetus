<?php

use Impetus\Framework\ImpetusJWT;
use Impetus\App\Models\Auth;

function wsmethod()
{

    require "app/config/config.php";
    $secret = $systemConfig["api"]["token"];

    //Coleta dados enviados via JSON
    $data = json_decode(file_get_contents("php://input"), false);

    //Autentica usuário
    $responseLogin = Auth::login($data->username, $data->password);

    if ($responseLogin->status == 0) {
        $response = [
            "code" => "401 Unauthorized",
            "response" => $responseLogin
        ];
        return (object) $response;
    } else {
        $jwt = ImpetusJWT::encode($responseLogin->data["id"], $responseLogin->data["username"], ["id" => $responseLogin->data["id"], "username" => $responseLogin->data["username"]], 24, $secret);
        $response = [
            "code" => "200 OK",
            "response" => [
                "status" => $responseLogin->status,
                "code" => $responseLogin->code,
                "token" => $jwt,
            ],

        ];
        return (object) $response;
    }


}

$response = wsmethod();
header("HTTP/1.1 " . $response->code);
header("Content-Type: application/json");
echo json_encode($response->response);