<?php

$systemConfig = [
    "status" => "debug",
    "appName" => "Impetus Framework",
    "version" => "1.0.0",
    "rootPath" => "http://localhost/impetus/build/",
    "webservicePath" => "http://localhost/impetus/build/ws/",
    "copyrightText" => "IMPETUS 2024",
    "errorReporting" => E_ALL,
    "database" => [
        "type" => "mariadb",
        "server" => "localhost",
        "username" => "root",
        "password" => "",
        "database" => "impetus"
        ],
    "api" => [
        "token" => "_jp[xovd[+({jnop12@p(5n1]930go"
        ],
    "storage" => [
        "defaultPath" => "storage"
        ]
];

//Reportar erros
error_reporting($systemConfig["errorReporting"]);

//Configuração de banco de dados
$dbConfigServer = $systemConfig["database"]["server"];
$dbConfigDatabase = $systemConfig["database"]["database"];
$dbConfigUsername = $systemConfig["database"]["username"];
$dbConfigPassword = $systemConfig["database"]["password"];

//Conexão
try {
    $conn = new PDO("mysql:host=$dbConfigServer;dbname=$dbConfigDatabase", $dbConfigUsername, $dbConfigPassword);
} catch (PDOException $e) {
    die("Connection Failed: " . $e->getMessage());
}
