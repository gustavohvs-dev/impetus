<?php

/**
 * Interpretador CLI para execução de comandos
 */

require_once "./impetus/cmd/cli/cmd.php";
require_once "./impetus/cmd/cli/init.php";
require_once "./impetus/cmd/cli/migrate.php";

$availableCommands = [
    ["init", "Cria a estrutura básica da aplicação"],
    ["migrate", "Realiza a criação do banco de dados"],
    ["model", "Cria uma estrutura de model e controller com base em uma tabela"]
];

$command = $argv[1];

if($command == 'init'){
    if($argc == 4){
        init($argv);
    }else{
        echo "\nNúmero de argumentos incorretos. \n";
        echo "Exemplo de comando: php impetus.php init appName dbName \n\n";
    }
}elseif($command == 'cmd'){
    cmd($availableCommands);
}elseif($command == 'migrate'){
    if($argc == 3){
        if($argv[2] == 'tables'){
            echo "\n";
            tables();
            echo "\n\n";
        }elseif($argv[2] == 'views'){
            echo "\n";
            views();
            echo "\n\n";
        }elseif($argv[2] == 'populate'){
            echo "\n";
            populate();
            echo "\n\n";
        }elseif($argv[2] == 'all'){
            echo "\n";
            migrate();
            echo "\n\n";
        }else{
            echo "Tipo de comando migrate inexistente. \n\n";
        }
    }else{
        echo "\nNúmero de argumentos incorretos. \n";
        echo "Exemplo de comando: php impetus.php migrate tables. \n";
        echo "Opções de migrate: tables, views, populate e all. \n\n";
    }
    
}else{
    echo "\nComando não encontrado. \n";
    echo "Utilize o comando 'cmd' para verificar os comandos disponíveis. \n";
    echo "Em caso de dúvidas, confira a documentação em https://github.com/gustavohvs-dev/impetus \n\n";
}