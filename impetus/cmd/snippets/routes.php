<?php

function routesSnippet(){

$snippet = 
'<?php

$routes = [

	["login", "app/controllers/login/login.php"],
	["createUser", "app/controllers/users/createUser.php"],

];
';

return $snippet;

}