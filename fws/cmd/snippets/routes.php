<?php

function routesSnippet(){

$snippet = 
'<?php

$indexView = "app/controllers/index/index.php";
$error404View = "app/controllers/errors/error401.php";
$error401View = "app/controllers/errors/error404.php";

$routes = [

	//Teste de aplicação
	["test", "app/controllers/test.php"],

];
';

return $snippet;

}