<?php

function migrate(){
    require "app/database/migrate.php";
    $migrateClass = new DatabaseMigrate;
    echo ($migrateClass->migrate());
}

