<?php

include_once "impetus/utils/ImpetusUtils.php";
use app\models\impetus\ImpetusUtils;

function configSnippet($appName, $dbName){

    $token = ImpetusUtils::token(30, ["charType" => "special"]);

$snippet = 
'<?php

$systemConfig = [
    "status" => "debug",
    "appName" => "'.$appName.'",
    "version" => "1.0.0",
    "database" => [
        "server" => "localhost",
        "username" => "root",
        "password" => "",
        "database" => "'.$dbName.'"
    ],
    "api" => [
        "token" => "'.$token.'"
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