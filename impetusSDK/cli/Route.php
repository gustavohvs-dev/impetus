<?php

function routes($tableName)
{
    $functionName = ucfirst(strtolower($tableName));

    echo "\nCriando rotas no backend ({$tableName})";

    if(!is_dir("build/ws/app/routes/") && !file_exists("build/ws/app/routes/routes.php")){
        echo "\nArquivo de rotas não encontrado\n";
        return null;
    }else{
        $arquivo = fopen ('build/ws/app/routes/routes.php', 'r');
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

$snippet .= '    //'.$functionName.' routes
    "'.$tableName.'/get" => fn() => Router::get("app/controllers/'.$tableName.'/get'.$functionName.'.php"),
    "'.$tableName.'/list" => fn() => Router::get("app/controllers/'.$tableName.'/list'.$functionName.'.php"),
    "'.$tableName.'/select" => fn() => Router::get("app/controllers/'.$tableName.'/select'.$functionName.'.php"),
    "'.$tableName.'/create" => fn() => Router::post("app/controllers/'.$tableName.'/create'.$functionName.'.php"),
    "'.$tableName.'/update" => fn() => Router::put("app/controllers/'.$tableName.'/update'.$functionName.'.php"),
    "'.$tableName.'/delete" => fn() => Router::delete("app/controllers/'.$tableName.'/delete'.$functionName.'.php"),
    
];

Router::ImpetusRouter($routes);';

        $arquivo = fopen("build/ws/app/routes/routes.php", 'w');
        if($arquivo == false){
            echo "\033[1;31m" . "\nFalha ao criar arquivo de rotas\n" . "\033[0m" ;
            return null;
        }else{
            $escrever = fwrite($arquivo, $snippet);
            if($escrever == false){
                echo "\033[1;31m" . "\nFalha ao preencher arquivo de rotas\n" . "\033[0m";
                return null;
            }else{
                echo "\033[1;32m" . "\nRotas criadas com sucesso\n" . "\033[0m";
                return null;
            }
        } 
    }

}