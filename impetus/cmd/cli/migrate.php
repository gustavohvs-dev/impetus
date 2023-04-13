<?php

function tables(){
    require_once "app/database/database.php";
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
                echo "\n(200 OK) Table '".$tableName."' created successfuly\n";
            }else{
                $error = $stmt->errorInfo();
                $error = $error[2];
                echo "\n(500 Internal Server Error) ".$error."\n";
            }
        }
    }
}

function data(){
    require_once "app/database/database.php";
    require "app/config/config.php";
    $databaseClass = new Database;
    $databaseMethods = get_class_methods($databaseClass);
    foreach($databaseMethods as $method){
        if(substr($method, -4) == "Data"){
            $dataName = substr($method, 0, -4);
            $data = $databaseClass->$method();
            $stmt = $conn->prepare($data);
            if($stmt->execute()){
                echo "\n(200 OK) ".$dataName." created successfuly\n";
            }else{
                $error = $stmt->errorInfo();
                $error = $error[2];
                echo "\n(500 Internal Server Error) ".$error."\n";
            }
        }
    }
}

function views(){
    require_once "app/database/database.php";
    require "app/config/config.php";
    $databaseClass = new Database;
    $databaseMethods = get_class_methods($databaseClass);
    foreach($databaseMethods as $method){
        if(substr($method, -4) == "View"){
            $viewName = substr($method, 0, -4);
            $viewData = $databaseClass->$method();
            $view = "CREATE VIEW ".$viewName." AS SELECT " . $viewData;
            $stmt = $conn->prepare($view);
            if($stmt->execute()){
                echo "\n(200 OK) View '".$viewName."' created successfuly\n";
            }else{
                $error = $stmt->errorInfo();
                $error = $error[2];
                echo "\n(500 Internal Server Error) ".$error."\n";
            }
        }
    }
}

function migrate(){
    tables();
    views();
    data();
}