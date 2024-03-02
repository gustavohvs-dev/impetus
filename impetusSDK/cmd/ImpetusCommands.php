<?php

function available($availableCommands)
{
    echo "\n### Available commands \n";
    foreach($availableCommands as $availableCommand){
        echo "\033[1;36m" . $availableCommand[0] . "\033[0m";
        echo " - " . $availableCommand[1] . "\n";
        echo "\033[1;30m" . "Example: " . $availableCommand[2] . "\033[0m" . "\n";
        echo "\033[1;30m" . "Argumentos disponíveis: " . $availableCommand[3] . "\033[0m" . "\n";
    }
    echo "\n";
}


