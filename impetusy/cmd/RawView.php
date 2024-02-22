<?php

function rawView($name)
{
    require "build/backend/app/config/config.php";
    echo "\nCriando Raw View ({$name})";

    $snippet = "";

/**
 * Criando Raw View
 */

$snippet.= '<?php

/**
 * '.$name.' View
 */

echo "'.$name.' View";

?>
';

    if(!is_dir("build/frontend/app/views/$name")){
        mkdir("build/frontend/app/views/$name", 0751);
        echo "\nPasta 'build/frontend/app/views/$name' criada.";
    }else{
        echo "\nPasta 'build/frontend/app/views/$name' já existente.";
    }

    $arquivo = fopen("build/frontend/app/views/$name/$name.php", 'w');
    if($arquivo == false){
        return "\033[1;31m"."\n(500 Internal Server Error) Falha ao criar Raw View (".$name.")" . "\033[0m";
    }else{
        $escrever = fwrite($arquivo, $snippet);
        if($escrever == false){
            return "\033[1;31m"."\n(500 Internal Server Error) Falha ao preencher Raw View (".$name.")" . "\033[0m";
        }else{
            echo "\033[1;32m"."\n(200 OK) Raw View '".$name."' criada com sucesso." . "\033[0m";
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