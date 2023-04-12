<?php

function configSnippet($appName, $dbName){

$snippet = 
'<?php

$systemConfig = [
    "status" => "offline",
    "appName" => "'.$appName.'",
    "version" => "1.0.0",
    "database" => [
        "server" => "localhost",
        "username" => "root",
        "password" => "",
        "database" => "'.$dbName.'"
    ],
    "api" => [
        "token" => "E5Z!h_Ugv+X26{832Pg9Gzefhd!IHgs&r"
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
';

return $snippet;

}