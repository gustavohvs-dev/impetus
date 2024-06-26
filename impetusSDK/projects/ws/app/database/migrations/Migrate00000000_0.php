<?php

class Migrate00000000_0
{
    public function usersTable()
    {
        $table = "CREATE TABLE users(
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            status VARCHAR(256) NOT NULL DEFAULT 'ACTIVE',
            name VARCHAR(1024) NOT NULL,
            email VARCHAR(1024) NOT NULL,
            username VARCHAR(256) NOT NULL UNIQUE,
            password VARCHAR(256) NOT NULL,
            permission ENUM('admin','user') NOT NULL,
            isConfirmedEmail BOOLEAN DEFAULT 0,
            pessoaId INT(30) NOT NULL,
            createdAt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
            updatedAt DATETIME
            )";
        return $table;
    }

    public function logTable()
    {
        $table = "CREATE TABLE log(
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            code VARCHAR(512) NOT NULL,
            tag VARCHAR(512) NOT NULL,
            endpoint TEXT(2000) NOT NULL,
            method VARCHAR(512) NOT NULL,
            request TEXT(8000) NOT NULL,
            response TEXT(8000) NOT NULL,
            description TEXT(8000) NOT NULL,
            userId INT NOT NULL,
            createdAt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
            FOREIGN KEY (userId) REFERENCES users(id)
            )";
        return $table;
    }

    public function usersView()
    {
        $view = "CREATE VIEW vw_users AS SELECT 
            USERS.id,
            USERS.status,
            USERS.name,
            USERS.email,
            USERS.username,
            USERS.permission,
            USERS.isConfirmedEmail,
            USERS.pessoaId,
            PESSOAS.nome AS pessoaNome,
            USERS.createdAt,
            USERS.updatedAt
        FROM users USERS
        LEFT JOIN pessoas PESSOAS ON USERS.pessoaId = PESSOAS.id;
        ";
        return $view;
    }

    public function logView()
    {
        $view = "CREATE VIEW vw_log AS SELECT 
            LOG.id, 
            LOG.userId, 
            LOG.code, 
            USER.username,
            LOG.tag, 
            LOG.description, 
            LOG.endpoint,
            LOG.method, 
            LOG.request, 
            LOG.response, 
            LOG.createdAt 
        FROM log LOG 
        LEFT JOIN users USER ON userId = USER.id;
        ";
        return $view;
    }

    public function pessoasTable()
    {
        $table = "CREATE TABLE pessoas(
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            status VARCHAR(256) NOT NULL DEFAULT 'ACTIVE',
            tipoDocumento ENUM('CPF','CNPJ') NOT NULL,
            documento VARCHAR(1024) NOT NULL,
            nome VARCHAR(1024) NOT NULL,
            nomeFantasia VARCHAR(1024),
            enderecoLogradouro VARCHAR(1024),
            enderecoNumero VARCHAR(1024),
            enderecoComplemento VARCHAR(1024),
            enderecoCidade VARCHAR(1024),
            enderecoBairro VARCHAR(1024),
            enderecoEstado ENUM('Acre','Alagoas','Amapá','Amazonas','Bahia','Ceará','Distrito Federal','Espirito Santo','Goiás','Maranhão','Mato Grosso do Sul','Mato Grosso','Minas Gerais','Pará','Paraíba','Paraná','Pernambuco','Piauí','Rio de Janeiro','Rio Grande do Norte','Rio Grande do Sul','Rondônia','Roraima','Santa Catarina','São Paulo','Sergipe','Tocantins'),
            enderecoPais VARCHAR(1024),
            enderecoCep VARCHAR(1024),
            createdAt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
            updatedAt DATETIME
            )";
        return $table;
    }

    public function pessoasFirstData()
    {
        $data = "INSERT INTO pessoas (tipoDocumento, documento, nome) VALUES('CNPJ', '11111111', 'IMPETUS FRAMEWORK')";
        return $data;
    }

    public function adminUserData()
    {
        $password = password_hash("admin", PASSWORD_BCRYPT);
        $data = "INSERT INTO users (name, email, username, password, permission, pessoaId) VALUES('IMPETUS', 'impetus@impetus.com', 'admin', '$password', 'admin', 1)";
        return $data;
    }

    public function observacoesTable()
    {
        $table = "CREATE TABLE observacoes(
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            status VARCHAR(256) NOT NULL DEFAULT 'ACTIVE',
            entidade VARCHAR(1024),
            entidadeId INT(30),
            texto TEXT(2000),
            usuarioId INT(30) NOT NULL,
            createdAt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
            updatedAt DATETIME
            )";
        return $table;
    }

    public function observacoesView()
    {
        $view = "CREATE VIEW vw_observacoes AS SELECT 
            OBS.id,
            OBS.status,
            OBS.entidade,
            OBS.entidadeId,
            OBS.texto,
            OBS.usuarioId,
            USERS.name AS usuarioNome,
            OBS.createdAt,
            OBS.updatedAt
        FROM observacoes OBS
        LEFT JOIN users USERS ON OBS.usuarioId = USERS.id;
        ";
        return $view;
    }

    public function arquivosTable()
    {
        $table = "CREATE TABLE arquivos(
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            status VARCHAR(256) NOT NULL DEFAULT 'ACTIVE',
            entidade VARCHAR(1024),
            entidadeId INT(30),
            nome VARCHAR(1000),
            path VARCHAR(5000),
            usuarioId INT(30) NOT NULL,
            tipo ENUM('ARQUIVO', 'CERTIFICADO/ALVARA'),
            vencimento DATE,
            createdAt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
            updatedAt DATETIME
            )";
        return $table;
    }

    public function arquivosView()
    {
        $view = "CREATE VIEW vw_arquivos AS SELECT 
            ARQ.id,
            ARQ.status,
            ARQ.entidade,
            ARQ.entidadeId,
            ARQ.nome,
            ARQ.path,
            ARQ.usuarioId,
            USERS.name,
            ARQ.tipo,
            ARQ.vencimento,
            ARQ.createdAt,
            ARQ.updatedAt
        FROM arquivos ARQ
        LEFT JOIN users USERS ON ARQ.usuarioId = USERS.id;
        ";
        return $view;
    }

    public function notificacoesTable()
    {
        $table = "CREATE TABLE notificacoes(
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            status ENUM('PENDENTE', 'LIDA') NOT NULL DEFAULT 'PENDENTE',
            titulo VARCHAR(512) NOT NULL,
            mensagem VARCHAR(1024) NOT NULL,
            cor VARCHAR(512),
            icone VARCHAR(512),
            userId INT(30) NOT NULL,
            createdAt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
            updatedAt DATETIME
            )";
        return $table;
    }
}
