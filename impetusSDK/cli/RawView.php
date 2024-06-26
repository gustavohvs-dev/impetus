<?php

function rawView($name)
{
    require "build/config.php";
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

    if(!is_dir("build/app/views/$name")){
        mkdir("build/app/views/$name", 0751);
        echo "\nPasta 'build/app/views/$name' criada.";
    }else{
        echo "\nPasta 'build/app/views/$name' já existente.";
    }

    $arquivo = fopen("build/app/views/$name/$name.php", 'w');
    if($arquivo == false){
        return "\033[1;31m"."\nFalha ao criar Raw View (".$name.")\n" . "\033[0m";
    }else{
        $escrever = fwrite($arquivo, $snippet);
        if($escrever == false){
            return "\033[1;31m"."\nFalha ao preencher Raw View (".$name.")\n" . "\033[0m";
        }else{
            echo "\033[1;32m"."\nRaw View '".$name."' criada com sucesso.\n" . "\033[0m";
        }
    } 

    $routeName = strtolower($name);

    echo "\nCriando rotas ({$name})";

    if(!is_dir("build/app/routes/") && !file_exists("build/app/routes/routes.php")){
        echo "\n(404 Not found) Arquivo de rotas não encontrado";
        return null;
    }else{
        $arquivo = fopen ('build/app/routes/routes.php', 'r');
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

        $arquivo = fopen("build/app/routes/routes.php", 'w');
        if($arquivo == false){
            echo "\033[1;31m" . "\nFalha ao criar arquivo de rotas\n" . "\033[0m" ;
            return null;
        }else{
            $escrever = fwrite($arquivo, $snippet);
            if($escrever == false){
                echo "\033[1;31m" . "\nFalha ao preencher arquivo de rotas\n" . "\033[0m";
                return null;
            }else{
                echo "\033[1;32m" . "\nRota criada com sucesso\n" . "\033[0m";
                return null;
            }
        } 
    }

}