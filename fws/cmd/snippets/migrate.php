<?php

function migrateSnippet(){

$snippet = 
'<?php

class migrate
{
    /**
     * Cria tabelas no banco de dados
     */
    public function migrate(){

        require_once "app/database/database.php";

        /**
         * Cria tabela de usuários
         */
        $table = "CREATE TABLE users (
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(256) NOT NULL UNIQUE,
            password VARCHAR(256) NOT NULL,
            permission VARCHAR(256) NOT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
            updated_at DATETIME,
            )";
        $stmt = $conn->prepare($table);
        $query = $stmt->execute();

        var_dump($query);

        return "(200 OK) Banco de dados estruturado com sucesso";

    }

    /**
     * Preenche tabelas no banco de dados com dados padrão
     */
    public function populate(){

    }
}
';

return $snippet;

}