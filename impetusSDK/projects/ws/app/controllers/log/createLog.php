<?php

use app\utils\ImpetusJWT;
use app\utils\ImpetusUtils;
use app\models\Log;
use app\models\Auth;

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
                        "status" => 0,
                        "info" => "Usuário não possui permissão para realizar ação"
                    ]
                ];
                return (object)$response;
            }

            //Coletar params do body (JSON)
            $jsonParams = json_decode(file_get_contents("php://input"),false);

            //Validação de campos
            $bodyCheckFields = ImpetusUtils::bodyCheckFields(
                [
                    ["tag", $jsonParams->tag, ['type(string)', 'uppercase', 'length(512)']],
					["endpoint", $jsonParams->endpoint, ['type(string)', 'specialChar', 'length(65535)']],
					["method", $jsonParams->method, ['type(string)', 'uppercase', 'length(512)']],
					["request", $jsonParams->request, ['type(string)', 'specialChar', 'length(65535)']],
					["response", $jsonParams->response, ['type(string)', 'specialChar', 'length(65535)']],
					["description", $jsonParams->description, ['type(string)', 'specialChar', 'length(65535)']],
					["userId", $jsonParams->userId, ['type(int)', 'length(11)']],
					
                ]
            );
            if($bodyCheckFields["status"] == 0){
                $response = [
                    "code" => "400 Bad Request",
                    "response" => $bodyCheckFields
                ];
                return (object)$response;
            }

            //Organizando dados para a request
            $data = [
                "tag" => $jsonParams->tag,
						"endpoint" => $jsonParams->endpoint,
						"method" => $jsonParams->method,
						"request" => $jsonParams->request,
						"response" => $jsonParams->response,
						"description" => $jsonParams->description,
						"userId" => $jsonParams->userId,
            ];

            //Criar dados
            $request = Log::createLog($data);
            if($request->status == 0){
                $response = [
                    "code" => "400 Bad request",
                    "response" => $request
                ];
                return (object)$response;
            }else{
                $response = [
                    "code" => "200 OK",
                    "response" => $request
                ];
                return (object)$response;
            }
        }
    }

}

$response = webserviceMethod();
header("HTTP/1.1 " . $response->code);
header("Content-Type: application/json");
echo json_encode($response->response);
