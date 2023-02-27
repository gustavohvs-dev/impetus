<?php

function indexSnippet(){

$snippet = 
'<?php
		
require "app/routes/routes.php";
require "app/config/config.php";

if($systemConfig["status"]=="online"){
	error_reporting(0);
}else{
	error_reporting(E_ALL);
}

if (isset($_GET["url"])) {
	$url = explode("/", $_GET["url"]);
	route($url, $routes, $error401View, $error404View);
}else{
	require_once $indexView;
}

//This function verify the URL and redirect the user
function route($url, $routes, $error401View, $error404View){
	$validated = "false";
	foreach($routes as $route) {
		$exibir = $route[0];
		$rota = $route[1];
		if($url[0] == $exibir) {
			$validated = "true";
			if(file_exists($rota)){
				require_once $rota;
			}else{
				require_once $error404View;
			}
			exit;
		}
	}
	if($validated == "false"){
		require_once $error401View;
	}
}
';

return $snippet;

}