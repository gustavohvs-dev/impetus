<?php

function tables(){
    require_once "build/backend/app/database/database.php";
    require "build/backend/app/config/config.php";
    $databaseClass = new Database;
    $databaseMethods = get_class_methods($databaseClass);
    foreach($databaseMethods as $method){
        if(substr($method, -5) == "Table"){
            $tableName = substr($method, 0, -5);
            $tableData = $databaseClass->$method();
            $table = "CREATE TABLE ".$tableName." ".$tableData;
            $stmt = $conn->prepare($table);
            if($stmt->execute()){
                echo "\033[1;32m" . "(200 OK) Table '".$tableName."' created successfuly\n" . "\033[0m";
            }else{
                $error = $stmt->errorInfo();
                $error = $error[2];
                echo "\033[1;33m" . "(500 Internal Server Error) ".$error."\n" . "\033[0m";
            }
        }
    }
}

function data(){
    require_once "build/backend/app/database/database.php";
    require "build/backend/app/config/config.php";
    $databaseClass = new Database;
    $databaseMethods = get_class_methods($databaseClass);
    foreach($databaseMethods as $method){
        if(substr($method, -4) == "Data"){
            $dataName = substr($method, 0, -4);
            $data = $databaseClass->$method();
            $stmt = $conn->prepare($data);
            if($stmt->execute()){
                echo "\033[1;32m" . "(200 OK) ".$dataName." created successfuly\n" . "\033[0m";
            }else{
                $error = $stmt->errorInfo();
                $error = $error[2];
                echo "\033[1;33m" . "(500 Internal Server Error) ".$error."\n" . "\033[0m";
            }
        }
    }
}

function views(){
    require_once "build/backend/app/database/database.php";
    require "build/backend/app/config/config.php";
    $databaseClass = new Database;
    $databaseMethods = get_class_methods($databaseClass);
    foreach($databaseMethods as $method){
        if(substr($method, -4) == "View"){
            $viewName = substr($method, 0, -4);
            $viewData = $databaseClass->$method();
            $view = "CREATE VIEW vw_".$viewName." AS SELECT " . $viewData;
            $stmt = $conn->prepare($view);
            if($stmt->execute()){
                echo "\033[1;32m" . "(200 OK) View '".$viewName."' created successfuly\n" . "\033[0m";
            }else{
                $error = $stmt->errorInfo();
                $error = $error[2];
                echo "\033[1;33m" . "(500 Internal Server Error) ".$error."\n" . "\033[0m";
            }
        }
    }
}

function migrate(){
    tables();
    views();
    data();
}