<?php

use app\utils\ImpetusJWT;
use app\utils\ImpetusUtils;
use app\models\Users;
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
					$validate = ImpetusUtils::validator("name", $jsonParams->name, ['type(string)', 'uppercase', 'length(1024)']);
                    if($validate["status"] == 0){
                        $response = [
                            "code" => "400 Bad Request",
                            "response" => $validate
                        ];
                        return (object)$response;
                    }
					$validate = ImpetusUtils::validator("email", $jsonParams->email, ['type(string)', 'uppercase', 'length(1024)']);
                    if($validate["status"] == 0){
                        $response = [
                            "code" => "400 Bad Request",
                            "response" => $validate
                        ];
                        return (object)$response;
                    }
					$validate = ImpetusUtils::validator("username", $jsonParams->username, ['type(string)', 'uppercase', 'length(256)']);
                    if($validate["status"] == 0){
                        $response = [
                            "code" => "400 Bad Request",
                            "response" => $validate
                        ];
                        return (object)$response;
                    }
					$validate = ImpetusUtils::validator("password", $jsonParams->password, ['type(string)', 'uppercase', 'length(256)']);
                    if($validate["status"] == 0){
                        $response = [
                            "code" => "400 Bad Request",
                            "response" => $validate
                        ];
                        return (object)$response;
                    }
					$validate = ImpetusUtils::validator("permission", $jsonParams->permission, ['type(string)', 'enum(admin|user)']);
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
                "name" => $jsonParams->name,
                "email" => $jsonParams->email,
                "username" => $jsonParams->username,
                "password" => password_hash($jsonParams->password, PASSWORD_BCRYPT),
                "permission" => $jsonParams->permission,
                "companyId" => $jsonParams->companyId,
                "updatedAt" => $datetime
            ];

            //Atualiza dados
            $request = Users::updateUsers($data);
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
                "tag" => "users",
                "code" => $jsonParams->id,
                "endpoint" => "users/update",
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
