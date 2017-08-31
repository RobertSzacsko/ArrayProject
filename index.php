<?php

include_once "output/print.php";
include_once "constants.php";
include_once "operations/from.php";
include_once "operations/join.php";
include_once "operations/commands.php";
include_once "read.php";

$header = [];

$options = readOptions();
$database = from($options[FROM]);
$options[SELECT]=explode(',',$options[SELECT]);
if (isset($options[FROM]) === true) {
    $listJoin = explode(",", $options[JOIN]);
    foreach ($listJoin as $join) {
        joinTables($database, $join, $header);
    }
}
if ($options[SELECT][0]=="" || $options[SELECT][0]=="all") {
    $options[SELECT]=$header;
}
printOutput($database, $header, $options[OUTPUT],$options[SELECT]);