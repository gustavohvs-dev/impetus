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
                    $validate = ImpetusUtils::validator("companyId", $jsonParams->companyId, ['type(int)']);
                    if($validate["status"] == 0){
                        $response = [
                            "code" => "400 Bad Request",
                            "response" => $validate
                        ];
                        return (object)$response;
                    }
					

            //Organizando dados para a request
            $data = [
                "status" => $jsonParams->status,
                "name" => $jsonParams->name,
                "email" => $jsonParams->email,
                "username" => $jsonParams->username,
                "password" => password_hash($jsonParams->password, PASSWORD_BCRYPT),
                "companyId" => $jsonParams->companyId,
                "permission" => $jsonParams->permission,
            ];

            //Criar dados
            $request = Users::createUsers($data);
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
                
            }

            //Registrar log
            Log::createLog([
                "tag" => "users",
                "code" => $request->id,
                "endpoint" => "users/create",
                "method" => "POST",
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
