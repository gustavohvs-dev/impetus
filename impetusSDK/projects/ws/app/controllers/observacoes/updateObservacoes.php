<?php

use app\utils\ImpetusJWT;
use app\utils\ImpetusUtils;
use app\models\Observacoes;
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
                    ["id", $jsonParams->id, ["type(int)"]],
                    ["status", $jsonParams->status, ['type(string)', 'uppercase', 'length(256)']],
					["entidade", $jsonParams->entidade, ['type(string)', 'uppercase', 'length(1024)', 'nullable']],
					["entidadeId", $jsonParams->entidadeId, ['type(int)', 'length(30)', 'nullable']],
					["texto", $jsonParams->texto, ['type(string)', 'specialChar', 'length(65535)', 'nullable']],
					["usuarioId", $jsonParams->usuarioId, ['type(int)', 'length(30)']],
					
                ]
            );
            if($bodyCheckFields["status"] == 0){
                $response = [
                    "code" => "400 Bad Request",
                    "response" => $bodyCheckFields
                ];
                return (object)$response;
            }

            //Coleta data/hora atual
            $datetime = ImpetusUtils::datetime();

            //Organizando dados para a request
            $data = [
                "id" => $jsonParams->id,
                "status" => $jsonParams->status,
						"entidade" => $jsonParams->entidade,
						"entidadeId" => $jsonParams->entidadeId,
						"texto" => $jsonParams->texto,
						"usuarioId" => $jsonParams->usuarioId,
                "updatedAt" => $datetime
            ];

            //Atualiza dados
            $request = Observacoes::updateObservacoes($data);
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
                "tag" => "observacoes",
                "code" => $jsonParams->id,
                "endpoint" => "observacoes/update",
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
