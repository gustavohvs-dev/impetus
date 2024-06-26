<?php

function api($name)
{
    require "build/config.php";
    echo "\nCriando API ({$name})";

    $functionName = ucfirst(strtolower($name));

    if(is_file("build/ws/app/api/$functionName.php")){
        echo "\nArquivo 'build/ws/app/api/$functionName' já existente.";
        echo "\033[1;31m"."\nOperação cancelada\n"."\033[0m";
        return;
    }

    $snippet = "";

/**
 * Criando api
 */

$snippet.= '<?php

/**
 * '.$functionName.' API
 */

namespace app\api;

class '.$functionName.'
{
    private $username = "admin";
    private $password = "password-example";
    private $endpoint = "https://www.api.com/";

    private function token() : array
    {
        $url = $this->endpoint . "login";
        $body = [
            "username" => $this->username,
            "password" => $this->password
        ];
        $bodyEncoded = json_encode($body);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $bodyEncoded,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
            ],
        ]);
        $results = curl_exec($curl);
        $err = curl_error($curl);
        $info = curl_getinfo($curl);
        curl_close($curl);
        $results = json_decode($results);

        if($info[\'http_code\'] != 200){
            $response = [
                "status" => 0,
                "info" => "Falha ao autenticar",
                "error" => $err
            ];
        }else{
            $response = [
                "status" => 1,
                "info" => "Autenticado com sucesso",
                "token" => $results->token,
            ];
        }

        return $response;
    }

    public function getExample($id){
        $token = $this->token();

        $url = $this->endpoint . "getExample?cpf=" . $id;

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer " . $token[\'token\']
                ],
        ]);

        $results = curl_exec($curl);
        $err = curl_error($curl);
        $info = curl_getinfo($curl);
        curl_close($curl);
        $results = json_decode($results);

        if($info[\'http_code\'] != 200){
            $response = [
                "status" => 0,
                "info" => "Houve uma falha na comunicação com a API",
                "error" => $err
            ];
        }else{
            $response = [
                "status" => 1,
                "info" => "Sucesso ao buscar dados de motorista",
                "data" => $results,
            ];
        }

        return $response;

    }
}
';

    $arquivo = fopen("build/ws/app/api/$functionName.php", 'w');
    if($arquivo == false){
        return "\033[1;31m"."\nFalha ao criar API de exemplo (".$functionName.")\n" . "\033[0m";
    }else{
        $escrever = fwrite($arquivo, $snippet);
        if($escrever == false){
            return "\033[1;31m"."\nFalha ao preencher API de exemplo (".$functionName.")\n" . "\033[0m";
        }else{
            echo "\033[1;32m"."\nAPI de exemplo '".$name."' criada com sucesso.\n" . "\033[0m";
        }
    } 

}