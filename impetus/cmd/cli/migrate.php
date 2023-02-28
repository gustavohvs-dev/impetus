<?php

function tables(){
    require "app/database/migrate.php";
    $migrateClass = new Migrate;
    echo ($migrateClass->tables());
    echo "\n\n";
}

function populate(){
    require "app/database/migrate.php";
    $migrateClass = new Migrate;
    echo ($migrateClass->populate());
    echo "\n\n";
}

function views(){
    require "app/database/migrate.php";
    $migrateClass = new Migrate;
    echo ($migrateClass->views());
    echo "\n\n";
}

function migrate(){
    require "app/database/migrate.php";
    $migrateClass = new Migrate;
    echo ($migrateClass->tables());
    echo ($migrateClass->populate());
    echo ($migrateClass->views());
    echo "\n\n";
}