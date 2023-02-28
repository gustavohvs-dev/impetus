<?php

function build($tableName){
    require "app/database/database.php";
    echo "\nExecutando comando build... {$tableName}";
    echo "\n\n";
}

function model($tableName){
    require "app/database/database.php";
    echo "\nExecutando comando build model... {$tableName}";

    //Busca tabela
    $query = "DESC $tableName";
    $stmt = $conn->prepare($query);
    if($stmt->execute()){
        $table = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "\nTabela encontrada.";
    }else{
        $error = $stmt->errorInfo();
        $error = $error[2];
        echo "\n" .$error;
        return "\n(500 Internal Server Error) Falha ao encontrar tabela";
    }

    var_dump($table);

    return "\n(200 OK) Model criada com sucesso";

    echo "\n\n";
}

function controller($tableName){
    require "app/database/database.php";
    echo "\nExecutando comando build controller... {$tableName}";
    echo "\n\n";
}