<?php

function controller($tableName)
{
    require "app/config/config.php";
    echo "\nCriando controllers ({$tableName})...";

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
                $columnType = explode("(", $column["Type"]);
                $type = $columnType[0];
                $columnType = explode(")", $columnType[1]);
                $typeArgs = $columnType[0];

                if($type == "int" || $type == "tinyint" || $type == "smailint" || $type == "mediumint" || $type == "bigint"){
                    $ruleArgs = "'type(int)'";
                    $ruleArgs .= ", 'length(".$typeArgs.")'";
                }elseif($type == "float" || $type == "decimal" || $type == "double" || $type == "real" || $type == "bit" || $type == "serial"){
                    $ruleArgs = "'type(number)'";
                    $ruleArgs .= ", 'length(".$typeArgs.")'";
                }elseif($type == "boolean"){
                    $ruleArgs = "'type(boolean)'";
                }elseif($type == "date"){
                    $ruleArgs = "'type(date)'";
                }elseif($type == "datetime"){
                    $ruleArgs = "'type(datetime)'";
                }elseif($type == "tinytext" || $type == "text" || $type == "mediumtext" || $type == "longtext"){
                    $ruleArgs = "'type(string)', 'specialChar'";
                    $ruleArgs .= ", 'length(".$typeArgs.")'";
                }elseif($type == "char" || $type == "varchar"){
                    $ruleArgs = "'type(string)', 'uppercase'";
                    $ruleArgs .= ", 'length(".$typeArgs.")'";
                }elseif($type == "enum"){
                    $ruleArgs = "'type(string)'";
                    $typeArgs = str_replace("'", "", $typeArgs);
                    $typeArgs = str_replace(",", "|", $typeArgs);
                    $ruleArgs .= ", 'enum(".$typeArgs.")'";
                }else{
                    $ruleArgs = "type(string)";
                }

                if($column["Null"]=="YES"){
                    $ruleArgs .= ", 'nullable'";
                }
                
                $rules .= '$validate = ImpetusUtils::validator("'.$column['Field'].'", $jsonParams->'.$column['Field'].', ['.$ruleArgs.']);
                    if($validate["status"] == 0){
                        $response = [
                            "code" => "400 Bad Request",
                            "response" => $validate
                        ];
                        return (object)$response;
                    }'.$rulesTab;
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
        if(!is_dir("app/controllers/$tableName")){
            mkdir("app/controllers/$tableName", 0751);
            echo "Pasta 'app/controllers/$tableName' criada. \n";
        }else{
            echo "Pasta 'app/controllers/$tableName' já existente. \n";
        }

        /**
         * Controller - GET
         */

$snippet= '<?php

//Importando models e middlewares
include_once "app/models/impetus/ImpetusJWT.php";
include_once "app/models/impetus/ImpetusUtils.php";
include_once "app/models/'.$functionName.'.php";
include_once "app/middlewares/Auth.php";
use app\models\impetus\ImpetusJWT;
use app\models\impetus\ImpetusUtils;
use app\models\\'.$functionName.';
use app\middlewares\Auth;

function webserviceMethod(){

    require "app/config/config.php";
    $secret = $systemConfig["api"]["token"];

    switch($_SERVER["REQUEST_METHOD"]){

        default:
    
            $response = [
                "code" => "401 Unauthorized",
                "response" => [
                    "status" => 0,
                    "code" => 401,
                    "info" => "Método não encontrado",
                ]
            ];
            return (object)$response;
    
        break;

        case "GET":

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

                    //Validar ID informado
                    $urlParams = ImpetusUtils::urlParams();
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
            
        break;
    
    }

}

$response = webserviceMethod();
header("HTTP/1.1 " . $response->code);
header("Content-Type: application/json");
echo json_encode($response->response);

';

    $arquivo = fopen("app/controllers/$tableName/get$functionName.php", 'w');
    if($arquivo == false){
        return "\n(500 Internal Server Error) Falha ao criar controller (get".$functionName.") \n";
    }else{
        $escrever = fwrite($arquivo, $snippet);
        if($escrever == false){
            return "\n(500 Internal Server Error) Falha ao preencher controller (get".$functionName.") \n";
        }else{
            echo "\n(200 OK) Controller get'".$functionName."' criado com sucesso. \n";
        }
    } 

    /**
     * FIM - Controller GET
     */

    /**
     * Controller - LIST
     */

$snippet= '<?php

//Importando models e middlewares
include_once "app/models/impetus/ImpetusJWT.php";
include_once "app/models/impetus/ImpetusUtils.php";
include_once "app/models/'.$functionName.'.php";
include_once "app/middlewares/Auth.php";
use app\models\impetus\ImpetusJWT;
use app\models\impetus\ImpetusUtils;
use app\models\\'.$functionName.';
use app\middlewares\Auth;

function webserviceMethod(){

    require "app/config/config.php";
    $secret = $systemConfig["api"]["token"];

    switch($_SERVER["REQUEST_METHOD"]){

        default:
    
            $response = [
                "code" => "401 Unauthorized",
                "response" => [
                    "status" => 0,
                    "code" => 401,
                    "info" => "Método não encontrado",
                ]
            ];
            return (object)$response;
    
        break;

        case "GET":

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
            
        break;
    
    }

}

$response = webserviceMethod();
header("HTTP/1.1 " . $response->code);
header("Content-Type: application/json");
echo json_encode($response->response);
';

    $arquivo = fopen("app/controllers/$tableName/list$functionName.php", 'w');
    if($arquivo == false){
        return "\n(500 Internal Server Error) Falha ao criar controller (list".$functionName.") \n";
    }else{
        $escrever = fwrite($arquivo, $snippet);
        if($escrever == false){
            return "\n(500 Internal Server Error) Falha ao preencher controller (list".$functionName.") \n";
        }else{
            echo "\n(200 OK) Controller list'".$functionName."' criado com sucesso. \n";
        }
    } 

    /**
     * FIM - Controller LIST
     */

    /**
     * Controller - INSERT
     */

$snippet= '<?php

//Importando models e middlewares
include_once "app/models/impetus/ImpetusJWT.php";
include_once "app/models/impetus/ImpetusUtils.php";
include_once "app/models/'.$functionName.'.php";
include_once "app/middlewares/Auth.php";
use app\models\impetus\ImpetusJWT;
use app\models\impetus\ImpetusUtils;
use app\models\\'.$functionName.';
use app\middlewares\Auth;

function webserviceMethod(){

    require "app/config/config.php";
    $secret = $systemConfig["api"]["token"];

    switch($_SERVER["REQUEST_METHOD"]){

        default:
    
            $response = [
                "code" => "401 Unauthorized",
                "response" => [
                    "status" => 0,
                    "code" => 401,
                    "info" => "Método não encontrado",
                ]
            ];
            return (object)$response;
    
        break;

        case "POST":

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
                    '.$rules.'

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
            
        break;
    
    }

}

$response = webserviceMethod();
header("HTTP/1.1 " . $response->code);
header("Content-Type: application/json");
echo json_encode($response->response);
';

    $arquivo = fopen("app/controllers/$tableName/create$functionName.php", 'w');
    if($arquivo == false){
        return "\n(500 Internal Server Error) Falha ao criar controller (create".$functionName.") \n";
    }else{
        $escrever = fwrite($arquivo, $snippet);
        if($escrever == false){
            return "\n(500 Internal Server Error) Falha ao preencher controller (create".$functionName.") \n";
        }else{
            echo "\n(200 OK) Controller create'".$functionName."' criado com sucesso. \n";
        }
    } 

    /**
     * FIM - Controller INSERT
     */

    /**
     * Controller - UPDATE
     */

$snippet= '<?php

//Importando models e middlewares
include_once "app/models/impetus/ImpetusJWT.php";
include_once "app/models/impetus/ImpetusUtils.php";
include_once "app/models/'.$functionName.'.php";
include_once "app/middlewares/Auth.php";
use app\models\impetus\ImpetusJWT;
use app\models\impetus\ImpetusUtils;
use app\models\\'.$functionName.';
use app\middlewares\Auth;

function webserviceMethod(){

    require "app/config/config.php";
    $secret = $systemConfig["api"]["token"];

    switch($_SERVER["REQUEST_METHOD"]){

        default:
    
            $response = [
                "code" => "401 Unauthorized",
                "response" => [
                    "status" => 0,
                    "code" => 401,
                    "info" => "Método não encontrado",
                ]
            ];
            return (object)$response;
    
        break;

        case "PUT":

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
                    $validate = ImpetusUtils::validator("'.$primaryKey.'", $jsonParams->'.$primaryKey.', ["type(int)"]);
                    if($validate["status"] == 0){
                        $response = [
                            "code" => "400 Bad Request",
                            "response" => $validate
                        ];
                        return (object)$response;
                    }
                    '.$rules.'

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
            
        break;
    
    }

}

$response = webserviceMethod();
header("HTTP/1.1 " . $response->code);
header("Content-Type: application/json");
echo json_encode($response->response);
';

    $arquivo = fopen("app/controllers/$tableName/update$functionName.php", 'w');
    if($arquivo == false){
        return "\n(500 Internal Server Error) Falha ao criar controller (update".$functionName.") \n";
    }else{
        $escrever = fwrite($arquivo, $snippet);
        if($escrever == false){
            return "\n(500 Internal Server Error) Falha ao preencher controller (update".$functionName.") \n";
        }else{
            echo "\n(200 OK) Controller update'".$functionName."' criado com sucesso. \n";
        }
    } 

    /**
     * FIM - Controller UPDATE
     */

    /**
     * Controller - DELETE
     */

$snippet= '<?php

//Importando models e middlewares
include_once "app/models/impetus/ImpetusJWT.php";
include_once "app/models/impetus/ImpetusUtils.php";
include_once "app/models/'.$functionName.'.php";
include_once "app/middlewares/Auth.php";
use app\models\impetus\ImpetusJWT;
use app\models\impetus\ImpetusUtils;
use app\models\\'.$functionName.';
use app\middlewares\Auth;

function webserviceMethod(){

    require "app/config/config.php";
    $secret = $systemConfig["api"]["token"];

    switch($_SERVER["REQUEST_METHOD"]){

        default:
    
            $response = [
                "code" => "401 Unauthorized",
                "response" => [
                    "status" => 0,
                    "code" => 401,
                    "info" => "Método não encontrado",
                ]
            ];
            return (object)$response;
    
        break;

        case "DELETE":

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

                    //Validar ID informado
                    $urlParams = ImpetusUtils::urlParams();
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
            }
            
        break;
    
    }

}

$response = webserviceMethod();
header("HTTP/1.1 " . $response->code);
header("Content-Type: application/json");
echo json_encode($response->response);

';

    $arquivo = fopen("app/controllers/$tableName/delete$functionName.php", 'w');
    if($arquivo == false){
        return "\n(500 Internal Server Error) Falha ao criar controller (delete".$functionName.") \n";
    }else{
        $escrever = fwrite($arquivo, $snippet);
        if($escrever == false){
            return "\n(500 Internal Server Error) Falha ao preencher controller (delete".$functionName.") \n";
        }else{
            echo "\n(200 OK) Controller delete'".$functionName."' criado com sucesso. \n";
        }
    } 

    /**
     * FIM - Controller DELETE
     */
  
    }else{
        $error = $stmt->errorInfo();
        $error = $error[2];
        echo "\n" .$error;
        return "\n(500 Internal Server Error) Falha ao encontrar tabela";
    }

    echo "\n";
}