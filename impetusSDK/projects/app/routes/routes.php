<?php

use app\routes\Router;

$routes = [
    //Index View
    "index" => fn() => Router::get("app/views/index/index.php"),

    //Log View
    "log" => fn() => Router::get("app/views/log/log.php"),

    //Login views
	"login" => fn() => Router::get("app/views/login/login.php"),
    "logout" => fn() => Router::get("app/controllers/logout.php"),
    "auth" => fn() => Router::post("app/controllers/login.php"),

    //Users view
    "users" => fn() => Router::get("app/views/users/users.php"),

    //pessoas View
    "pessoas" => fn() => Router::get("app/views/pessoas/pessoas.php"),
    "painel-pessoas" => fn() => Router::get("app/views/pessoas/painel-pessoas.php"),

    //notificacoes View
    "notificacoes" => fn() => Router::get("app/views/notificacoes/notificacoes.php"),
];

Router::ImpetusRouter($routes);