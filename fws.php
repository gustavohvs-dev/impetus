<?php

/**
 * Interpretador CLI para execução de comandos
 */

require_once "./fws/cmd/cli/cmd.php";
require_once "./fws/cmd/cli/init.php";
require_once "./fws/cmd/cli/migrate.php";

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
        echo "Exemplo de comando: php fws.php init appName dbName \n\n";
    }
}elseif($command == 'cmd'){
    cmd($availableCommands);
}elseif($command == 'migrate'){
    echo "\n";
    migrate();
    echo "\n\n";
}else{
    echo "\nComando não encontrado. \n";
    echo "Utilize o comando 'cmd' para verificar os comandos disponíveis. \n";
    echo "Em caso de dúvidas, confira a documentação em https://github.com/gustavohvs-dev/fws \n\n";
}