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
    ]
];
';

return $snippet;

}