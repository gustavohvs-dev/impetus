<?php

use app\routes\Router;

$routes = [
    //Authentication route
	"login" => fn() => Router::post("app/controllers/login/login.php"),
    "validate" => fn() => Router::post("app/controllers/login/validate.php"),

    //Users routes
    "users/get" => fn() => Router::get("app/controllers/users/getUsers.php"),
    "users/list" => fn() => Router::get("app/controllers/users/listUsers.php"),
    "users/create" => fn() => Router::post("app/controllers/users/createUsers.php"),
    "users/update" => fn() => Router::put("app/controllers/users/updateUsers.php"),
    "users/delete" => fn() => Router::delete("app/controllers/users/deleteUsers.php"),

    //Log routes
    "log/get" => fn() => Router::get("app/controllers/log/getLog.php"),
    "log/list" => fn() => Router::get("app/controllers/log/listLog.php"),
    "log/create" => fn() => Router::post("app/controllers/log/createLog.php"),

    //Observacoes routes
    "observacoes/get" => fn() => Router::get("app/controllers/observacoes/getObservacoes.php"),
    "observacoes/list" => fn() => Router::get("app/controllers/observacoes/listObservacoes.php"),
    "observacoes/select" => fn() => Router::get("app/controllers/observacoes/selectObservacoes.php"),
    "observacoes/create" => fn() => Router::post("app/controllers/observacoes/createObservacoes.php"),
    "observacoes/update" => fn() => Router::put("app/controllers/observacoes/updateObservacoes.php"),
    "observacoes/delete" => fn() => Router::delete("app/controllers/observacoes/deleteObservacoes.php"),
    
    //Arquivos routes
    "arquivos/get" => fn() => Router::get("app/controllers/arquivos/getArquivos.php"),
    "arquivos/list" => fn() => Router::get("app/controllers/arquivos/listArquivos.php"),
    "arquivos/select" => fn() => Router::get("app/controllers/arquivos/selectArquivos.php"),
    "arquivos/create" => fn() => Router::post("app/controllers/arquivos/createArquivos.php"),
    "arquivos/update" => fn() => Router::put("app/controllers/arquivos/updateArquivos.php"),
    "arquivos/delete" => fn() => Router::delete("app/controllers/arquivos/deleteArquivos.php"),

    //Pessoas routes
    "pessoas/get" => fn() => Router::get("app/controllers/pessoas/getPessoas.php"),
    "pessoas/list" => fn() => Router::get("app/controllers/pessoas/listPessoas.php"),
    "pessoas/create" => fn() => Router::post("app/controllers/pessoas/createPessoas.php"),
    "pessoas/update" => fn() => Router::put("app/controllers/pessoas/updatePessoas.php"),
    "pessoas/delete" => fn() => Router::delete("app/controllers/pessoas/deletePessoas.php"),
    "pessoas/select" => fn() => Router::get("app/controllers/pessoas/selectPessoas.php"),
    "pessoas/sheet" => fn() => Router::get("app/controllers/pessoas/sheetPessoas.php"),
    "pessoas/print" => fn() => Router::get("app/controllers/pessoas/printPessoas.php"),

    "gmail/test" => fn() => Router::get("app/utils/PHPMailerManager.php"),

    //Notificacoes routes
    "notificacoes/get" => fn() => Router::get("app/controllers/notificacoes/getNotificacoes.php"),
    "notificacoes/list" => fn() => Router::get("app/controllers/notificacoes/listNotificacoes.php"),
    "notificacoes/select" => fn() => Router::get("app/controllers/notificacoes/selectNotificacoes.php"),
    "notificacoes/create" => fn() => Router::post("app/controllers/notificacoes/createNotificacoes.php"),
    "notificacoes/update" => fn() => Router::put("app/controllers/notificacoes/updateNotificacoes.php"),
    "notificacoes/delete" => fn() => Router::delete("app/controllers/notificacoes/deleteNotificacoes.php"),
    
];

Router::ImpetusRouter($routes);