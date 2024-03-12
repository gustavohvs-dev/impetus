<?php

function tables($path = "Migrate00000000_0.php"){
    require_once "build/backend/app/database/migrations/".$path;
    require "build/backend/app/config/config.php";
    $className = explode(".", $path);
    $databaseClass = new $className[0];
    $databaseMethods = get_class_methods($databaseClass);
    foreach($databaseMethods as $method){
        if(substr($method, -5) == "Table"){
            $tableName = substr($method, 0, -5);
            $tableData = $databaseClass->$method();
            $table = "CREATE TABLE ".$tableName." ".$tableData;
            $stmt = $conn->prepare($table);
            if($stmt->execute()){
                echo "\033[1;32m" . $className[0] . ": " . "Table '".$tableName."' created successfuly\n" . "\033[0m";
            }else{
                $error = $stmt->errorInfo();
                if($error[1] == 1050 || $error[1] == 1062 || $error[1] == 1060){
                    echo "\033[1;33m" . $className[0]. ": " . $error[2] .  " on " . "CREATE TABLE " .$tableName . "\n" . "\033[0m";
                }else{
                    echo "\033[1;31m" . $className[0]. ": " . $error[2] .  " on " . "CREATE TABLE " .$tableName . "\n" . "\033[0m";
                }
            }
        }
    }
    unset($databaseClass);
}

function data($path = "Migrate00000000_0.php"){
    require_once "build/backend/app/database/migrations/".$path;
    require "build/backend/app/config/config.php";
    $className = explode(".", $path);
    $databaseClass = new $className[0];
    $databaseMethods = get_class_methods($databaseClass);
    foreach($databaseMethods as $method){
        if(substr($method, -4) == "Data"){
            $dataName = substr($method, 0, -4);
            $data = $databaseClass->$method();
            $stmt = $conn->prepare($data);
            if($stmt->execute()){
                echo "\033[1;32m" . $className[0] . ": " .$dataName." created successfuly\n" . "\033[0m";
            }else{
                $error = $stmt->errorInfo();
                if($error[1] == 1050 || $error[1] == 1062 || $error[1] == 1060){
                    echo "\033[1;33m" . $className[0]. ": " . $error[2] .  " on " . $data . "\n" . "\033[0m";
                }else{
                    echo "\033[1;31m" . $className[0]. ": " . $error[2] .  " on " . $data . "\n" . "\033[0m";
                }
            }
        }
    }
    unset($databaseClass);
}

function views($path = "Migrate00000000_0.php"){
    require_once "build/backend/app/database/migrations/".$path;
    require "build/backend/app/config/config.php";
    $className = explode(".", $path);
    $databaseClass = new $className[0];
    $databaseMethods = get_class_methods($databaseClass);
    foreach($databaseMethods as $method){
        if(substr($method, -4) == "View"){
            $viewName = substr($method, 0, -4);
            $viewData = $databaseClass->$method();
            $view = "CREATE VIEW vw_".$viewName." AS SELECT " . $viewData;
            $stmt = $conn->prepare($view);
            if($stmt->execute()){
                echo "\033[1;32m" . $className[0] . ": " . "View '".$viewName."' created successfuly\n" . "\033[0m";
            }else{
                $error = $stmt->errorInfo();
                if($error[1] == 1050 || $error[1] == 1062 || $error[1] == 1060){
                    echo "\033[1;33m" . $className[0]. ": " . $error[2] .  " on " . "CREATE VIEW vw_" .$viewName . "\n" . "\033[0m";
                }else{
                    echo "\033[1;31m" . $className[0]. ": " . $error[2] .  " on " . "CREATE VIEW vw_" .$viewName . "\n" . "\033[0m";
                }
            }
        }
    }
    unset($databaseClass);
}

function update($path = "Migrate00000000_0.php"){
    require_once "build/backend/app/database/migrations/".$path;
    require "build/backend/app/config/config.php";
    $className = explode(".", $path);
    $databaseClass = new $className[0];
    $databaseMethods = get_class_methods($databaseClass);
    foreach($databaseMethods as $method){
        if(substr($method, -6) == "Update"){
            $dataName = substr($method, 0, -6);
            $data = $databaseClass->$method();
            $stmt = $conn->prepare($data);
            if($stmt->execute()){
                echo "\033[1;32m" . $className[0] . ": " .$dataName." updated successfuly\n" . "\033[0m";
            }else{
                $error = $stmt->errorInfo();
                if($error[1] == 1050 || $error[1] == 1062 || $error[1] == 1060){
                    echo "\033[1;33m" . $className[0]. ": " . $error[2] .  " on " . $data . "\n" . "\033[0m";
                }else{
                    echo "\033[1;31m" . $className[0]. ": " . $error[2] .  " on " . $data . "\n" . "\033[0m";
                }
            }
        }
    }
    unset($databaseClass);
}

function migrate(){
    $path = "build/backend/app/database/migrations/";
    $diretorio = dir($path);
    while($arquivo = $diretorio -> read()){
        if($arquivo != "." && $arquivo != ".."){
            tables($arquivo);
            update($arquivo);
            views($arquivo);
            data($arquivo);
        }
    }
}