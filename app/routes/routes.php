<?php

use Impetus\Framework\Router;

$routes = [
    //Authentication route
	"login" => fn() => Router::post("app/controllers/login/login.php"),
];

Router::ImpetusRouter($routes);