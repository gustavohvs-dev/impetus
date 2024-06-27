<?php

use app\utils\ImpetusJWT;
use app\utils\ImpetusUtils;
use app\utils\ImpetusFileManager;
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
            if($auth->data["permission"] != "admin" && $auth->data["permission"] != "user"){
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
					["entidade", $jsonParams->entidade, ['type(string)', 'uppercase', 'length(1024)']],
					["entidadeId", $jsonParams->entidadeId, ['type(int)', 'length(30)']],
					["nome", $jsonParams->nome, ['type(string)', 'uppercase', 'length(1000)']],
					["path", $jsonParams->path, ['type(string)', 'uppercase', 'length(5000)']],
                    ["arquivo", $jsonParams->arquivo, ['type(string)']],
                    ["tipo", $jsonParams->tipo, ['enum(ARQUIVO|CERTIFICADO/ALVARA)', 'nullable']],
                    ["vencimento", $jsonParams->vencimento, ['type(date)', 'nullable']]
                ]
            );
            if($bodyCheckFields["status"] == 0){
                $response = [
                    "code" => "400 Bad Request",
                    "response" => $bodyCheckFields
                ];
                return (object)$response;
            }

            //Enviar arquivo
            $enviarArquivo = ImpetusFileManager::saveFile($jsonParams->arquivo, $jsonParams->nome, $jsonParams->path, 'all', 10000000, true);
            if($enviarArquivo['status'] != 1){
                $response = [
                    "code" => "400 Bad Request",
                    "response" => [
                        "status" => 0,
                        "info" => $enviarArquivo['error']
                    ]
                ];
                return (object)$response;
            }

            //Organizando dados para a request
            $data = [
                "status" => "ACTIVE",
                "entidade" => $jsonParams->entidade,
                "entidadeId" => $jsonParams->entidadeId,
                "nome" => $jsonParams->nome,
                "path" => $enviarArquivo['path'],
                "usuarioId" => $jwt->payload->id,
                "tipo" => $jsonParams->tipo,
                "vencimento" => $jsonParams->vencimento
            ];

            //Criar dados
            $request = Arquivos::createArquivos($data);
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
                "tag" => "arquivos",
                "code" => $request->id,
                "endpoint" => "arquivos/create",
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
