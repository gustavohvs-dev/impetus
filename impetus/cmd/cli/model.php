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

        foreach($table as $column)
        {
            if($column['Key'] == "PRI"){
                $primaryKey = $column['Field'];
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
        $stmt = $conn->prepare("SELECT * FROM '.$tableName.' WHERE '.$primaryKey.' = :ID");
        $stmt->bindParam(":ID", $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
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
        return $response;
    }

    static function list'.$functionName.'()
    {
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
        return $response;
    }

    static function create'.$functionName.'($data)
    {

    }

    static function uptade'.$functionName.'($data)
    {

    }

    static function delete'.$functionName.'($id)
    {

    }

}
';

    $arquivo = fopen("app/models/$functionName.php", 'w');
    if($arquivo == false){
        return "\nError: Falha ao criar model (".$functionName.") \n";
    }else{
        $escrever = fwrite($arquivo, $snippet);
        if($escrever == false){
            return "\nError: Falha ao preencher model (".$functionName.") \n";
        }else{
            echo "\nModel '".$functionName."' criada com sucesso. \n";
        }
    } 
  
    }else{
        $error = $stmt->errorInfo();
        $error = $error[2];
        echo "\n" .$error;
        return "\n(500 Internal Server Error) Falha ao encontrar tabela";
    }

    echo "\n(200 OK) Model criada com sucesso";

    echo "\n\n";
}