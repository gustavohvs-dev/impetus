<?php

//Configurações básicas do sistema
$systemConfig = [
    "appName" => "IMPETUS",
    "copyrightText" => "IMPETUS 2024",
    "status" => "debug",
    "version" => "1.0.0",
];

//Setando atributo de endPoint para requisições JQUERY/AXIOS no $systemConfig
if($systemConfig['status'] == 'online'){
    $systemConfig = array_merge($systemConfig, ["endPoint" => "https://www.website.com/backend/"]);
}else{
    $systemConfig = array_merge($systemConfig, ["endPoint" => "http://localhost/ImpetusPHP/build/backend/"]);
}
