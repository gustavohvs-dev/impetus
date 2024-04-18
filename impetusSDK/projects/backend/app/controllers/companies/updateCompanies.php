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

            //Coletar params do body (JSON)
            $jsonParams = json_decode(file_get_contents("php://input"),false);

            //Validação de campos
            $validate = ImpetusUtils::validator("id", $jsonParams->id, ["type(int)"]);
            if($validate["status"] == 0){
                $response = [
                    "code" => "400 Bad Request",
                    "response" => $validate
                ];
                return (object)$response;
            }
            $validate = ImpetusUtils::validator("status", $jsonParams->status, ['type(string)', 'uppercase', 'length(256)']);
                    if($validate["status"] == 0){
                        $response = [
                            "code" => "400 Bad Request",
                            "response" => $validate
                        ];
                        return (object)$response;
                    }
					$validate = ImpetusUtils::validator("corporateName", $jsonParams->corporateName, ['type(string)', 'uppercase', 'length(2048)']);
                    if($validate["status"] == 0){
                        $response = [
                            "code" => "400 Bad Request",
                            "response" => $validate
                        ];
                        return (object)$response;
                    }
					$validate = ImpetusUtils::validator("name", $jsonParams->name, ['type(string)', 'uppercase', 'length(2048)']);
                    if($validate["status"] == 0){
                        $response = [
                            "code" => "400 Bad Request",
                            "response" => $validate
                        ];
                        return (object)$response;
                    }
					$validate = ImpetusUtils::validator("document", $jsonParams->document, ['type(string)', 'uppercase', 'length(256)']);
                    if($validate["status"] == 0){
                        $response = [
                            "code" => "400 Bad Request",
                            "response" => $validate
                        ];
                        return (object)$response;
                    }
					

            //Coleta data/hora atual
            $datetime = ImpetusUtils::datetime();

            //Organizando dados para a request
            $data = [
                "id" => $jsonParams->id,
                "status" => $jsonParams->status,
                "corporateName" => $jsonParams->corporateName,
                "name" => $jsonParams->name,
                "document" => $jsonParams->document,
                "updatedAt" => $datetime
            ];

            //Atualiza dados
            $request = Companies::updateCompanies($data);
            if($request->status == 0){
                $response = [
                    "code" => "400 Bad request",
                    "response" => $request
                ];
            }else{
                $response = [
                    "code" => "200 OK",
                    "response" => $request
                ];  
            }

            //Registrar log
            Log::createLog([
                "tag" => "companies",
                "endpoint" => "companies/update",
                "method" => "PUT",
                "request" => json_encode($jsonParams),
                "response" => json_encode($response),
                "description" => $request->info,
                "userId" => $jwt->payload->id
            ]);

            return (object)$response;
        }
    }

}

$response = webserviceMethod();
header("HTTP/1.1 " . $response->code);
header("Content-Type: application/json");
echo json_encode($response->response);
