<?php

use app\utils\ImpetusJWT;
use app\utils\ImpetusUtils;
use app\models\Pessoas;
use app\models\Auth;
use Dompdf\Dompdf;
use Dompdf\Options;

function webserviceMethod()
{

    require "../config.php";
    $secret = $systemConfig["api"]["token"];

    //Coletar bearer token
    $bearer = ImpetusJWT::getBearerToken();
    $jwt = ImpetusJWT::decode($bearer, $secret);

    if ($jwt->status == 0) {
        $response = [
            "code" => "400 Bad request",
            "response" => [
                "status" => 0,
                "code" => 400,
                "info" => $jwt->error,
            ]
        ];
        return (object)$response;
    } else {
        $auth = Auth::validate($jwt->payload->id, $jwt->payload->username);
        if ($auth->status == 0) {
            $response = [
                "code" => "401 Unauthorized",
                "response" => [
                    "status" => 0,
                    "code" => 401,
                    "info" => "Falha ao autenticar",
                ]
            ];
            return (object)$response;
        } else {
            /**
             * Regra de negócio do método
             */

            //Validar permissão de usuário
            if ($auth->data["permission"] != "admin") {
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
            if ($bodyCheckFields["status"] == 0) {
                $response = [
                    "code" => "400 Bad Request",
                    "response" => $bodyCheckFields
                ];
                return (object)$response;
            }

            //Realizar busca
            $buscar = Pessoas::getPessoas($urlParams["id"]);
            if ($buscar->status == 0) {
                $response = [
                    "code" => "404 Not found",
                    "response" => $buscar
                ];
                return (object)$response;
            } else {

                $response = [
                    "code" => "200 OK",
                ];

                //Print
                // instantiate and use the dompdf class

                $options = new Options();
                $options->setChroot(__DIR__);
                $options->setIsRemoteEnabled(true);

                $dompdf = new Dompdf($options);

                $dompdf->loadHtml('
                <html>
                    <head>
                        <style>
                            @page {
                                margin: 0cm 0cm;
                            }

                            body {
                                font-family: "Helvetica";
                                margin-top:    1cm;
                                margin-bottom: 1cm;
                                margin-left:   1cm;
                                margin-right:  1cm;
                            }
                            
                        </style>
                    </head>
                    <body>
                        <div style="text-align:center;">
                            <img src="'.$systemConfig["rootPath"].'app/public/assets/loginLogo.png" style="width: 40%;">	
                        </div>
                    
                        <h2>Exemplo de PDF</h2>

                        <span>'.$buscar->data['nome'].'</span>
                        
                        <br>

                    </body>
                 </html>
                ');

                // (Optional) Setup the paper size and orientation
                $dompdf->setPaper('A4', 'portrait');
                // Render the HTML as PDF
                $dompdf->render();
                // Output the generated PDF to Browser
                $dompdf->stream();
            }
        }
    }
}

$response = webserviceMethod();
header("HTTP/1.1 " . $response->code);
header("Content-Type: application/json");
echo json_encode($response->response);
