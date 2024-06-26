<?php

use app\utils\ImpetusJWT;
use app\utils\ImpetusUtils;
use app\models\Pessoas;
use app\models\Auth;
use app\models\Log;
use app\models\Notificacoes;

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

            //Coletar params do body (JSON)
            $jsonParams = json_decode(file_get_contents("php://input"),false);

            //Validação de campos
            $bodyCheckFields = ImpetusUtils::bodyCheckFields(
                [
                    ["status", $jsonParams->status, ['type(string)', 'uppercase', 'length(256)']],
					["tipoDocumento", $jsonParams->tipoDocumento, ['type(string)', 'enum(CPF|CNPJ)']],
					["documento", $jsonParams->documento, ['type(string)', 'uppercase', 'length(1024)']],
					["nome", $jsonParams->nome, ['type(string)', 'uppercase', 'length(1024)']],
					["nomeFantasia", $jsonParams->nomeFantasia, ['type(string)', 'uppercase', 'length(1024)', 'nullable']],
					["enderecoLogradouro", $jsonParams->enderecoLogradouro, ['type(string)', 'uppercase', 'length(1024)', 'nullable']],
					["enderecoNumero", $jsonParams->enderecoNumero, ['type(string)', 'uppercase', 'length(1024)', 'nullable']],
					["enderecoComplemento", $jsonParams->enderecoComplemento, ['type(string)', 'uppercase', 'length(1024)', 'nullable']],
					["enderecoCidade", $jsonParams->enderecoCidade, ['type(string)', 'uppercase', 'length(1024)', 'nullable']],
					["enderecoEstado", $jsonParams->enderecoEstado, ['type(string)', 'enum(Acre|Alagoas|Amapá|Amazonas|Bahia|Ceará|Distrito Federal|Espirito Santo|Goiás|Maranhão|Mato Grosso do Sul|Mato Grosso|Minas Gerais|Pará|Paraíba|Paraná|Pernambuco|Piauí|Rio de Janeiro|Rio Grande do Norte|Rio Grande do Sul|Rondônia|Roraima|Santa Catarina|São Paulo|Sergipe|Tocantins)', 'nullable']],
					["enderecoPais", $jsonParams->enderecoPais, ['type(string)', 'uppercase', 'length(1024)', 'nullable']],
					["enderecoCep", $jsonParams->enderecoCep, ['type(string)', 'uppercase', 'length(1024)', 'nullable']],	
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
                "tipoDocumento" => $jsonParams->tipoDocumento,
                "documento" => $jsonParams->documento,
                "nome" => $jsonParams->nome,
                "nomeFantasia" => $jsonParams->nomeFantasia,
                "enderecoLogradouro" => $jsonParams->enderecoLogradouro,
                "enderecoNumero" => $jsonParams->enderecoNumero,
                "enderecoComplemento" => $jsonParams->enderecoComplemento,
                "enderecoCidade" => $jsonParams->enderecoCidade,
                "enderecoEstado" => $jsonParams->enderecoEstado,
                "enderecoPais" => $jsonParams->enderecoPais,
                "enderecoCep" => $jsonParams->enderecoCep,
            ];

            //Criar dados
            $request = Pessoas::createPessoas($data);
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

                //Registrar log
                Log::createLog([
                    "code" => $request->id,
                    "tag" => "pessoas",
                    "endpoint" => "pessoas/create",
                    "method" => "POST",
                    "request" => json_encode($jsonParams),
                    "response" => json_encode($response),
                    "description" => $request->info,
                    "userId" => $jwt->payload->id
                ]);

                //Criar notificações
                Notificacoes::createNotificacoesForAllUsers([
                    "status" => "PENDENTE",
                    "titulo" => "Nova pessoa cadastrada",
                    "mensagem" => "Novo " . $jsonParams->tipoDocumento . " cadastrado: " . $jsonParams->nome,
                    "cor" => "success",
                    "icone" => "user"
                ]);

                return (object)$response;
            }
        }
    }

}

$response = webserviceMethod();
header("HTTP/1.1 " . $response->code);
header("Content-Type: application/json");
echo json_encode($response->response);
