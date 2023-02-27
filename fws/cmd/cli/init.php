<?php

function init($argv){

    /**
     * Criando diretórios
     */
    echo "\nCriando diretórios... \n";
    if(!is_dir("app")){
        mkdir("app", 0751);
        echo "Pasta 'app' criada. \n";
    }else{
        echo "Pasta 'app' já existente. \n";
    }

    if(!is_dir("app")){
        echo "Error: Falha ao criar pasta app. \n";
        return null;
    }else{
        if(!is_dir("app/routes")){
            mkdir("app/routes", 0751);
            echo "Pasta 'routes' criada. \n";
        }else{
            echo "Pasta 'routes' já existente. \n";
        }

        if(!is_dir("app/models")){
            mkdir("app/models", 0751);
            echo "Pasta 'models' criada. \n";
        }else{
            echo "Pasta 'models' já existente. \n";
        }

        if(!is_dir("app/controllers")){
            mkdir("app/controllers", 0751);
            echo "Pasta 'controllers' criada. \n";
        }else{
            echo "Pasta 'controllers' já existente. \n";
        }

        if(!is_dir("app/config")){
            mkdir("app/config", 0751);
            echo "Pasta 'config' criada. \n";
        }else{
            echo "Pasta 'config' já existente. \n";
        }

        if(!is_dir("app/database")){
            mkdir("app/database", 0751);
            echo "Pasta 'database' criada. \n";
        }else{
            echo "Pasta 'database' já existente. \n";
        }

        if(!is_dir("app/middlewares")){
            mkdir("app/middlewares", 0751);
            echo "Pasta 'middlewares' criada. \n";
        }else{
            echo "Pasta 'middlewares' já existente. \n";
        }
    }

    /**
     * Criando arquivos básicos
     */
    echo "Criando arquivos... \n";

    if(!is_dir("app/config")){
        echo "Error: Falha ao encontrar pasta de configuração. \n";
        return null;
    }else{
        $arquivo = fopen("app/config/config.php", 'w');
        if($arquivo == false){
            echo "Error: Falha ao criar arquivo de configuração. \n";
            return null;
        }else{
            require "./fws/cmd/snippets/config.php";
            $texto = configSnippet($argv[2], $argv[3]);
            $escrever = fwrite($arquivo, $texto);
            if($escrever == false){
                echo "Error: Falha ao preencher arquivo de configuração. \n";
                return null;
            }else{
                echo "Arquivo 'config' criado com sucesso. \n";
            }
        } 
    }

    if(!is_dir("app/database")){
        echo "Error: Falha ao encontrar pasta de banco de dados. \n";
        return null;
    }else{
        $arquivo = fopen("app/database/database.php", 'w');
        if($arquivo == false){
            echo "Error: Falha ao criar arquivo de banco de dados. \n";
            return null;
        }else{
            require "./fws/cmd/snippets/database.php";
            $texto = databaseSnippet();
            $escrever = fwrite($arquivo, $texto);
            if($escrever == false){
                echo "Error: Falha ao preencher arquivo de banco de dados. \n";
                return null;
            }else{
                echo "Arquivo 'database' criado com sucesso. \n";
            }
        } 

        $arquivo = fopen("app/database/migrate.php", 'w');
        if($arquivo == false){
            echo "Error: Falha ao criar arquivo migrate. \n";
            return null;
        }else{
            require "./fws/cmd/snippets/migrate.php";
            $texto = migrateSnippet();
            $escrever = fwrite($arquivo, $texto);
            if($escrever == false){
                echo "Error: Falha ao preencher arquivo migrate. \n";
                return null;
            }else{
                echo "Arquivo 'migrate' criado com sucesso. \n";
            }
        } 
    }

    $arquivo = fopen(".htaccess", 'w');
    if($arquivo == false){
        echo "Error: Falha ao criar arquivo htacess. \n";
        return null;
    }else{
        require "./fws/cmd/snippets/htaccess.php";
        $texto = htaccessSnippet();
        $escrever = fwrite($arquivo, $texto);
        if($escrever == false){
            echo "Error: Falha ao preencher arquivo htaccess. \n";
            return null;
        }else{
            echo "Arquivo 'htaccess' criado com sucesso. \n";
        }
    } 

    $arquivo = fopen("index.php", 'w');
    if($arquivo == false){
        echo "Error: Falha ao criar arquivo index. \n";
        return null;
    }else{
        require "./fws/cmd/snippets/index.php";
        $texto = indexSnippet($argv[2]);
        $escrever = fwrite($arquivo, $texto);
        if($escrever == false){
            echo "Error: Falha ao preencher arquivo index. \n";
            return null;
        }else{
            echo "Arquivo 'index' criado com sucesso. \n";
        }
    } 

    if(!is_dir("app/routes")){
        echo "Error: Falha ao encontrar pasta raiz. \n";
        return null;
    }else{
        $arquivo = fopen("app/routes/routes.php", 'w');
        if($arquivo == false){
            echo "Error: Falha ao criar arquivo de rotas. \n";
            return null;
        }else{
            require "./fws/cmd/snippets/routes.php";
            $texto = routesSnippet();
            $escrever = fwrite($arquivo, $texto);
            if($escrever == false){
                echo "Error: Falha ao preencher arquivo de rotas. \n";
                return null;
            }else{
                echo "Arquivo 'routes' criado com sucesso. \n";
            }
        } 
    }

    echo "\n";
    
}