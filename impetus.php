<?php

/**
 * Interpretador CLI para execução de comandos
 */

require_once "./impetus/cmd/cli/cmd.php";
require_once "./impetus/cmd/cli/init.php";
require_once "./impetus/cmd/cli/migrate.php";
require_once "./impetus/cmd/cli/build.php";
require_once "./impetus/cmd/cli/controller.php";
require_once "./impetus/cmd/cli/model.php";
require_once "./impetus/cmd/cli/route.php";

$availableCommands = [
    ["init", "Cria a estrutura básica da aplicação"],
    ["migrate", "Realiza a criação do banco de dados"],
    ["build", "Cria uma estrutura de model, controller e routes com base em uma tabela"]
];

if(!isset($argv[1])){
    cmd($availableCommands);
}else{
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
                tables();
            }elseif($argv[2] == 'views'){
                views();
            }elseif($argv[2] == 'populate'){
                populate();
            }elseif($argv[2] == 'all'){ 
                migrate();
            }else{
                echo "Tipo de comando migrate inexistente. \n\n";
            }
        }else{
            echo "\nNúmero de argumentos incorretos. \n";
            echo "Exemplo de comando: php impetus.php migrate tables. \n";
            echo "Opções de migrate: tables, views, populate e all. \n\n";
        }
    }elseif($command == 'build'){
        if($argc == 4){
            if($argv[2] == 'model'){
                model($argv[3]);
            }elseif($argv[2] == 'controller'){
                controller($argv[3]);
            }elseif($argv[2] == 'route'){
                route($argv[3]);
            }elseif($argv[2] == 'all'){
                build($argv[3]);
            }else{
                echo "Tipo de comando migrate inexistente. \n\n";
            }
        }else{
            echo "\nNúmero de argumentos incorretos. \n";
            echo "Exemplo de comando: php impetus.php build all tableName. \n";
            echo "Opções de migrate: model, controller e all. \n\n";
        } 
    }else{
        echo "\nComando não encontrado. \n";
        echo "Utilize o comando 'cmd' para verificar os comandos disponíveis. \n";
        echo "Em caso de dúvidas, confira a documentação em https://github.com/gustavohvs-dev/impetus \n\n";
    }
}
