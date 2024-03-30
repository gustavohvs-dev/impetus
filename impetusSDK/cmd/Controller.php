<?php

function controller($tableName)
{
    require "build/backend/app/config/config.php";
    echo "\nCriando controllers ({$tableName})";

    //Busca tabela
    $query = "DESC $tableName";
    $stmt = $conn->prepare($query);
    if($stmt->execute())
    {
        $table = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "\nTabela encontrada...";

        $functionName = ucfirst(strtolower($tableName));

        $pointerCreate = 0;
        $columnNameCreate = [];
        $typeCreate = [];
        $createParams = "";
        $createTabs = "";
        $rules = ""; 
        $rulesTab = "\n\t\t\t\t\t";
        
        $documentation = [];

        foreach($table as $column)
        {
            if($column['Key'] == "PRI"){
                $primaryKey = $column['Field'];
            }

            if($column['Field']<>"id" && $column['Field']<>"createdAt"){
                $columnNameCreate[$pointerCreate] = $column["Field"];

                $columnType = explode("(" , $column["Type"]);
                $columnType = $columnType[0];
                if($columnType == "int"){
                    $typeCreate[$pointerCreate] = "PDO::PARAM_INT";
                }else{
                    $typeCreate[$pointerCreate] = "PDO::PARAM_STR";
                }

                $pointerCreate++;
            }

            if($column['Field']<>"id" && $column['Field']<>"createdAt" && $column['Field']<>"updatedAt"){

                $createParams .= $createTabs . '"'.$column['Field'].'" => $jsonParams->'.$column['Field'].',';
                $createTabs = "\n\t\t\t\t\t\t";

                //Criando regras de validação
                $columnType = $column["Type"];

                if($columnType == 'date' || $columnType == 'datetime'){
                    $type = $columnType;
                    $typeArgs = null;
                }else{
                    $columnType = explode("(", $column["Type"]);
                    $type = $columnType[0];
                    $columnType = explode(")", $columnType[1]);
                    $typeArgs = $columnType[0];
                }

                if($type == "int" || $type == "tinyint" || $type == "smallint" || $type == "mediumint" || $type == "bigint"){
                    $ruleArgs = "'type(int)'";
                    $ruleArgs .= ", 'length(".$typeArgs.")'";
                    array_push($documentation, [$column['Field'], $type, 23]);
                }elseif($type == "float" || $type == "decimal" || $type == "double" || $type == "real" || $type == "bit" || $type == "serial"){
                    $ruleArgs = "'type(number)'";
                    $ruleArgs .= ", 'length(".$typeArgs.")'";
                    array_push($documentation, [$column['Field'], $type, 18.2]);
                }elseif($type == "boolean"){
                    $ruleArgs = "'type(boolean)'";
                    array_push($documentation, [$column['Field'], $type, true]);
                }elseif($type == "date"){
                    $ruleArgs = "'type(date)'";
                    array_push($documentation, [$column['Field'], $type, "2024-01-01"]);
                }elseif($type == "datetime"){
                    $ruleArgs = "'type(datetime)'";
                    array_push($documentation, [$column['Field'], $type, "2024-01-01 21:00:00"]);
                }elseif($type == "tinytext" || $type == "text" || $type == "mediumtext" || $type == "longtext"){
                    $ruleArgs = "'type(string)', 'specialChar'";
                    $ruleArgs .= ", 'length(".$typeArgs.")'";
                    array_push($documentation, [$column['Field'], $type, "lorem ipsum dolor sit amet consectetur adipiscing elit"]);
                }elseif($type == "char" || $type == "varchar"){
                    $ruleArgs = "'type(string)', 'uppercase'";
                    $ruleArgs .= ", 'length(".$typeArgs.")'";
                    array_push($documentation, [$column['Field'], $type, "some string data"]);
                }elseif($type == "enum"){
                    $ruleArgs = "'type(string)'";
                    $typeArgs = str_replace("'", "", $typeArgs);
                    $typeArgs = str_replace(",", "|", $typeArgs);
                    $ruleArgs .= ", 'enum(".$typeArgs.")'";
                    $tempDocumentationEnumExample = explode("|", $typeArgs);
                    array_push($documentation, [$column['Field'], $type, $tempDocumentationEnumExample[0], $typeArgs]);
                }else{
                    $ruleArgs = "type(string)";
                    array_push($documentation, [$column['Field'], $type, "some string data"]);
                }

                if($column["Null"]=="YES"){
                    $ruleArgs .= ", 'nullable'";
                }
                
                $rules .= '["'.$column['Field'].'", $jsonParams->'.$column['Field'].', ['.$ruleArgs.']],'.$rulesTab;
            }

        }

        $queryCreateColumns = "";
        $queryCreateBindsTags = "";
        $queryCreateBindsParams = "";
        $queryUpdateColumns = "";
        $queryUpdateBindsParams = "";
        $comma = "";

        for($i = 0; $i < $pointerCreate; $i++)
        {
            if($columnNameCreate[$i] <> "updatedAt"){
                $queryCreateColumns .= $comma . $columnNameCreate[$i];
                $queryCreateBindsTags .= $comma . ":" . strtoupper($columnNameCreate[$i]);
                $queryCreateBindsParams .= '$stmt->bindParam(":'.strtoupper($columnNameCreate[$i]).'", $data["'.$columnNameCreate[$i].'"], '.$typeCreate[$i].');' . "\n\t\t";

                $queryUpdateColumns .= $comma . $columnNameCreate[$i] . " = :" . strtoupper($columnNameCreate[$i]);
                $queryUpdateBindsParams .= '$stmt->bindParam(":'.strtoupper($columnNameCreate[$i]).'", $data["'.$columnNameCreate[$i].'"], '.$typeCreate[$i].');' . "\n\t\t";
                $comma = ", ";
            }else{
                $queryUpdateColumns .= $comma . $columnNameCreate[$i] . " = :" . strtoupper($columnNameCreate[$i]);
                $queryUpdateBindsParams .= '$stmt->bindParam(":'.strtoupper($columnNameCreate[$i]).'", $data["'.$columnNameCreate[$i].'"], '.$typeCreate[$i].');' . "\n\t\t";
                $comma = ", ";
            }
        }

        /**
         * Criar pasta do controller
         */
        if(!is_dir("build/backend/app/controllers/$tableName")){
            mkdir("build/backend/app/controllers/$tableName", 0751);
            echo "\nPasta 'build/backend/app/controllers/$tableName' criada.";
        }else{
            echo "\nPasta 'build/backend/app/controllers/$tableName' já existente.";
            echo "\033[1;31m"."\nOperação cancelada"."\033[0m";
            return;
        }

        /**
         * Controller - GET
         */

$snippet= '<?php

use app\utils\ImpetusJWT;
use app\utils\ImpetusUtils;
use app\models\\'.$functionName.';
use app\models\Auth;

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
            if($bodyCheckFields["status"] == 0){
                $response = [
                    "code" => "400 Bad Request",
                    "response" => $bodyCheckFields
                ];
                return (object)$response;
            }

            //Realizar busca
            $buscar = '.$functionName.'::get'.$functionName.'($urlParams["id"]);
            if($buscar->status == 0){
                $response = [
                    "code" => "404 Not found",
                    "response" => $buscar
                ];
                return (object)$response;
            }else{
                $response = [
                    "code" => "200 OK",
                    "response" => $buscar
                ];
                return (object)$response;
            }

            
        }
    }

}

$response = webserviceMethod();
header("HTTP/1.1 " . $response->code);
header("Content-Type: application/json");
echo json_encode($response->response);

';

    $arquivo = fopen("build/backend/app/controllers/$tableName/get$functionName.php", 'w');
    if($arquivo == false){
        echo "\033[1;31m"."\n(500 Internal Server Error) Falha ao criar controller (get".$functionName.")". "\033[0m";
        return false;
    }else{
        $escrever = fwrite($arquivo, $snippet);
        if($escrever == false){
            echo "\033[1;31m"."\n(500 Internal Server Error) Falha ao preencher controller (get".$functionName.")". "\033[0m";
            return false;
        }else{
            echo "\033[1;32m"."\n(200 OK) Controller get".$functionName." criado com sucesso.". "\033[0m";
        }
    } 

    /**
     * FIM - Controller GET
     */

    /**
     * Controller - LIST
     */

$snippet= '<?php

use app\utils\ImpetusJWT;
use app\utils\ImpetusUtils;
use app\models\\'.$functionName.';
use app\models\Auth;

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
            $urlParams = ImpetusUtils::urlParams();
            $buscar = '.$functionName.'::list'.$functionName.'($urlParams);
            $response = [
                "code" => "200 OK",
                "response" => $buscar
            ];
            return (object)$response;
        }
    }

}

$response = webserviceMethod();
header("HTTP/1.1 " . $response->code);
header("Content-Type: application/json");
echo json_encode($response->response);
';

    $arquivo = fopen("build/backend/app/controllers/$tableName/list$functionName.php", 'w');
    if($arquivo == false){
        echo "\033[1;31m"."\n(500 Internal Server Error) Falha ao criar controller (list".$functionName.")". "\033[0m";
        return false;
    }else{
        $escrever = fwrite($arquivo, $snippet);
        if($escrever == false){
            echo "\033[1;31m"."\n(500 Internal Server Error) Falha ao preencher controller (list".$functionName.")". "\033[0m";
            return false;
        }else{
            echo "\033[1;32m"."\n(200 OK) Controller list".$functionName." criado com sucesso.". "\033[0m";
        }
    } 

    /**
     * FIM - Controller LIST
     */

    /**
     * Controller - INSERT
     */

$snippet= '<?php

use app\utils\ImpetusJWT;
use app\utils\ImpetusUtils;
use app\models\\'.$functionName.';
use app\models\Auth;

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
                    '.$rules.'
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
                '.$createParams.'
            ];

            //Criar dados
            $request = '.$functionName.'::create'.$functionName.'($data);
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
                return (object)$response;
            }
        }
    }

}

$response = webserviceMethod();
header("HTTP/1.1 " . $response->code);
header("Content-Type: application/json");
echo json_encode($response->response);
';

    $arquivo = fopen("build/backend/app/controllers/$tableName/create$functionName.php", 'w');
    if($arquivo == false){
        echo "\033[1;31m"."\n(500 Internal Server Error) Falha ao criar controller (create".$functionName.")". "\033[0m";
        return false;
    }else{
        $escrever = fwrite($arquivo, $snippet);
        if($escrever == false){
            echo "\033[1;31m"."\n(500 Internal Server Error) Falha ao preencher controller (create".$functionName.")". "\033[0m";
            return false;
        }else{
            echo "\033[1;32m"."\n(200 OK) Controller create".$functionName." criado com sucesso.". "\033[0m";
        }
    } 

    /**
     * FIM - Controller INSERT
     */

    /**
     * Controller - UPDATE
     */

$snippet= '<?php

use app\utils\ImpetusJWT;
use app\utils\ImpetusUtils;
use app\models\\'.$functionName.';
use app\models\Auth;

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
                    ["'.$primaryKey.'", $jsonParams->'.$primaryKey.', ["type(int)"]],
                    '.$rules.'
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
                "'.$primaryKey.'" => $jsonParams->'.$primaryKey.',
                '.$createParams.'
                "updatedAt" => $datetime
            ];

            //Atualiza dados
            $request = '.$functionName.'::update'.$functionName.'($data);
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
                return (object)$response;
            }
        }
    }

}

$response = webserviceMethod();
header("HTTP/1.1 " . $response->code);
header("Content-Type: application/json");
echo json_encode($response->response);
';

    $arquivo = fopen("build/backend/app/controllers/$tableName/update$functionName.php", 'w');
    if($arquivo == false){
        echo "\033[1;31m"."\n(500 Internal Server Error) Falha ao criar controller (update".$functionName.")". "\033[0m";
        return false;
    }else{
        $escrever = fwrite($arquivo, $snippet);
        if($escrever == false){
            echo "\033[1;31m"."\n(500 Internal Server Error) Falha ao preencher controller (update".$functionName.")". "\033[0m";
            return false;
        }else{
            echo "\033[1;32m"."\n(200 OK) Controller update".$functionName." criado com sucesso.". "\033[0m";
        }
    } 

    /**
     * FIM - Controller UPDATE
     */

    /**
     * Controller - DELETE
     */

$snippet= '<?php

use app\utils\ImpetusJWT;
use app\utils\ImpetusUtils;
use app\models\\'.$functionName.';
use app\models\Auth;

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
    }

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
    }
    
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

    $urlParams = ImpetusUtils::urlParams();

    //Validação de campos
    $bodyCheckFields = ImpetusUtils::bodyCheckFields(
        [
            ["id", $urlParams["id"], ["type(int)"]]
        ]
    );
    if($bodyCheckFields["status"] == 0){
        $response = [
            "code" => "400 Bad Request",
            "response" => $bodyCheckFields
        ];
        return (object)$response;
    } 

    //Realizar busca
    $deletar = '.$functionName.'::delete'.$functionName.'($urlParams["id"]);
    if($deletar->status == 0){
        $response = [
            "code" => "400 Bad request",
            "response" => $deletar
        ];
        return (object)$response;
    }else{
        $response = [
            "code" => "200 OK",
            "response" => $deletar
        ];
        return (object)$response;
    }
    
}

$response = webserviceMethod();
header("HTTP/1.1 " . $response->code);
header("Content-Type: application/json");
echo json_encode($response->response);

';

    $arquivo = fopen("build/backend/app/controllers/$tableName/delete$functionName.php", 'w');
    if($arquivo == false){
        echo "\033[1;31m"."\n(500 Internal Server Error) Falha ao criar controller (delete".$functionName.")". "\033[0m";
        return false;
    }else{
        $escrever = fwrite($arquivo, $snippet);
        if($escrever == false){
            echo "\033[1;31m"."\n(500 Internal Server Error) Falha ao preencher controller (delete".$functionName.")". "\033[0m";
            return false;
        }else{
            echo "\033[1;32m"."\n(200 OK) Controller delete".$functionName." criado com sucesso.". "\033[0m";
        }
    } 

    /**
     * FIM - Controller DELETE
     */

     /**
     * Controller - SELECT
     */

$snippet= '<?php

use app\utils\ImpetusJWT;
use app\utils\ImpetusUtils;
use app\models\\'.$functionName.';
use app\models\Auth;

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
    }

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
    }
    
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

    $urlParams = ImpetusUtils::urlParams();

    //Validação de campos
    $bodyCheckFields = ImpetusUtils::bodyCheckFields(
        [
            ["name", $urlParams["name"], ["type(string)"]]
        ]
    );
    if($bodyCheckFields["status"] == 0){
        $response = [
            "code" => "400 Bad Request",
            "response" => $bodyCheckFields
        ];
        return (object)$response;
    } 

    //Realizar busca
    $request = '.$functionName.'::select'.$functionName.'($urlParams);
    $response = [
        "code" => "200 OK",
        "response" => $request
    ];
    return (object)$response;
    
}

$response = webserviceMethod();
header("HTTP/1.1 " . $response->code);
header("Content-Type: application/json");
echo json_encode($response->response);

';

    $arquivo = fopen("build/backend/app/controllers/$tableName/select$functionName.php", 'w');
    if($arquivo == false){
        echo "\033[1;31m"."\n(500 Internal Server Error) Falha ao criar controller (delete".$functionName.")". "\033[0m";
        return false;
    }else{
        $escrever = fwrite($arquivo, $snippet);
        if($escrever == false){
            echo "\033[1;31m"."\n(500 Internal Server Error) Falha ao preencher controller (delete".$functionName.")". "\033[0m";
            return false;
        }else{
            echo "\033[1;32m"."\n(200 OK) Controller select".$functionName." criado com sucesso.". "\033[0m";
        }
    } 

    /**
     * FIM - Controller SELECT
     */

     /**
     * Documentação - README.md
     */

$jsonBody = "";
$semicolon = ",";
$count = count($documentation);
for($i = 0; $i < $count; $i++){
    if($i+1 < $count){
    $jsonBody .= '"' . $documentation[$i][0] . '" : "' . $documentation[$i][2] . '"' . $semicolon . '
    ';
    }else{
    $jsonBody .= '"' . $documentation[$i][0] . '" : "' . $documentation[$i][2] . '"';
    }
}

$snippet= '# Documentação de API - '.$functionName.'

## Autenticação

A autenticação ocorre atráves de JSON Web Token. Faça a autenticação em "/login" e informe o bearer token no header da requisição.

## (GET) Buscar registro

#### Endpoint

```shell
/'.$tableName.'/get?id=1
```

#### Response

```json
{
"status": 1,
"code": 200,
"info": "Registro encontrado",
"data": {
    "id": "1",
    "field": "test",
    "createdAt": "2024-03-03 14:12:39",
    "updatedAt": null
    }
}
```

## (GET) Listar registros

#### Endpoint

```shell
/'.$tableName.'/list
```

#### Parâmetros de URL (opcionais)
- currentPage = (int) Define a página atual da paginação;
- dataPerPage = (int) Define a quantidade de dados a serem retornados por página. (Default: 10)

#### Response

```json
{
	"status": 1,
	"code": 200,
	"currentPage": 1,
	"numberOfPages": 1,
	"dataPerPage": 10,
	"data": [
		{
			"id": "1",
			"field": "test",
			"createdAt": "2024-03-07 11:26:16",
			"updatedAt": "2024-03-07 11:27:28"
		},
		{
			"id": "2",
			"field": "test",
			"createdAt": "2024-03-03 13:55:25",
			"updatedAt": null
		}
	]
}
```

## (POST) Criar novo registro

#### Endpoint

```shell
/'.$tableName.'/create
```
#### Body

```json
{
    '.$jsonBody.'
}
```

#### Response

```json
{
    "status": 1,
    "code": 200,
    "id" : 2,
    "info": "Registro criado com sucesso",
}
```

## (PUT) Atualizar registro

#### Endpoint

```shell
/'.$tableName.'/update
```
#### Body

```json
{
    "id" : 1,
    '.$jsonBody.'
}
```

#### Response

```json
{
    "status": 1,
    "code": 200,
    "info": "Registro atualizado com sucesso",
}
```

## (DELETE) Deletar registro

#### Endpoint

```shell
/'.$tableName.'/delete
```

#### Response

```json
{
    "status": 1,
    "code": 200,
    "info": "Registro deletado com sucesso",
}
```

';

    $arquivo = fopen("build/backend/app/controllers/$tableName/README.md", 'w');
    if($arquivo == false){
        echo "\033[1;31m"."\n(500 Internal Server Error) Falha ao criar README.md (".$functionName.")". "\033[0m";
        return false;
    }else{
        $escrever = fwrite($arquivo, $snippet);
        if($escrever == false){
            echo "\033[1;31m"."\n(500 Internal Server Error) Falha ao preencher README.md (".$functionName.")". "\033[0m";
            return false;
        }else{
            echo "\033[1;32m"."\n(200 OK) Documentação (README.md) criada com sucesso.". "\033[0m";
            return true;
        }
    } 

    /**
     * FIM - README.md
     */
  
    }else{
        $error = $stmt->errorInfo();
        $error = $error[2];
        echo "\033[1;31m"."\n(500 Internal Server Error) ". $error ."\033[0m";
        return false;
    }

}