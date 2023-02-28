<?php

function controller($tableName)
{
    require "app/database/database.php";
    echo "\nComando controller em desenvolvimento... {$tableName}";
    echo "\n\n";
}