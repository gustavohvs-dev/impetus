<?php

use app\utils\ImpetusJWT;
use app\utils\ImpetusUtils;
use app\models\Notificacoes;
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
            
            //Validar permissão de usuário
            if($auth->data["permission"] != "admin"){
                $response = [
                    "code" => "403 Forbidden",
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
                    ["status", $jsonParams->status, ['type(string)', 'enum(PENDENTE|LIDA)']],
					["titulo", $jsonParams->titulo, ['type(string)', 'uppercase', 'length(512)']],
					["mensagem", $jsonParams->mensagem, ['type(string)', 'uppercase', 'length(1024)']],
					["cor", $jsonParams->cor, ['type(string)', 'uppercase', 'length(512)', 'nullable']],
					["icone", $jsonParams->icone, ['type(string)', 'uppercase', 'length(512)', 'nullable']],
					["userId", $jsonParams->userId, ['type(int)', 'length(30)']],
					
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
                "status" => $jsonParams->status,
						"titulo" => $jsonParams->titulo,
						"mensagem" => $jsonParams->mensagem,
						"cor" => $jsonParams->cor,
						"icone" => $jsonParams->icone,
						"userId" => $jsonParams->userId,
            ];
            $data = ImpetusUtils::sanitizeArray($data);

            //Criar dados
            $request = Notificacoes::createNotificacoes($data);
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
                "tag" => "notificacoes",
                "code" => $request->id,
                "endpoint" => "notificacoes/create",
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
