<?php

function init($argv){

    $qntError = 0;

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
        echo "(500 Internal Server Error) Falha ao criar pasta app. \n";
        $qntError++;
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

    if(!is_dir("app/models")){
        echo "(500 Internal Server Error) Falha ao criar pasta models. \n";
        $qntError++;
        return null;
    }else{
        if(!is_dir("app/models/impetus")){
            mkdir("app/models/impetus", 0751);
            echo "Pasta 'impetus' criada. \n";
        }else{
            echo "Pasta 'impetus' já existente. \n";
        }
    }

    if(!is_dir("app/controllers")){
        echo "(500 Internal Server Error) Falha ao criar pasta controllers. \n";
        $qntError++;
        return null;
    }else{
        if(!is_dir("app/controllers/login")){
            mkdir("app/controllers/login", 0751);
            echo "Pasta 'login' criada. \n";
        }else{
            echo "Pasta 'login' já existente. \n";
        }
    }

    if(!is_dir("app/controllers")){
        echo "(500 Internal Server Error) Falha ao criar pasta controllers. \n";
        $qntError++;
        return null;
    }else{
        if(!is_dir("app/controllers/test")){
            mkdir("app/controllers/test", 0751);
            echo "Pasta 'test' criada. \n";
        }else{
            echo "Pasta 'test' já existente. \n";
        }
    }

    /**
     * Criando arquivos básicos
     */
    echo "Criando arquivos... \n";

    if(!is_dir("app/middlewares")){
        echo "(500 Internal Server Error) Falha ao encontrar pasta de middlewares. \n";
        $qntError++;
        return null;
    }else{
        $arquivo = fopen("app/middlewares/Auth.php", 'w');
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

    if(!is_dir("app/config")){
        echo "(500 Internal Server Error) Falha ao encontrar pasta de configuração. \n";
        $qntError++;
        return null;
    }else{
        $arquivo = fopen("app/config/config.php", 'w');
        if($arquivo == false){
            echo "(500 Internal Server Error) Falha ao criar arquivo de configuração. \n";
            $qntError++;
            return null;
        }else{
            require "./impetus/cmd/snippets/config.php";
            $texto = configSnippet($argv[2], $argv[3]);
            $escrever = fwrite($arquivo, $texto);
            if($escrever == false){
                echo "(500 Internal Server Error) Falha ao preencher arquivo de configuração. \n";
                $qntError++;
                return null;
            }else{
                echo "Arquivo 'config' criado com sucesso. \n";
            }
        } 
    }

    if(!is_dir("app/database")){
        echo "(500 Internal Server Error) Falha ao encontrar pasta de banco de dados. \n";
        $qntError++;
        return null;
    }else{
        $arquivo = fopen("app/database/database.php", 'w');
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

        $arquivo = fopen("app/database/migrate.php", 'w');
        if($arquivo == false){
            echo "(500 Internal Server Error) Falha ao criar arquivo migrate. \n";
            $qntError++;
            return null;
        }else{
            require "./impetus/cmd/snippets/migrate.php";
            $texto = migrateSnippet();
            $escrever = fwrite($arquivo, $texto);
            if($escrever == false){
                echo "(500 Internal Server Error) Falha ao preencher arquivo migrate. \n";
                $qntError++;
                return null;
            }else{
                echo "Arquivo 'migrate' criado com sucesso. \n";
            }
        } 
    }

    $arquivo = fopen(".htaccess", 'w');
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

    $arquivo = fopen("index.php", 'w');
    if($arquivo == false){
        echo "(500 Internal Server Error) Falha ao criar arquivo index. \n";
        $qntError++;
        return null;
    }else{
        require "./impetus/cmd/snippets/index.php";
        $texto = indexSnippet($argv[2]);
        $escrever = fwrite($arquivo, $texto);
        if($escrever == false){
            echo "(500 Internal Server Error) Falha ao preencher arquivo index. \n";
            $qntError++;
            return null;
        }else{
            echo "Arquivo 'index' criado com sucesso. \n";
        }
    } 

    if(!is_dir("app/routes")){
        echo "(500 Internal Server Error) Falha ao encontrar pasta raiz. \n";
        $qntError++;
        return null;
    }else{
        $arquivo = fopen("app/routes/routes.php", 'w');
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

    if(!is_dir("app/controllers/login")){
        echo "(500 Internal Server Error) Falha ao encontrar pasta raiz. \n";
        $qntError++;
        return null;
    }else{
        $arquivo = fopen("app/controllers/login/login.php", 'w');
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

    if(!is_dir("app/controllers/test")){
        echo "(500 Internal Server Error) Falha ao encontrar pasta raiz. \n";
        $qntError++;
        return null;
    }else{
        $arquivo = fopen("app/controllers/test/test.php", 'w');
        if($arquivo == false){
            echo "(500 Internal Server Error) Falha ao criar arquivo 'test'. \n";
            $qntError++;
            return null;
        }else{
            require "./impetus/cmd/snippets/test.php";
            $texto = testSnippet();
            $escrever = fwrite($arquivo, $texto);
            if($escrever == false){
                echo "(500 Internal Server Error) Falha ao preencher arquivo 'test'. \n";
                $qntError++;
                return null;
            }else{
                echo "Arquivo 'test' criado com sucesso. \n";
            }
        } 
    }

    /** 
     * Copiando arquivos utilitários
     */
    if(!copy("impetus/utils/ImpetusJWT.php", "app/models/impetus/ImpetusJWT.php")){
        echo "(500 Internal Server Error) Falha ao copiar arquivo 'ImpetusJWT'. \n";
        $qntError++;
        return null;
    }else{
        echo "Arquivo utilitário 'ImpetusJWT' criado com sucesso. \n";
    }

    if(!copy("impetus/utils/ImpetusUtils.php", "app/models/impetus/ImpetusUtils.php")){
        echo "(500 Internal Server Error) Falha ao copiar arquivo 'ImpetusUtils'. \n";
        $qntError++;
        return null;
    }else{
        echo "Arquivo utilitário 'ImpetusUtils' criado com sucesso. \n";
    }

    if($qntError == 0){
        echo "(200 OK) Projeto configurado com sucesso. \n";
        echo "\nDica: Para seguir com a configuração, siga os passos abaixo: \n";
        echo "1 - Crie o banco de dados, com o nome '".$argv[3]."'. \n";
        echo "2 - Vá em 'app/config' e abra o arquivo de configuração. \n";
        echo "3 - Preencha os dados necessários para configuração do banco de dados. \n";
        echo "4 - Execute o comando 'php impetus.php migrate all' para criar a tabela de usuários e o primeiro usuário do webservice. \n";
        echo "5 - Pronto! O web service está pronto a ser utilizado. \n";
    }else{
        echo "Verifique os erros, em caso de problemas, verifique a documentação em https://github.com/gustavohvs-dev/impetus. \n";
    }

    echo "\n";
    
}