<?php

function build($tableName)
{
    model($tableName);
    controller($tableName);
    routes($tableName);
    echo "\n\n";
}