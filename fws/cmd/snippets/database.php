<?php

function databaseSnippet(){

$snippet = 
'<?php

require "app/config/config.php";

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