<?php

function run($scriptName){
    $impetusJson = file_get_contents('impetus.json');
    $impetusJsonDecoded = json_decode($impetusJson);
    $founded = false;
    foreach($impetusJsonDecoded->scripts as $key => $value){
        if($key == $scriptName){
            $founded = true;
            $output = shell_exec($value);
            print_r($output);
        }
    }
    if($founded != true){
        echo "\033[1;31m" . "(500 Internal Server Error) Comando inválido \n" . "\033[0m";
    }
}