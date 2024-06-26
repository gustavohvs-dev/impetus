<?php

function model($tableName)
{
    require "build/config.php";
    echo "\nCriando model ({$tableName})";

    $snippet = "";

    //Busca tabela
    $query = "DESC $tableName";
    $stmt = $conn->prepare($query);
    if($stmt->execute())
    {
        $table = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "\nTabela encontrada.";

        $functionName = ucfirst(strtolower($tableName));

        if(is_file("build/ws/app/models/$functionName.php")){
            echo "\nArquivo 'build/ws/app/models/$functionName' já existente.";
            echo "\033[1;31m"."\nOperação cancelada"."\n \033[0m";
            return;
        }

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
                    $typeCreate[$pointerCreate] = "\PDO::PARAM_INT";
                }else{
                    $typeCreate[$pointerCreate] = "\PDO::PARAM_STR";
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

        if(!isset($primaryKey)){
            echo "\033[1;31m"."\nTabela '".$tableName."' não possui chave primária\n" . "\033[0m";
            return;
        }

        /**
         * Criando model
         */

$snippet.= '<?php

namespace app\models;

class '.$functionName.'
{
    static function get'.$functionName.'($id)
    {
        require "../config.php";
        $stmt = $conn->prepare("SELECT * FROM '.$tableName.' WHERE '.$primaryKey.' = :ID");
        $stmt->bindParam(":ID", $id, \PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($result <> null) {
            $response = [
                "status" => 1,
                "code" => 200,
                "info" => "Registro encontrado",
                "data" => $result
            ];
        } else {
            $response = [
                "status" => 0,
                "code" => 404,
                "info" => "Nenhum resultado encontrado"
            ];  
        }
        return (object)$response;
    }

    static function list'.$functionName.'($data)
    {
        require "../config.php";

        //Quantidade de dados
        $stmt = $conn->prepare("SELECT COUNT(id) count FROM '.$tableName.'");
        $stmt->execute();
        $rowCount = $stmt->fetch(\PDO::FETCH_ASSOC);

        //Quantidade de páginas
        if (isset($data["dataPerPage"]) && !empty($data["dataPerPage"])){
            $rowsPerPage = $data["dataPerPage"];
        }else{
            $rowsPerPage = 10;
        }
        $numberOfPages = ceil($rowCount["count"]/$rowsPerPage);
        
        //Requisição
        $query = "SELECT * FROM '.$tableName.' ";

        //Filtros
        $clausule = "WHERE ";
        if(isset($data["id"]) && !empty($data["id"])) {
            $query .= $clausule . "id = \'".$data["id"]."\'";
            $clausule = " AND ";
        }
        /**if(isset($data["name"]) && !empty($data["name"])) {
            $query .= $clausule . "name LIKE \'%".$data["name"]."%\'";
            $clausule = " AND ";
        }*/

        if (isset($data["currentPage"]) && !empty($data["currentPage"]) && $data["currentPage"]>0) {
            $query .= " ORDER BY id LIMIT ".($data["currentPage"]-1)*$rowsPerPage.", " . $rowsPerPage;
            $currentPage = $data["currentPage"];
        }else{
            $query .= " ORDER BY id LIMIT 0, " . $rowsPerPage;
            $currentPage = 1;
        }

        $stmt = $conn->prepare($query);
        $stmt->execute();

        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if ($results <> null) {
            $response = [
                "status" => 1,
                "code" => 200,
                "currentPage" => (INT)$currentPage,
                "numberOfPages" => (INT)$numberOfPages,
                "dataPerPage" => (INT)$rowsPerPage,
                "data" => $results
            ];
            return $response;
        } else {
            $response = [
                "status" => 0,
                "code" => 404,
                "currentPage" => (INT)$currentPage,
                "numberOfPages" => (INT)$numberOfPages,
                "dataPerPage" => (INT)$rowsPerPage,
                "info" => "Nenhum resultado encontrado"
            ];
            return $response;
        }
    }

    static function create'.$functionName.'($data)
    {
        require "../config.php";
        $stmt = $conn->prepare("INSERT INTO '.$tableName.' ('.$queryCreateColumns.') VALUES('.$queryCreateBindsTags.')");
        '.$queryCreateBindsParams.' 
        if ($stmt->execute()) {
            $response = [
                "status" => 1,
                "code" => 200,
                "id" => (int)$conn->lastInsertId(),
                "info" => "Registro criado com sucesso"
            ];
        }else{
            $error = $stmt->errorInfo();
            $error = $error[2];
            $response = [
                "status" => 0,
                "code" => 500,
                "info" => "Falha ao criar registro",
                "error" => $error
            ];
        }
        return (object)$response;
    }

    static function update'.$functionName.'($data)
    {
        require "../config.php";
        $stmt = $conn->prepare("UPDATE '.$tableName.' SET '.$queryUpdateColumns.' WHERE '.$primaryKey.' = :ID");
        $stmt->bindParam(":ID", $data["'.$primaryKey.'"], \PDO::PARAM_INT);
        '.$queryUpdateBindsParams.'
        if ($stmt->execute()) {
            $response = [
                "status" => 1,
                "code" => 200,
                "info" => "Registro atualizado com sucesso"
            ];
        }else{
            $error = $stmt->errorInfo();
            $error = $error[2];
            $response = [
                "status" => 0,
                "code" => 500,
                "info" => "Falha ao atualizar registro",
                "error" => $error
            ];
        }
        return (object)$response;
    }

    static function delete'.$functionName.'($id)
    {
        require "../config.php";
        $stmt = $conn->prepare("UPDATE '.$tableName.' SET status = \'INACTIVE\' WHERE '.$primaryKey.' = :ID");
        $stmt->bindParam(":ID", $id, \PDO::PARAM_INT);
        if ($stmt->execute()) {
            if($stmt->rowCount() != 0){
                $response = [
                    "status" => 1,
                    "code" => 200,
                    "info" => "Registro inativado com sucesso",
                ];
            }else{
                $response = [
                    "status" => 0,
                    "code" => 404,
                    "info" => "Falha ao inativar registro",
                    "error" => "Not found entry (".$id.") for key (id)"
                ];
            }
        } else {
            $error = $stmt->errorInfo();
            $error = $error[2];
            $response = [
                "status" => 0,
                "code" => 404,
                "info" => "Falha ao inativar registro",
                "error" => $error
            ];  
        }
        return (object)$response;
    }

    static function destroy'.$functionName.'($id)
    {
        require "../config.php";
        $stmt = $conn->prepare("DELETE FROM '.$tableName.' WHERE '.$primaryKey.' = :ID");
        $stmt->bindParam(":ID", $id, \PDO::PARAM_INT);
        if ($stmt->execute()) {
            if($stmt->rowCount() != 0){
                $response = [
                    "status" => 1,
                    "code" => 200,
                    "info" => "Registro deletado com sucesso",
                ];
            }else{
                $response = [
                    "status" => 0,
                    "code" => 404,
                    "info" => "Falha ao deletar registro",
                    "error" => "Not found entry (".$id.") for key (id)"
                ];
            }
        } else {
            $error = $stmt->errorInfo();
            $error = $error[2];
            $response = [
                "status" => 0,
                "code" => 404,
                "info" => "Falha ao deletar registro",
                "error" => $error
            ];  
        }
        return (object)$response;
    }

    static function select'.$functionName.'($queryParams)
    {
        require "../config.php";
        $searchField = \'name\';
        $query = "SELECT id AS id, ".$searchField." AS text FROM '.$tableName.' ";
        $clausule = "WHERE ";
        if (isset($queryParams[$searchField]) && !empty($queryParams[$searchField])) {
            $query .= $clausule . $searchField . " LIKE \'%$queryParams[$searchField]%\'";
            $clausule = " AND ";
        }
        $stmt = $conn->prepare($query);
        $stmt->execute();

        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if ($data <> null) {
            return $data;
        } else {
            $response = [
                "status" => 0,
                "error" => "Nenhum dado encontrado!"
            ];
            return $response;
        }
    }

}
';

    $arquivo = fopen("build/ws/app/models/$functionName.php", 'w');
    if($arquivo == false){
        echo "\033[1;31m"."\nFalha ao criar model (".$functionName.")\n" . "\033[0m";
        return;
    }else{
        $escrever = fwrite($arquivo, $snippet);
        if($escrever == false){
            echo "\033[1;31m"."\nFalha ao preencher model (".$functionName.")\n" . "\033[0m";
            return;
        }else{
            echo "\033[1;32m"."\nModel '".$functionName."' criada com sucesso.\n" . "\033[0m";
        }
    } 
  
    }else{
        $error = $stmt->errorInfo();
        $error = $error[2];
        echo "\033[1;31m"."\n". $error ."\n \033[0m";
    }

}