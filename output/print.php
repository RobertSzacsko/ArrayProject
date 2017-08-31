<?php

include_once "console.php";
include_once "browser.php";
//include_once "file.php";

function printOutput($database, $header, $option,$select)
{
    $functionName = "print" . $option;
    $functionName($database/*,$header*/, $select);
}