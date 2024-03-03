<?php

$systemConfig = [
    "status" => "debug",
    "appName" => "Impetus",
    "version" => "1.0.0",
    "path" => "http://localhost/impetus/build/backend/",
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
        "defaultPath" => "..\storage"
        ]
];

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
