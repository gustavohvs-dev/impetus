<?php

function init($argv){

    $qntError = 0;

    /**
     * Criando diretórios
     */
    echo "\nCriando diretórios... \n";
    if(is_dir("src")){
        $qntError++;
        echo "Pasta 'src' já existente. \n";
    }else{
        mkdir("src", 0751);
        echo "Pasta 'src' criada. \n";
        if(!is_dir("src/app")){
            mkdir("src/app", 0751);
            echo "Pasta 'app' criada. \n";
        }else{
            echo "Pasta 'app' já existente. \n";
        }
    
        if(!is_dir("src/app")){
            echo "(500 Internal Server Error) Falha ao criar pasta app. \n";
            $qntError++;
            return null;
        }else{
            if(!is_dir("src/app/routes")){
                mkdir("src/app/routes", 0751);
                echo "Pasta 'routes' criada. \n";
            }else{
                echo "Pasta 'routes' já existente. \n";
            }
    
            if(!is_dir("src/app/models")){
                mkdir("src/app/models", 0751);
                echo "Pasta 'models' criada. \n";
            }else{
                echo "Pasta 'models' já existente. \n";
            }
    
            if(!is_dir("src/app/controllers")){
                mkdir("src/app/controllers", 0751);
                echo "Pasta 'controllers' criada. \n";
            }else{
                echo "Pasta 'controllers' já existente. \n";
            }
    
            if(!is_dir("src/app/config")){
                mkdir("src/app/config", 0751);
                echo "Pasta 'config' criada. \n";
            }else{
                echo "Pasta 'config' já existente. \n";
            }
    
            if(!is_dir("src/app/database")){
                mkdir("src/app/database", 0751);
                echo "Pasta 'database' criada. \n";
            }else{
                echo "Pasta 'database' já existente. \n";
            }
    
            if(!is_dir("src/app/middlewares")){
                mkdir("src/app/middlewares", 0751);
                echo "Pasta 'middlewares' criada. \n";
            }else{
                echo "Pasta 'middlewares' já existente. \n";
            }
        }
    
        if(!is_dir("src/app/models")){
            echo "(500 Internal Server Error) Falha ao criar pasta models. \n";
            $qntError++;
            return null;
        }else{
            if(!is_dir("src/app/models/impetus")){
                mkdir("src/app/models/impetus", 0751);
                echo "Pasta 'impetus' criada. \n";
            }else{
                echo "Pasta 'impetus' já existente. \n";
            }
        }
    
        if(!is_dir("src/app/controllers")){
            echo "(500 Internal Server Error) Falha ao criar pasta controllers. \n";
            $qntError++;
            return null;
        }else{
            if(!is_dir("src/app/controllers/login")){
                mkdir("src/app/controllers/login", 0751);
                echo "Pasta 'login' criada. \n";
            }else{
                echo "Pasta 'login' já existente. \n";
            }
        }
    
        /**
         * Criando arquivos básicos
         */
        echo "Criando arquivos... \n";
    
        if(!is_dir("src/app/middlewares")){
            echo "(500 Internal Server Error) Falha ao encontrar pasta de middlewares. \n";
            $qntError++;
            return null;
        }else{
            $arquivo = fopen("src/app/middlewares/Auth.php", 'w');
            if($arquivo == false){
                echo "(500 Internal Server Error) Falha ao criar arquivo de Auth. \n";
                $qntError++;
                return null;
            }else{
                require "./impetus/cmd/snippets/auth.php";
                $texto = authSnippet();
                $escrever = fwrite($arquivo, $texto);
                if($escrever == false){
                    echo "(500 Internal Server Error) Falha ao preencher middleware 'Auth'. \n";
                    $qntError++;
                    return null;
                }else{
                    echo "Arquivo 'Auth' criado com sucesso. \n";
                }
            } 
        }
    
        if(!is_dir("src/app/config")){
            echo "(500 Internal Server Error) Falha ao encontrar pasta de configuração. \n";
            $qntError++;
            return null;
        }else{
            $arquivo = fopen("src/app/config/config.php", 'w');
            if($arquivo == false){
                echo "(500 Internal Server Error) Falha ao criar arquivo de configuração. \n";
                $qntError++;
                return null;
            }else{
                require "./impetus/cmd/snippets/config.php";
                if(isset($argv[3])){
                    $dbName = $argv[3];
                }else{
                    $dbName = 'impetus';
                }
                $texto = configSnippet($argv[2], $dbName);
                $escrever = fwrite($arquivo, $texto);
                if($escrever == false){
                    echo "(500 Internal Server Error) Falha ao preencher arquivo de configuração. \n";
                    $qntError++;
                    return null;
                }else{
                    echo "Arquivo 'config' criado com sucesso. \n";
                }
            } 
    
            $arquivo = fopen("src/server.php", 'w');
            if($arquivo == false){
                echo "(500 Internal Server Error) Falha ao criar arquivo de servidor. \n";
                $qntError++;
                return null;
            }else{
                require "./impetus/cmd/snippets/server.php";
                $texto = serverSnippet($argv[2]);
                $escrever = fwrite($arquivo, $texto);
                if($escrever == false){
                    echo "(500 Internal Server Error) Falha ao preencher arquivo de servidor. \n";
                    $qntError++;
                    return null;
                }else{
                    echo "Arquivo 'server' criado com sucesso. \n";
                }
            } 
        }
    
        if(!is_dir("src/app/database")){
            echo "(500 Internal Server Error) Falha ao encontrar pasta de banco de dados. \n";
            $qntError++;
            return null;
        }else{
            $arquivo = fopen("src/app/database/database.php", 'w');
            if($arquivo == false){
                echo "(500 Internal Server Error) Falha ao criar arquivo de banco de dados. \n";
                $qntError++;
                return null;
            }else{
                require "./impetus/cmd/snippets/database.php";
                $texto = databaseSnippet();
                $escrever = fwrite($arquivo, $texto);
                if($escrever == false){
                    echo "(500 Internal Server Error) Falha ao preencher arquivo de banco de dados. \n";
                    $qntError++;
                    return null;
                }else{
                    echo "Arquivo 'database' criado com sucesso. \n";
                }
            } 
        }
    
        $arquivo = fopen("src/.htaccess", 'w');
        if($arquivo == false){
            echo "(500 Internal Server Error) Falha ao criar arquivo htacess. \n";
            $qntError++;
            return null;
        }else{
            require "./impetus/cmd/snippets/htaccess.php";
            $texto = htaccessSnippet();
            $escrever = fwrite($arquivo, $texto);
            if($escrever == false){
                echo "(500 Internal Server Error) Falha ao preencher arquivo htaccess. \n";
                $qntError++;
                return null;
            }else{
                echo "Arquivo 'htaccess' criado com sucesso. \n";
            }
        } 
    
        $arquivo = fopen("src/index.php", 'w');
        if($arquivo == false){
            echo "(500 Internal Server Error) Falha ao criar arquivo index. \n";
            $qntError++;
            return null;
        }else{
            require "./impetus/cmd/snippets/index.php";
            $texto = indexSnippet();
            $escrever = fwrite($arquivo, $texto);
            if($escrever == false){
                echo "(500 Internal Server Error) Falha ao preencher arquivo index. \n";
                $qntError++;
                return null;
            }else{
                echo "Arquivo 'index' criado com sucesso. \n";
            }
        } 
    
        if(!is_dir("src/app/routes")){
            echo "(500 Internal Server Error) Falha ao encontrar pasta raiz. \n";
            $qntError++;
            return null;
        }else{
            $arquivo = fopen("src/app/routes/routes.php", 'w');
            if($arquivo == false){
                echo "(500 Internal Server Error) Falha ao criar arquivo de rotas. \n";
                $qntError++;
                return null;
            }else{
                require "./impetus/cmd/snippets/routes.php";
                $texto = routesSnippet();
                $escrever = fwrite($arquivo, $texto);
                if($escrever == false){
                    echo "(500 Internal Server Error) Falha ao preencher arquivo de rotas. \n";
                    $qntError++;
                    return null;
                }else{
                    echo "Arquivo 'routes' criado com sucesso. \n";
                }
            } 
        }
    
        if(!is_dir("src/app/controllers/login")){
            echo "(500 Internal Server Error) Falha ao encontrar pasta raiz. \n";
            $qntError++;
            return null;
        }else{
            $arquivo = fopen("src/app/controllers/login/login.php", 'w');
            if($arquivo == false){
                echo "(500 Internal Server Error) Falha ao criar arquivo de login. \n";
                $qntError++;
                return null;
            }else{
                require "./impetus/cmd/snippets/login.php";
                $texto = loginSnippet();
                $escrever = fwrite($arquivo, $texto);
                if($escrever == false){
                    echo "(500 Internal Server Error) Falha ao preencher arquivo de login. \n";
                    $qntError++;
                    return null;
                }else{
                    echo "Arquivo 'login' criado com sucesso. \n";
                }
            } 
        }
    
        /** 
         * Copiando arquivos utilitários
         */
        if(!copy("impetus/utils/ImpetusJWT.php", "src/app/models/impetus/ImpetusJWT.php")){
            echo "(500 Internal Server Error) Falha ao copiar arquivo 'ImpetusJWT'. \n";
            $qntError++;
            return null;
        }else{
            echo "Arquivo utilitário 'ImpetusJWT' criado com sucesso. \n";
        }
    
        if(!copy("impetus/utils/ImpetusUtils.php", "src/app/models/impetus/ImpetusUtils.php")){
            echo "(500 Internal Server Error) Falha ao copiar arquivo 'ImpetusUtils'. \n";
            $qntError++;
            return null;
        }else{
            echo "Arquivo utilitário 'ImpetusUtils' criado com sucesso. \n";
        }
    
        if(!copy("impetus/utils/ImpetusMaths.php", "src/app/models/impetus/ImpetusMaths.php")){
            echo "(500 Internal Server Error) Falha ao copiar arquivo 'ImpetusMaths'. \n";
            $qntError++;
            return null;
        }else{
            echo "Arquivo utilitário 'ImpetusMaths' criado com sucesso. \n";
        }
    }

    if($qntError == 0){
        echo "\nDica: Para seguir com a configuração, siga os passos abaixo: \n";
        echo "1 - Vá em 'src/app/config.php' e abra o arquivo de configuração. \n";
        echo "2 - Preencha os dados necessários para configuração do banco de dados e crie o database. \n";
        echo "3 - Execute o comando 'php impetus.php migrate --all' para criar a tabela de usuários e o primeiro usuário do webservice. \n";
        echo "4 - Pronto! O web service está pronto a ser utilizado. \n";
        echo "\n" . "\033[1;32m" . "(200 OK) Projeto configurado com sucesso. \n" . "\033[0m";
    }else{
        echo "\033[1;31m" . "Verifique os erros, em caso de problemas, verifique a documentação em https://github.com/gustavohvs-dev/impetus. \n" . "\033[0m";
    }

    echo "\n";
    
}