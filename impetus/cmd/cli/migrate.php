<?php

function tables(){
    require "app/database/database.php";
    require "app/config/config.php";
    $databaseClass = new Database;
    $databaseMethods = get_class_methods($databaseClass);
    foreach($databaseMethods as $method){
        if(substr($method, -5) == "Table"){
            $tableName = substr($method, 0, -5);
            $tableData = $databaseClass->$method();
            $table = "CREATE TABLE ".$tableName." ".$tableData;
            $stmt = $conn->prepare($table);
            if($stmt->execute()){
                echo "\n(200 OK) 'Table ".$tableName."' created successfuly\n";
            }else{
                $error = $stmt->errorInfo();
                $error = $error[2];
                echo "\n(500 Internal Server Error) ".$error."\n";
            }
        }
    }
    echo "\n";
}

function populate(){
    
}

function views(){
    
}

function migrate(){
    
}