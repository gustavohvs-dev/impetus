<?php

function model($tableName)
{
    require "app/database/database.php";
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

        /**
         * Criando model
         */

$snippet.= '<?php

namespace app\models;

class '.$functionName.'
{
    static function get'.$functionName.'($id)
    {
        require "app/database/database.php";
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
        require "app/database/database.php";

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

        /*if(isset($data["status"]) && !empty($data["status"])) {
            $clausule = "WHERE ";
            $query .= $clausule . "status = `'.$data["status"].'`";
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
            return (object)$response;
        }
    }

    static function create'.$functionName.'($data)
    {
        require "app/database/database.php";
        $stmt = $conn->prepare("INSERT INTO '.$tableName.' ('.$queryCreateColumns.') VALUES('.$queryCreateBindsTags.')");
        '.$queryCreateBindsParams.' 
        if ($stmt->execute()) {
            $response = [
                "status" => 1,
                "code" => 200,
                "info" => "Registro criado com sucesso"
            ];
        }else{
            $error = $stmt->errorInfo();
            $error = $error[2];
            $response = [
                "status" => 1,
                "code" => 500,
                "info" => "Falha ao criar registro",
                "error" => $error
            ];
        }
        return (object)$response;
    }

    static function update'.$functionName.'($data)
    {
        require "app/database/database.php";
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
                "status" => 1,
                "code" => 500,
                "info" => "Falha ao atualizar registro",
                "error" => $error
            ];
        }
        return (object)$response;
    }

    static function delete'.$functionName.'($id)
    {
        require "app/database/database.php";
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
                    "status" => 1,
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

}
';

    $arquivo = fopen("app/models/$functionName.php", 'w');
    if($arquivo == false){
        return "\n(500 Internal Server Error) Falha ao criar model (".$functionName.") \n";
    }else{
        $escrever = fwrite($arquivo, $snippet);
        if($escrever == false){
            return "\n(500 Internal Server Error) Falha ao preencher model (".$functionName.") \n";
        }else{
            echo "\n(200 OK) Model '".$functionName."' criada com sucesso. \n";
        }
    } 
  
    }else{
        $error = $stmt->errorInfo();
        $error = $error[2];
        echo "\n" .$error;
        return "\n(500 Internal Server Error) Falha ao encontrar tabela";
    }

    echo "\n";

}