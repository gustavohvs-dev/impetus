<?php

function initBackend()
{
    echo "Iniciando instalação... \n";
    echo "Copiando arquivos... \n";
    recurseCopy("./impetusSDK/cmd/snippets/storage", "./build/storage");
    recurseCopy("./impetusSDK/cmd/snippets/backend", "./build/backend");
    echo "\033[1;32m"."Instação do backend concluída. \n". "\033[0m";
}

function initFrontend()
{
    echo "Iniciando instalação... \n";
    echo "Copiando arquivos... \n";
    recurseCopy("./impetusSDK/cmd/snippets/frontend", "./build/frontend");
    echo "\033[1;32m"."Instação do frontend concluída. \n". "\033[0m";
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