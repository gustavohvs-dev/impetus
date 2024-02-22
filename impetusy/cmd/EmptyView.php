<?php

function emptyView($name)
{
    require "build/backend/app/config/config.php";
    echo "\nCriando Empty View ({$name})";

    $snippet = "";

/**
 * Criando View
 */

$snippet.= '<?php

use app\components\Core;
use app\middlewares\Auth;

$userData = Auth::validateSession([\'admin\']);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<?php Core::header(); ?>
</head>

<body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-layout="default">
	<div class="wrapper">

		<?php Core::sidebar($userData); ?>

		<div class="main">

			<?php Core::topbar(); ?>

			<main class="content">
				<div class="container-fluid p-0">

					<div class="row mb-2 mb-xl-3">
						<div class="col-auto d-none d-sm-block">
							<h3>Empty view</h3>
						</div>

						<div class="col-auto ms-auto text-end mt-n1">
							<button type="button" class="btn btn-primary">Example button</button>
						</div>
					</div>

					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-body">

								</div>
							</div>
						</div>
					</div>

				</div>
			</main>

			<?php Core::footer(); ?>

		</div>
	</div>

	<?php Core::credentials($userData); ?>
	<?php Core::scriptsJs(); ?>

</body>

</html>
';

    if(!is_dir("build/frontend/app/views/$name")){
        mkdir("build/frontend/app/views/$name", 0751);
        echo "\nPasta 'build/frontend/app/views/$name' criada.";
    }else{
        echo "\nPasta 'build/frontend/app/views/$name' já existente.";
    }

    $arquivo = fopen("build/frontend/app/views/$name/$name.php", 'w');
    if($arquivo == false){
        return "\033[1;31m"."\n(500 Internal Server Error) Falha ao criar Empty View (".$name.")" . "\033[0m";
    }else{
        $escrever = fwrite($arquivo, $snippet);
        if($escrever == false){
            return "\033[1;31m"."\n(500 Internal Server Error) Falha ao preencher Empty View (".$name.")" . "\033[0m";
        }else{
            echo "\033[1;32m"."\n(200 OK) Empty View '".$name."' criada com sucesso." . "\033[0m";
        }
    } 

    $routeName = strtolower($name);

    echo "\nCriando rotas ({$name})";

    if(!is_dir("build/frontend/app/routes/") && !file_exists("build/frontend/app/routes/routes.php")){
        echo "\n(404 Not found) Arquivo de rotas não encontrado";
        return null;
    }else{
        $arquivo = fopen ('build/frontend/app/routes/routes.php', 'r');
        $result = [];
        while(!feof($arquivo)){
            $result[] = explode("];",fgets($arquivo));
        }
        fclose($arquivo);

        $snippet = "";
        $rows = count($result);

        foreach($result as $line){
            if (--$rows <= 0) {
                break;
            }
            $snippet.= $line[0];
        }

$snippet .= '    //'.$name.' route
    "'.$routeName.'" => fn() => Router::get("app/views/'.$routeName.'/'.$routeName.'.php"),
];

Router::ImpetusRouter($routes);';

        $arquivo = fopen("build/frontend/app/routes/routes.php", 'w');
        if($arquivo == false){
            echo "\033[1;31m" . "\n(500 Server Internal Error) Falha ao criar arquivo de rotas" . "\033[0m" ;
            return null;
        }else{
            $escrever = fwrite($arquivo, $snippet);
            if($escrever == false){
                echo "\033[1;31m" . "\n(500 Server Internal Error) Falha ao preencher arquivo de rotas" . "\033[0m";
                return null;
            }else{
                echo "\033[1;32m" . "\n(200 OK) Rota criada com sucesso" . "\033[0m";
                return null;
            }
        } 
    }

}