<?php

function indexSnippet($appName){

$snippet = 
'<?php
		
require_once "app/routes/routes.php";
require_once "app/config/config.php";

//Exibição de erros
if($systemConfig["status"]=="online"){
	error_reporting(0);
}else{
	error_reporting(E_ALL);
}

if (isset($_GET["url"])) {
	$url = explode("/", $_GET["url"]);
	route($url, $routes);
}else{
	//Index
	$response = [
        "status" => "1",
        "info" => "Bem-vindo ao '.$appName.'!",
    ];
    header("HTTP/1.1 200 OK");
    header("Content-Type: application/json");
    echo json_encode($response);
}

//This function verify the URL and redirect the user
function route($url, $routes){
	$validated = false;
	foreach($routes as $route) {
		$exibir = $route[0];
		$rota = $route[1];
		if($url[0] == $exibir) {
			$validated = true;
			if(file_exists($rota)){
				require_once $rota;
			}else{
				$response = [
					"status" => "0",
					"info" => "Rota não encontrada!",
				];
				header("HTTP/1.1 404 Not Found");
				header("Content-Type: application/json");
				echo json_encode($response);
			}
		}
	}
	if($validated == false){
		$response = [
			"status" => "0",
			"info" => "Rota não encontrada!",
		];
		header("HTTP/1.1 404 Not Found");
		header("Content-Type: application/json");
		echo json_encode($response);
	}
}
';

return $snippet;

}