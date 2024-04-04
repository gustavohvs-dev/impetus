<?php

function tables($path = "Migrate00000000_0.php"){
    require_once "build/backend/app/database/migrations/".$path;
    require "build/backend/app/config/config.php";
    $className = explode(".", $path);
    $databaseClass = new $className[0];
    $databaseMethods = get_class_methods($databaseClass);
    foreach($databaseMethods as $method){
        if(substr($method, -5) == "Table"){
            $tableName = substr($method, 0, -5);
            $tableData = $databaseClass->$method();
            $table = "CREATE TABLE ".$tableName." ".$tableData;
            $stmt = $conn->prepare($table);
            if($stmt->execute()){
                echo "\033[1;32m" . $className[0] . ": " . "Table '".$tableName."' created successfuly\n" . "\033[0m";
            }else{
                $error = $stmt->errorInfo();
                if($error[1] == 1050 || $error[1] == 1062 || $error[1] == 1060){
                    echo "\033[1;33m" . $className[0]. ": " . $error[2] .  " on " . "CREATE TABLE " .$tableName . "\n" . "\033[0m";
                }else{
                    echo "\033[1;31m" . $className[0]. ": " . $error[2] .  " on " . "CREATE TABLE " .$tableName . "\n" . "\033[0m";
                }
            }
        }
    }
    unset($databaseClass);
}

function data($path = "Migrate00000000_0.php"){
    require_once "build/backend/app/database/migrations/".$path;
    require "build/backend/app/config/config.php";
    $className = explode(".", $path);
    $databaseClass = new $className[0];
    $databaseMethods = get_class_methods($databaseClass);
    foreach($databaseMethods as $method){
        if(substr($method, -4) == "Data"){
            $dataName = substr($method, 0, -4);
            $data = $databaseClass->$method();
            $stmt = $conn->prepare($data);
            if($stmt->execute()){
                echo "\033[1;32m" . $className[0] . ": " .$dataName." created successfuly\n" . "\033[0m";
            }else{
                $error = $stmt->errorInfo();
                if($error[1] == 1050 || $error[1] == 1062 || $error[1] == 1060){
                    echo "\033[1;33m" . $className[0]. ": " . $error[2] .  " on " . $data . "\n" . "\033[0m";
                }else{
                    echo "\033[1;31m" . $className[0]. ": " . $error[2] .  " on " . $data . "\n" . "\033[0m";
                }
            }
        }
    }
    unset($databaseClass);
}

function views($path = "Migrate00000000_0.php"){
    require_once "build/backend/app/database/migrations/".$path;
    require "build/backend/app/config/config.php";
    $className = explode(".", $path);
    $databaseClass = new $className[0];
    $databaseMethods = get_class_methods($databaseClass);
    foreach($databaseMethods as $method){
        if(substr($method, -4) == "View"){
            $viewName = substr($method, 0, -4);
            $viewData = $databaseClass->$method();
            $view = "CREATE VIEW vw_".$viewName." AS SELECT " . $viewData;
            $stmt = $conn->prepare($view);
            if($stmt->execute()){
                echo "\033[1;32m" . $className[0] . ": " . "View '".$viewName."' created successfuly\n" . "\033[0m";
            }else{
                $error = $stmt->errorInfo();
                if($error[1] == 1050 || $error[1] == 1062 || $error[1] == 1060){
                    echo "\033[1;33m" . $className[0]. ": " . $error[2] .  " on " . "CREATE VIEW vw_" .$viewName . "\n" . "\033[0m";
                }else{
                    echo "\033[1;31m" . $className[0]. ": " . $error[2] .  " on " . "CREATE VIEW vw_" .$viewName . "\n" . "\033[0m";
                }
            }
        }
    }
    unset($databaseClass);
}

function update($path = "Migrate00000000_0.php"){
    require_once "build/backend/app/database/migrations/".$path;
    require "build/backend/app/config/config.php";
    $className = explode(".", $path);
    $databaseClass = new $className[0];
    $databaseMethods = get_class_methods($databaseClass);
    foreach($databaseMethods as $method){
        if(substr($method, -6) == "Update"){
            $dataName = substr($method, 0, -6);
            $data = $databaseClass->$method();
            $stmt = $conn->prepare($data);
            if($stmt->execute()){
                echo "\033[1;32m" . $className[0] . ": " .$dataName." updated successfuly\n" . "\033[0m";
            }else{
                $error = $stmt->errorInfo();
                if($error[1] == 1050 || $error[1] == 1062 || $error[1] == 1060){
                    echo "\033[1;33m" . $className[0]. ": " . $error[2] .  " on " . $data . "\n" . "\033[0m";
                }else{
                    echo "\033[1;31m" . $className[0]. ": " . $error[2] .  " on " . $data . "\n" . "\033[0m";
                }
            }
        }
    }
    unset($databaseClass);
}

function migrate(){
    $path = "build/backend/app/database/migrations/";
    $diretorio = dir($path);
    while($arquivo = $diretorio -> read()){
        if($arquivo != "." && $arquivo != ".."){
            tables($arquivo);
            update($arquivo);
            views($arquivo);
            data($arquivo);
        }
    }
}

function migrateCreateExamples(){

    $migrationDate = "Migrate" . date("Y") . date("m") . date("d");
    $migrationDayNumber = 0;
    $migrationFound = true;
    
    while($migrationFound){
        $migrationName =  $migrationDate . "_" . $migrationDayNumber;
        if(!is_file("build/backend/app/database/migrations/$migrationName.php")){
            $migrationFound = false;
        }else{
            $migrationDayNumber++;
        }
    }

$snippet= '<?php

class '.$migrationName.'
{

    /**
     * Exemplo de criação de tabela
     * Utilize o sufixo Table para indicar que é uma tabela.
     * Os campos "id", "createdAt", "updatedAt" são padronizados não remova esses campos.
     */
    public function exampleUsersTable()
    {
        $table = "(
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            status VARCHAR(256) NOT NULL DEFAULT \'ACTIVE\',
            name VARCHAR(1024) NOT NULL,
            email VARCHAR(1024) NOT NULL,
            username VARCHAR(256) NOT NULL UNIQUE,
            password VARCHAR(256) NOT NULL,
            permission ENUM(\'admin\',\'user\') NOT NULL,
            isConfirmedEmail BOOLEAN DEFAULT 0,
            companyId INT(30) NOT NULL,
            createdAt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
            updatedAt DATETIME
            )";
        return $table;
    }

    /**
     * Exemplo de criação de view
     * Utilize o sufixo View para indicar a criação de uma view.
     */
    public function examplesUsersView()
    {
        $view = "
            USERS.id,
            USERS.status,
            USERS.name,
            USERS.email,
            USERS.username,
            USERS.permission,
            USERS.isConfirmedEmail,
            USERS.companyId,
            COMPANIES.corporateName,
            USERS.createdAt,
            USERS.updatedAt
        FROM users USERS
        LEFT JOIN companies COMPANIES ON USERS.companyId = COMPANIES.id;
        ";
        return $view;
    }

    /**
     * Exemplo de inserção de dados
     * Utilize o sufixo Data para indicar a criação de dados.
     */
    public function impetusCompaniesData()
    {
        $data = "INSERT INTO companies (corporateName, name, document) VALUES(\'IMPETUS FRAMEWORK\', \'IMPETUS\', \'11.111.111/0001-11\')";
        return $data;
    }

    /**
     * Exemplo de atualização de tabela
     * Utilize o Update para indicar a atualização do banco de dados, seja na estrutura ou nos dados
     */
    public function addMethod2ColumnUpdate()
    {
        $data = "ALTER TABLE log ADD method2 VARCHAR(128) DEFAULT \'YES\'";
        return $data;
    }

    /**
     * Outro exemplo de atualização de tabela
     * Utilize o Update para indicar a atualização do banco de dados, seja na estrutura ou nos dados
     */
    public function setMethod2Update()
    {
        $data = "UPDATE `log` SET `method2` = \'NAO\'";
        return $data;
    }

}
';

    $arquivo = fopen("build/backend/app/database/migrations/$migrationName.php", 'w');
    if($arquivo == false){
        echo "\033[1;31m"."\n(500 Internal Server Error) Falha ao criar migration example (".$migrationName.")" . "\033[0m";
        return;
    }else{
        $escrever = fwrite($arquivo, $snippet);
        if($escrever == false){
            echo "\033[1;31m"."\n(500 Internal Server Error) Falha ao preencher migration example (".$migrationName.")" . "\033[0m";
            return;
        }else{
            echo "\033[1;32m"."\n(200 OK) Migration example '".$migrationName."' criada com sucesso." . "\033[0m";
        }
    } 

}