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
        "database" => "'.$dbName.'"
    ],
    "api" => [
        "token" => "E5Z!h_Ugv+X26{832Pg9Gzefhd!IHgs&r"
    ]
];
';

return $snippet;

}