<?php

class Migrate20230309_1
{
    public function users4Table()
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

    public function addMethod2ColumnUpdate()
    {
        $data = "ALTER TABLE log ADD method2 VARCHAR(128) DEFAULT 'YES'";
        return $data;
    }

    public function setMethod2Update()
    {
        $data = "UPDATE `log` SET `method2` = 'NAO'";
        return $data;
    }
}
