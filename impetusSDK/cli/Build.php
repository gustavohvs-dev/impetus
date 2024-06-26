<?php

function build($tableName)
{
    model($tableName);
    $controllerStatus = controller($tableName);
    if($controllerStatus){
        routes($tableName);
    };
}