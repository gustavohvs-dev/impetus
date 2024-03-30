<?php

class Migrate20230309_1
{
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
