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

    static function list'.$functionName.'()
    {
        require "app/database/database.php";
        $stmt = $conn->prepare("SELECT * FROM '.$tableName.'");
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        if ($result <> null) {
            $response = [
                "status" => 1,
                "code" => 200,
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
            $response = [
                "status" => 1,
                "code" => 500,
                "info" => "Falha ao criar registro"
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
            $response = [
                "status" => 1,
                "code" => 500,
                "info" => "Falha ao atualizar registro"
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
            $response = [
                "status" => 1,
                "code" => 200,
                "info" => "Registro deletado com sucesso"
            ];
        } else {
            $response = [
                "status" => 0,
                "code" => 404,
                "info" => "Falha ao deletar registro"
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