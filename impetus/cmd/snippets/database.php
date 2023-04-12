<?php

function databaseSnippet(){

$snippet = 
'<?php

class Database
{
    public function usersTable()
    {
        $table = "(
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(256) NOT NULL UNIQUE,
            password VARCHAR(256) NOT NULL,
            permission VARCHAR(256) NOT NULL,
            createdAt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
            updatedAt DATETIME
            )";
        return $table;
    }

    /**
     * Preenche tabelas no banco de dados com dados padrão
     */
    /*public function populate()
    {

        require "app/database/database.php";

        // Cria um usuário padrão para autenticação no webservice
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
            echo "\n(200 OK) Usuário admin criado com sucesso.";
        }else{
            $error = $stmt->errorInfo();
            $error = $error[2];
            echo "\n" . $error;
            echo "\n(500 Internal Server Error) Falha ao criar usuário admin";
        }
        // Fim

        return "\n";
    }*/

}
';

return $snippet;

}