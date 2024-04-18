<?php

function initWelcome()
{
    echo "\033[1;35m" . "\nSeja bem-vindo ao ImpetusPHP, aproveite os recursos do framework para melhorar a produtividade de desenvolvimento. Tenha um bom dia de trabalho. \n\n" . "\033[0m";
}

function initBackend()
{
    echo "Iniciando instalação... \n";
    echo "Copiando arquivos, aguarde até o fim da instalação... \n";
    recurseCopy("./impetusSDK/projects/storage", "./build/storage");
    recurseCopy("./impetusSDK/projects/backend", "./build/backend");
    echo "\033[1;32m"."Instação do backend concluída. \n\n". "\033[0m";
}

function initFrontend()
{
    echo "Iniciando instalação... \n";
    echo "Copiando arquivos, aguarde até o fim da instalação... \n";
    recurseCopy("./impetusSDK/projects/frontend", "./build/frontend");
    echo "\033[1;32m"."Instação do frontend concluída. \n\n". "\033[0m";
}

function recurseCopy(string $sourceDirectory,string $destinationDirectory,string $childFolder = ''): void 
{
    $directory = opendir($sourceDirectory);

    if (is_dir("./build") === false) {
        mkdir("./build");
    }

    if (is_dir($destinationDirectory) === false) {
        mkdir($destinationDirectory);
    }

    if ($childFolder !== '') {
        if (is_dir("$destinationDirectory/$childFolder") === false) {
            mkdir("$destinationDirectory/$childFolder");
        }

        while (($file = readdir($directory)) !== false) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            if (is_dir("$sourceDirectory/$file") === true) {
                recurseCopy("$sourceDirectory/$file", "$destinationDirectory/$childFolder/$file");
            } else {
                copy("$sourceDirectory/$file", "$destinationDirectory/$childFolder/$file");
            }
        }

        closedir($directory);

        return;
    }

    while (($file = readdir($directory)) !== false) {
        if ($file === '.' || $file === '..') {
            continue;
        }

        if (is_dir("$sourceDirectory/$file") === true) {
            recurseCopy("$sourceDirectory/$file", "$destinationDirectory/$file");
        }
        else {
            copy("$sourceDirectory/$file", "$destinationDirectory/$file");
        }
    }

    closedir($directory);
}