<?php

class Migrate00000000_0
{
    public function usersTable()
    {
        $table = "(
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            status VARCHAR(256) NOT NULL DEFAULT 'ACTIVE',
            name VARCHAR(1024) NOT NULL,
            email VARCHAR(1024) NOT NULL,
            username VARCHAR(256) NOT NULL UNIQUE,
            password VARCHAR(256) NOT NULL,
            permission ENUM('admin','user') NOT NULL,
            isConfirmedEmail BOOLEAN DEFAULT 0,
            companyId INT(30) NOT NULL,
            createdAt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
            updatedAt DATETIME
            )";
        return $table;
    }

    public function companiesTable()
    {
        $table = "(
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            status VARCHAR(256) NOT NULL DEFAULT 'ACTIVE',
            corporateName VARCHAR(2048) NOT NULL,
            name VARCHAR(2048) NOT NULL,
            document VARCHAR(256) NOT NULL UNIQUE,
            createdAt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
            updatedAt DATETIME
            )";
        return $table;
    }

    public function logTable()
    {
        $table = "(
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            method VARCHAR(512) NOT NULL,
            request TEXT(8000) NOT NULL,
            response TEXT(8000) NOT NULL,
            userId INT NOT NULL,
            createdAt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
            FOREIGN KEY (userId) REFERENCES users(id)
            )";
        return $table;
    }

    public function usersView()
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

    public function logView()
    {
        $view = "
            LOG.id, 
            LOG.userId, 
            USER.username, 
            LOG.method, 
            LOG.request, 
            LOG.response, 
            LOG.createdAt 
        FROM log LOG 
        LEFT JOIN users USER ON userId = USER.id;
        ";
        return $view;
    }

    public function impetusCompaniesData()
    {
        $data = "INSERT INTO companies (corporateName, name, document) VALUES('IMPETUS FRAMEWORK', 'IMPETUS', '11.111.111/0001-11')";
        return $data;
    }

    public function adminUserData()
    {
        $password = password_hash("admin", PASSWORD_BCRYPT);
        $data = "INSERT INTO users (name, email, username, password, permission) VALUES('IMPETUS', 'impetus@impetus.com', 'admin', '$password', 'admin')";
        return $data;
    }
}
