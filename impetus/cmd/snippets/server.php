<?php

function serverSnippet($appName){

$snippet = 
'<?php

//Exibição de erros
if($systemConfig["status"]=="deploy"){
	error_reporting(0);
}elseif($systemConfig["status"]=="dev"){
	error_reporting(E_ERROR);
}elseif($systemConfig["status"]=="debug"){
	error_reporting(E_ALL);
}else{
	error_reporting(0);
}

if (isset($_GET["url"])) {
	$url = explode("/", $_GET["url"]);
	route($url, $routes);
}else{
	//Index
	$response = [
        "status" => "1",
		"code" => "200",
        "info" => "'.$appName.' is working normally",
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