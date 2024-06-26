<?php

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use app\utils\ImpetusJWT;
use app\utils\ImpetusUtils;
use app\models\Pessoas;
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
            if($auth->data["permission"] != "admin" && $auth->data["permission"] != "comercial"){
                $response = [
                    "code" => "401 Unauthorized",
                    "response" => [
                        "status" => 1,
                        "info" => "Usuário não possui permissão para realizar ação"
                    ]
                ];
                return (object)$response;
            }

            //Validar ID informado
            /*$urlParams = ImpetusUtils::urlParams();
            if(!isset($urlParams["id"])){
                $response = [
                    "code" => "400 Bad Request",
                    "response" => [
                        "status" => 1,
                        "info" => "Parâmetro (id) não informado"
                    ]
                ];
                return (object)$response;
            }
            $validate = ImpetusUtils::validator("id", $urlParams["id"], ["type(int)"]);
            if($validate["status"] == 0){
                $response = [
                    "code" => "400 Bad Request",
                    "response" => $validate
                ];
                return (object)$response;
            }*/

            //Realizar busca
            $buscar = Pessoas::listPessoas([]);

            $response = [
                "code" => "200 OK",
                "response" => $buscar
            ];

            /*foreach($buscar['data'] as $row){
                echo "__________________________";
                foreach($row as $key => $value) {
                    echo $key . "-" . $value;
                }
            }

            return (object)$response;*/

            // Create new Spreadsheet object
            $spreadsheet = new Spreadsheet();

            // Set document properties
            $spreadsheet->getProperties()->setCreator('ImpetusSDK')
                        ->setTitle('Office 2007 XLSX Test Document')
                        ->setSubject('Office 2007 XLSX Test Document');

            // Add some data
            $letters = ["A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO","AP","AQ","AR","AS","AT","AU","AV","AW","AX","AY","AZ","BA","BB","BC","BD","BE","BF","BG","BH","BI","BJ","BK","BL","BM","BN","BO","BP","BQ","BR","BS","BT","BU","BV","BW","BX","BY","BZ"];
            $pos = 0;
            $rowNumber = 2;

            foreach($buscar['data'] as $row){
                foreach($row as $key => $value) {
                    //Set headers
                    if($rowNumber == 2){
                        $spreadsheet->setActiveSheetIndex(0)
                                    ->setCellValue($letters[$pos] . 1, $key);
                    }
                    //Set data
                    $spreadsheet->setActiveSheetIndex(0)
                                ->setCellValue($letters[$pos] . $rowNumber, $value);
                    $pos = $pos + 1;
                }
                $pos = 0;
	            $rowNumber = $rowNumber + 1;
            }

            // Setting auto filter
	        $spreadsheet->getActiveSheet()->setAutoFilter('A1:U' . intval($rowNumber - 1));

            // Column autosize
            foreach(range('A','U') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                            ->setAutoSize(true);
            }

            // Rename worksheet
            $spreadsheet->getActiveSheet()->setTitle('Sheet1');

            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $spreadsheet->setActiveSheetIndex(0);

            // Redirect output to a client’s web browser (Xlsx)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="01simple.xlsx"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');
            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
            exit;
            
        }
    }

}

$response = webserviceMethod();
header("HTTP/1.1 " . $response->code);
header("Content-Type: application/json");
echo json_encode($response->response);