<?php

class Database
{
    /**
     * Important table to authenticate the users with JWT in the web service
     */
    public function usersTable()
    {
        $table = "(
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            fk_companyId INT(30) NOT NULL,
            status VARCHAR(256) NOT NULL DEFAULT 'ACTIVE',
            name VARCHAR(1024) NOT NULL,
            email VARCHAR(1024) NOT NULL,
            username VARCHAR(256) NOT NULL UNIQUE,
            password VARCHAR(256) NOT NULL,
            permission ENUM('admin','user') NOT NULL,
            isConfirmedEmail BOOLEAN DEFAULT 0,
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

    /**
     * Adds a first admin user in users table
     */
    public function impetusCompaniesData()
    {
        $data = "INSERT INTO companies (corporateName, name, document) VALUES('IMPETUS FRAMEWORK', 'IMPETUS', '11.111.111/0001-11')";
        return $data;
    }

    /**
     * Adds a first admin user in users table
     */
    public function adminUserData()
    {
        $password = password_hash("admin", PASSWORD_BCRYPT);
        $data = "INSERT INTO users (name, email, username, password, permission) VALUES('IMPETUS', 'impetus@impetus.com', 'admin', '$password', 'admin')";
        return $data;
    }

    /**
     * Table example with foreign key reference
     */
    /*public function logTable()
    {
        $table = "(
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            fk_user INT NOT NULL,
            method VARCHAR(512) NOT NULL,
            comment TEXT(1000) NOT NULL,
            createdAt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
            FOREIGN KEY (fk_user) REFERENCES users(id)
            )";
        return $table;
    }*/

    /**
     * An example of how incluse a view
     */
    /*public function logView()
    {
        $view = "LOG.id, LOG.fk_user, USER.username, LOG.method, LOG.comment, LOG.createdAt FROM log LOG LEFT JOIN users USER ON fk_user = USER.id;";
        return $view;
    }*/

}
