<?php

/**
 * Interpretador CLI para execução de comandos
 */

$availableCommands = [
    ["init", "Cria a estrutura básica da aplicação"],
    ["migrate", "Realiza a criação do banco de dados"],
    ["script ScriptName", "Executa algum script pré-definido"],
    ["empty-model", "Cria uma estrutura de model e controller vazia"],
    ["model TableName", "Cria uma estrutura de model e controller com base em uma tabela"]
];

$command = $argv[1];

if($command == 'init'){
    init();
}elseif($command == 'cli'){
    cli($availableCommands);
}else{
    echo "\nComando não encontrado. \n";
    echo "Utilize o comando 'cli' para verificar os comandos disponíveis. \n";
    echo "Em caso de dúvidas, confira a documentação em https://github.com/gustavohvs-dev/fws \n\n";
}

function init(){
    echo "\nCriando estrutura básica de pastas...\n";
    echo "Comando executado com sucesso! \n\n";
}

function cli($availableCommands){
    echo "\n ### Comandos disponíveis \n";
    foreach($availableCommands as $availableCommand){
        echo $availableCommand[0] . " - " . $availableCommand[1] . "\n";
    }
    echo "\n";
}