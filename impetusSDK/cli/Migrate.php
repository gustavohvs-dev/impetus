<?php

function tables($path = "Migrate00000000_0.php"){
    require_once "build/ws/app/database/migrations/".$path;
    require "build/config.php";
    $className = explode(".", $path);
    $databaseClass = new $className[0];
    $databaseMethods = get_class_methods($databaseClass);
    foreach($databaseMethods as $method){
        if(substr($method, -5) == "Table"){
            $tableName = substr($method, 0, -5);
            $tableData = $databaseClass->$method();
            $table = $tableData;
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
    require_once "build/ws/app/database/migrations/".$path;
    require "build/config.php";
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
    require_once "build/ws/app/database/migrations/".$path;
    require "build/config.php";
    $className = explode(".", $path);
    $databaseClass = new $className[0];
    $databaseMethods = get_class_methods($databaseClass);
    foreach($databaseMethods as $method){
        if(substr($method, -4) == "View"){
            $viewName = substr($method, 0, -4);
            $viewData = $databaseClass->$method();
            $view = $viewData;
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
    require_once "build/ws/app/database/migrations/".$path;
    require "build/config.php";
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
    $path = "build/ws/app/database/migrations/";
    $diretorio = dir($path);
    echo "\n";
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
        if(!is_file("build/ws/app/database/migrations/$migrationName.php")){
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
     * Utilize o sufixo Table no nome da função para indicar que será feita a inclusão de uma nova tabela
     * Os campos "id", "createdAt", "updatedAt" são padronizados não remova esses campos.
     * Substitua os demais campos (field) para criar uma tabela que se enquadre na regra de negócio da sua aplicação.
     * Substitua o "tb_name" para o nome de tabela que desejar.
     */
    public function exampleNewTable()
    {
        $table = "CREATE TABLE tb_name(
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            status ENUM(\'ACTIVE\',\'INACTIVE\') NOT NULL DEFAULT \'ACTIVE\',
            field1 VARCHAR(1024) NOT NULL,
            field2 VARCHAR(256) NOT NULL UNIQUE,
            field3 ENUM(\'option1\',\'option2\') NOT NULL,
            field4 BOOLEAN DEFAULT 0,
            createdAt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
            updatedAt DATETIME
            )";
        return $table;
    }

    /**
     * Exemplo de criação de view
     * Utilize o sufixo View para indicar a criação de uma view.
     */
    public function examplesNewView()
    {
        $view = "CREATE VIEW vw_name AS SELECT 
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
    public function impetusNewData()
    {
        $data = "INSERT INTO tb_name (corporateName, name, document) VALUES(\'IMPETUS FRAMEWORK\', \'IMPETUS\', \'11.111.111/0001-11\')";
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

    $arquivo = fopen("build/ws/app/database/migrations/$migrationName.php", 'w');
    if($arquivo == false){
        echo "\033[1;31m"."\nFalha ao criar migration de exemplo (".$migrationName.")" . "\033[0m";
        return;
    }else{
        $escrever = fwrite($arquivo, $snippet);
        if($escrever == false){
            echo "\033[1;31m"."\nFalha ao preencher migration de exemplo (".$migrationName.")\n" . "\033[0m";
            return;
        }else{
            echo "\033[1;32m"."\nMigration de exemplo '".$migrationName."' criada com sucesso.\n" . "\033[0m";
        }
    } 

}