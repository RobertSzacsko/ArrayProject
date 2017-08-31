<?php

include_once "commands.php";

function joinTables(&$dataBase,$tables,&$header)
{
    $tablesNr=count($tables);
    if($tablesNr>1) {
        for ($iterator = 0; $iterator < $tablesNr; $iterator++) {
            join2Tables($dataBase, $tables[$iterator], $header);
        }
    }
    else {
        join2Tables($dataBase,$tables,$header);
    }
}

function join2Tables(&$dataBase,$table,&$header)
{
    $table2=myReadFile($table);
    $header2=array();
    $dataBase2=array();
    $table=explode('.',$table);
    parseJoinFile($table2,$header2,$dataBase2,$table[0]);
    mergeTables($header,$dataBase,$header2,$dataBase2);
}

function mergeTables(&$header,&$dataBase,$header2,$dataBase2)
{
    $header=array_merge($header,$header2);
    $dataBase2=array_intersect_key($dataBase2,$dataBase);
    $dataBase=(array_merge_custom($dataBase , $dataBase2));
}

function array_merge_custom($array1,$array2) {
    $mergeArray = [];
    $array1Keys = array_keys($array1);
    $array2Keys = array_keys($array2);
    $keys = array_merge($array1Keys,$array2Keys);

    foreach($keys as $key) {
        $mergeArray[$key] = array_merge_recursive(isset($array1[$key])?$array1[$key]:[],isset($array2[$key])?$array2[$key]:[]);
    }

    return $mergeArray;
}

