<?php

function build($tableName)
{
    model($tableName);
    controller($tableName);
    route($tableName);
    echo "\n\n";
}