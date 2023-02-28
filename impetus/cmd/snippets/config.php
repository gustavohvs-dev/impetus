<?php

function configSnippet($appName, $dbName){

$snippet = 
'<?php

$systemConfig = [
    "status" => "offline",
    "appName" => "teste",
    "version" => "1.0.0",
    "database" => [
        "server" => "localhost",
        "username" => "root",
        "password" => "",
        "database" => "dbteste"
    ]
];
';

return $snippet;

}