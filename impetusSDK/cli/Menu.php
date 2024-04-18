<?php

function menu($name)
{

    if(!file_exists("build/frontend/app/components/menu/menu.json")){
        echo "\n(404 Not found) Arquivo de menu não encontrado";
        return null;
    }

    $menuJson = file_get_contents('build/frontend/app/components/menu/menu.json');
    $menu = json_decode($menuJson);

    $isHeaderPaginasExist = false;

    foreach($menu as $header){
        if($header->title == "Páginas"){
            $isHeaderPaginasExist = true;
        }
    }

    if(!$isHeaderPaginasExist){
        //Adiciona header "Páginas"
        array_push($menu, (object) [
            "title" => "Páginas",
            "permissions" => ["admin","user"],
            "children" => [
                (object) [
                    "name" => ucfirst($name),
                    "icon" => "box",
                    "pathname" => $name,
                    "permissions" => ["admin","user"],
                ]
            ]
        ]);
    }else{
        //Adiciona uma nova página em Páginas
        foreach($menu as $header){
            if($header->title == "Páginas"){
                array_push($header->children,
                    (object) [
                        "name" => ucfirst($name),
                        "icon" => "box",
                        "pathname" => $name,
                        "permissions" => ["admin","user"],
                    ]
                );
            }
        }
    }

    $newMenu = json_encode($menu, JSON_PRETTY_PRINT);   

    $arquivo = fopen("build/frontend/app/components/menu/menu.json", 'w');
    if($arquivo == false){
        echo "\033[1;31m"."\nFalha ao reescrever arquivo menu.json\n". "\033[0m";
        return false;
    }else{
        $escrever = fwrite($arquivo, $newMenu);
        if($escrever == false){
            echo "\033[1;31m"."\nFalha ao preencher arquivo menu.json\n". "\033[0m";
            return false;
        }else{
            echo "\033[1;32m"."\nMenu reescrito\n\n". "\033[0m";
        }
    } 

}