<?php

function available()
{

    $availableCommands = [
        ["init", "Realiza a configuração inicial da aplicação", "php impetus init --arg", "--backend, --frontend"],
        ["migrate", "Realiza a criação do banco de dados e migração de dados", "php impetus migrate --arg", "--all, --tables, --views, --data"],
        ["build", "Cria uma estrutura de model, controller e routes com base em uma tabela", "php impetus build --arg tablename", "--all, --model, --controller, --routes, --api, --view, --raw-view, --empty-view"],
    ];

    echo "\033[1;35m" . "\nSegue abaixo a lista de comandos disponíveis: \n\n";
    foreach($availableCommands as $availableCommand){
        echo "\033[1;36m" . $availableCommand[0] . "\033[0m";
        echo " - " . $availableCommand[1] . "\n";
        echo "\033[1;30m" . "Example: " . $availableCommand[2] . "\033[0m" . "\n";
        echo "\033[1;30m" . "Argumentos disponíveis: " . $availableCommand[3] . "\033[0m" . "\n";
    }
    echo "\n";
}


