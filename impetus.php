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
require_once "./impetus/cmd/cli/routes.php";
require_once "./impetus/cmd/cli/tests.php";
require_once "./impetus/cmd/cli/run.php";

$availableCommands = [
    ["init", "Cria a estrutura básica da aplicação", "php impetus.php init projectName"],
    ["migrate", "Realiza a criação do banco de dados e migração de dados", "php impetus.php migrate --all"],
    ["build", "Cria uma estrutura de model, controller e routes com base em uma tabela, assim como todo o CRUD da tabela", "php impetus.php build tableName --all"],
    ["config", "Realiza uma configuração de ambiente, como por exemplo, configura sua aplicação para uso de testes com PHPUnit automaticamente (--test)", "php impetus.php config --test"],
    ["run", "Executa um comando pré-definido no arquivo 'impetus.json'", "php impetus.php run test"],
];

if(!isset($argv[1])){
    cmd($availableCommands);
}else{
    $command = $argv[1];

    if($command == 'init'){
        if($argc == 4 || $argc == 3){
            init($argv);
        }else{
            echo "\nNúmero de argumentos incorretos. \n";
            echo "Exemplo de comando: php impetus.php init appName \n\n";
        }
    }elseif($command == 'cmd'){
        cmd($availableCommands);
    }elseif($command == 'migrate'){
        if($argc == 3){
            if($argv[2] == '--tables'){
                tables();
                echo "\n";
            }elseif($argv[2] == '--views'){
                views();
                echo "\n";
            }elseif($argv[2] == '--data'){
                data();
                echo "\n";
            }elseif($argv[2] == '--all'){ 
                migrate();
                echo "\n";
            }else{
                echo "Tipo de comando migrate inexistente. \n\n";
                echo "Exemplo de comando: php impetus.php migrate --tables. \n";
                echo "Opções de migrate: tables, views, populate e all. \n\n";
            }
        }else{
            echo "\nNúmero de argumentos incorretos. \n";
            echo "Exemplo de comando: php impetus.php migrate --tables. \n";
            echo "Opções de migrate: tables, views, populate e all. \n\n";
        }
    }elseif($command == 'build'){
        if($argc == 4){
            if($argv[3] == '--model'){
                model($argv[2]);
            }elseif($argv[3] == '--controller'){
                controller($argv[2]);
            }elseif($argv[3] == '--routes'){
                routes($argv[2]);
            }elseif($argv[3] == '--all'){
                build($argv[2]);
            }else{
                echo "Tipo de comando build inexistente. \n";
                echo "Exemplo de comando: php impetus.php build tableName --all. \n";
                echo "Opções de migrate: model, controller, routes e all. \n\n";
            }
        }else{
            echo "\nNúmero de argumentos incorretos. \n";
            echo "Exemplo de comando: php impetus.php build tableName --all. \n";
            echo "Opções de migrate: model, controller, routes e all. \n\n";
        }
    }elseif($command == 'config'){
        if($argc == 3){
            if($argv[2] == '--test'){
                tests();
            }else{
                echo "Tipo de comando config inexistente. \n";
                echo "Exemplo de comando: php impetus.php config --test \n\n";
                echo "Opções de config: test. \n\n";
            }
        }else{
            echo "\nNúmero de argumentos incorretos. \n";
            echo "Exemplo de comando: php impetus.php config --test \n\n";
        }
    }elseif($command == 'run'){
        if($argc == 3){
            run($argv[2]);
        }else{
            echo "\nNúmero de argumentos incorretos. \n";
            echo "Exemplo de comando: php impetus.php run test \n\n";
        }
    }else{
        echo "\nComando não encontrado. \n";
        echo "Utilize o comando 'cmd' para verificar os comandos disponíveis. \n";
        echo "Em caso de dúvidas, confira a documentação em https://github.com/gustavohvs-dev/impetus \n\n";
    }
}
