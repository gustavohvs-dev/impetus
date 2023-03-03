<?php

function controller($tableName)
{
    require "app/database/database.php";
    echo "\nCriando controller ({$tableName})";

    //Busca tabela
    $query = "DESC $tableName";
    $stmt = $conn->prepare($query);
    if($stmt->execute())
    {
        $table = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "\nTabela encontrada.";

        $functionName = ucfirst(strtolower($tableName));

        $pointerCreate = 0;
        $columnNameCreate = [];
        $typeCreate = [];

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
include_once "app/middlewares/Auth.php";
use app\models\impetus\ImpetusJWT;
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
                    //Regra de negócio do método
                    $response = [
                        "code" => "200 OK",
                        "response" => [
                            "status" => 1,
                            "info" => "Método funcionando (GET)"
                        ]
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

    $arquivo = fopen("app/controllers/$tableName/get$functionName.php", 'w');
    if($arquivo == false){
        return "\nError: Falha ao criar controller (get".$functionName.") \n";
    }else{
        $escrever = fwrite($arquivo, $snippet);
        if($escrever == false){
            return "\nError: Falha ao preencher controller (get".$functionName.") \n";
        }else{
            echo "\nController get'".$functionName."' criado com sucesso. \n";
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
include_once "app/middlewares/Auth.php";
use app\models\impetus\ImpetusJWT;
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
                    //Regra de negócio do método
                    $response = [
                        "code" => "200 OK",
                        "response" => [
                            "status" => 1,
                            "info" => "Método funcionando (LIST)"
                        ]
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
        return "\nError: Falha ao criar controller (list".$functionName.") \n";
    }else{
        $escrever = fwrite($arquivo, $snippet);
        if($escrever == false){
            return "\nError: Falha ao preencher controller (list".$functionName.") \n";
        }else{
            echo "\nController list'".$functionName."' criado com sucesso. \n";
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
include_once "app/middlewares/Auth.php";
use app\models\impetus\ImpetusJWT;
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
                    //Regra de negócio do método
                    $response = [
                        "code" => "200 OK",
                        "response" => [
                            "status" => 1,
                            "info" => "Método funcionando (CREATE)"
                        ]
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

    $arquivo = fopen("app/controllers/$tableName/create$functionName.php", 'w');
    if($arquivo == false){
        return "\nError: Falha ao criar controller (create".$functionName.") \n";
    }else{
        $escrever = fwrite($arquivo, $snippet);
        if($escrever == false){
            return "\nError: Falha ao preencher controller (create".$functionName.") \n";
        }else{
            echo "\nController create'".$functionName."' criado com sucesso. \n";
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
include_once "app/middlewares/Auth.php";
use app\models\impetus\ImpetusJWT;
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
                    //Regra de negócio do método
                    $response = [
                        "code" => "200 OK",
                        "response" => [
                            "status" => 1,
                            "info" => "Método funcionando (UPDATE)"
                        ]
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

    $arquivo = fopen("app/controllers/$tableName/update$functionName.php", 'w');
    if($arquivo == false){
        return "\nError: Falha ao criar controller (update".$functionName.") \n";
    }else{
        $escrever = fwrite($arquivo, $snippet);
        if($escrever == false){
            return "\nError: Falha ao preencher controller (update".$functionName.") \n";
        }else{
            echo "\nController update'".$functionName."' criado com sucesso. \n";
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
include_once "app/middlewares/Auth.php";
use app\models\impetus\ImpetusJWT;
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
                    //Regra de negócio do método
                    $response = [
                        "code" => "200 OK",
                        "response" => [
                            "status" => 1,
                            "info" => "Método funcionando (DELETE)"
                        ]
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

    $arquivo = fopen("app/controllers/$tableName/delete$functionName.php", 'w');
    if($arquivo == false){
        return "\nError: Falha ao criar controller (delete".$functionName.") \n";
    }else{
        $escrever = fwrite($arquivo, $snippet);
        if($escrever == false){
            return "\nError: Falha ao preencher controller (delete".$functionName.") \n";
        }else{
            echo "\nController delete'".$functionName."' criado com sucesso. \n";
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

    echo "\n(200 OK) Controller criado com sucesso";

    echo "\n\n";
}