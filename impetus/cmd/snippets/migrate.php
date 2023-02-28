<?php

function migrateSnippet(){

$snippet = 
'<?php

class Migrate
{
    /**
     * Cria tabelas no banco de dados
     */
    public function tables()
    {

        require "app/database/database.php";

        /**
         * Cria tabela de usuários
         */
        $table = "CREATE TABLE users (
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(256) NOT NULL UNIQUE,
            password VARCHAR(256) NOT NULL,
            permission VARCHAR(256) NOT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
            updated_at DATETIME
            )";
        $stmt = $conn->prepare($table);
        if($stmt->execute()){
            echo "Tabela users criada com sucesso.\n";
        }else{
            $error = $stmt->errorInfo();
            $error = $error[2];
            echo $error . "\n";
            return "(500 Internal Server Error) Falha ao criar tabela users";
        }

        return "(200 OK) Banco de dados estruturado com sucesso";

    }

    /**
     * Preenche tabelas no banco de dados com dados padrão
     */
    public function populate()
    {

        require "app/database/database.php";

        /**
         * Cria um usuário padrão para autenticação no webservice
         */
        $data = [
            "username" => "admin",
            "password" => "admin",
            "permission" => "admin",
        ];
        $query = "INSERT INTO users (username, password, permission) 
        VALUES(:USERNAME, :PASS, :PERMISSION)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":USERNAME", $data["username"], PDO::PARAM_STR);
        $password = password_hash($data["password"], PASSWORD_BCRYPT);
        $stmt->bindParam(":PASS", $password, PDO::PARAM_STR);
        $stmt->bindParam(":PERMISSION", $data["permission"], PDO::PARAM_STR);
        if($stmt->execute()){
            echo "Usuário admin criado com sucesso.\n";
        }else{
            $error = $stmt->errorInfo();
            $error = $error[2];
            echo $error . "\n";
            return "(500 Internal Server Error) Falha ao criar usuário admin";
        }

        return "(200 OK) Banco de dados populado com sucesso";
    }

    /**
     * Cria views das tabelas
     */
    public function views()
    {

        require "app/database/database.php";
        
        /**
         * Cria view de log
         */
        /*$view = "CREATE VIEW view_historico AS SELECT 
            HISTORICO.id, 
            HISTORICO.status, 
            HISTORICO.usuarioId, 
            USUARIOS.nome, 
            HISTORICO.tipo, 
            HISTORICO.texto, 
            HISTORICO.createdAt, 
            HISTORICO.updatedAt 
            FROM historico HISTORICO 
            LEFT JOIN usuarios USUARIOS ON USUARIOS.id = HISTORICO.usuarioId 
            ;";
        $stmt = $conn->prepare($view);
        if($stmt->execute()){
            echo "View log criada com sucesso.\n";
        }else{
            $error = $stmt->errorInfo();
            $error = $error[2];
            echo $error . "\n";
            return "(500 Internal Server Error) Falha ao criar view log";
        }*/

        return "(200 OK) Views estruturadas com sucesso";

    }

}

';

return $snippet;

}