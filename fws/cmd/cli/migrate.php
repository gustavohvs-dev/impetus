<?php

function migrate(){
    require_once "app/database/migrate.php";
    $migrateClass = new migrate;
    echo ($migrateClass->migrate());
}

