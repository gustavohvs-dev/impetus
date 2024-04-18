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

    //Companies routes
    "companies/get" => fn() => Router::get("app/controllers/companies/getCompanies.php"),
    "companies/list" => fn() => Router::get("app/controllers/companies/listCompanies.php"),
    "companies/select" => fn() => Router::get("app/controllers/companies/selectCompanies.php"),
    "companies/create" => fn() => Router::post("app/controllers/companies/createCompanies.php"),
    "companies/update" => fn() => Router::put("app/controllers/companies/updateCompanies.php"),
    "companies/delete" => fn() => Router::delete("app/controllers/companies/deleteCompanies.php"),

    //Log routes
    "log/get" => fn() => Router::get("app/controllers/log/getLog.php"),
    "log/list" => fn() => Router::get("app/controllers/log/listLog.php"),
    "log/create" => fn() => Router::post("app/controllers/log/createLog.php"),
];

Router::ImpetusRouter($routes);