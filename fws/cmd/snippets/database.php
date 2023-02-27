<?php

function databaseSnippet(){

$snippet = 
'<?php

require_once "app/config/config.php";

//Conexão
try {
    $conn = new PDO("mysql:host=".$systemConfig["database"]["server"].";dbname=".$systemConfig["database"]["database"]."", $systemConfig["database"]["username"], $systemConfig["database"]["password"]);
} catch (PDOException $e) {
    die("Connection Failed: " . $e->getMessage());
}
';

return $snippet;

}