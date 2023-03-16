<?php

function routes($tableName)
{
    $functionName = ucfirst(strtolower($tableName));

    echo "\nCriando rotas ({$tableName})";

    if(!is_dir("app/routes/") && !file_exists("app/routes/routes.php")){
        echo "\n(404 Not found) Arquivo de rotas não encontrado\n\n";
        return null;
    }else{
        $arquivo = fopen ('app/routes/routes.php', 'r');
        $result = [];
        while(!feof($arquivo)){
            $result[] = explode("];",fgets($arquivo));
        }
        fclose($arquivo);

        $snippet = "";

        foreach($result as $line){
            $snippet.= $line[0];
        }

$snippet .= '    //'.$tableName.' routes
    ["get'.$functionName.'", "app/controllers/'.$functionName.'/get'.$functionName.'.php"],
    ["list'.$functionName.'", "app/controllers/'.$functionName.'/list'.$functionName.'.php"],
    ["create'.$functionName.'", "app/controllers/'.$functionName.'/create'.$functionName.'.php"],
    ["update'.$functionName.'", "app/controllers/'.$functionName.'/update'.$functionName.'.php"],
    ["delete'.$functionName.'", "app/controllers/'.$functionName.'/delete'.$functionName.'.php"],

];
';

        $arquivo = fopen("app/routes/routes.php", 'w');
        if($arquivo == false){
            echo "\n(500 Server Internal Error) Falha ao criar arquivo de todas \n\n";
            return null;
        }else{
            $escrever = fwrite($arquivo, $snippet);
            if($escrever == false){
                echo "\n(500 Server Internal Error) Falha ao preencher arquivo de rotas \n\n";
                return null;
            }else{
                echo "\n(200 OK) Rotas criadas com sucesso\n\n";
                return null;
            }
        } 
    }

}