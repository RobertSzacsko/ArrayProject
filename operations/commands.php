<?php

include_once "join.php";

function executeCommands($commands){
    $header=[];
    $dataBase=[];
    openDataBase($commands["from"], $header, $dataBase);
    joinTables($dataBase, $commands["join"], $header);
}

function openDataBase($filename,&$header,&$dataBase)
{
    $file=myReadFile($filename);
    parseFile($file, $header, $dataBase);
}

function myReadFile($filename)
{
    $toRead = fopen(BASE_PATH . 'database/' . $filename . ".csv", "r", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $lines = array();
    $iterator = 0;

    while (!feof($toRead)) {
        $lines[$iterator] = fgetcsv($toRead);
        $iterator++;
    }
    fclose($toRead);

    return $lines;
}

function parseFile($file, &$header, &$dataBase)
{
    $lineLimit = count($file[0]);
    $linesLimit = count($file);
    moveIdToFront($file);

    for($iterator = 1; $iterator < $lineLimit; $iterator++) {
        $header[$iterator - 1] = $file[0][$iterator];
    }

    for ($linesIterator = 1; $linesIterator < $linesLimit; $linesIterator++) {
        $key = $file[$linesIterator][0];
        $toPush = array();
        for ($lineIterator = 1; $lineIterator < $lineLimit; $lineIterator++) {
            $toPush[$header[$lineIterator-1]]= $file[$linesIterator][$lineIterator];
        }
        $dataBase[$key]=$toPush;
    }
}

function parseJoinFile($file,&$header,&$dataBase,$tableName)
{
    $lineLimit=count($file[0]);
    $linesLimit=count($file);
    moveJoinIdToFront($file);


    for($iterator=1; $iterator<$lineLimit; $iterator++) {
        $header[$iterator-1] = $tableName . '.' . $file[0][$iterator];
    }
    for ($linesIterator=1; $linesIterator<$linesLimit; $linesIterator++) {
        $key = $file[$linesIterator][0];
        $toPush = array();
        if (empty($dataBase[$key]) == true) {
            $dataBase[$key] = [];
        }
        for ($lineIterator = 1; $lineIterator < $lineLimit; $lineIterator++) {
            $toPush[$header[$lineIterator - 1]] = $file[$linesIterator][$lineIterator];
        }
        array_push($dataBase[$key], $toPush);
    }
    foreach($dataBase as $userId => $children) {
        $dataBase[$userId][$tableName] = $children;
        foreach (array_keys($dataBase[$userId]) as $key) {
            if ($key !== $tableName) {
                unset($dataBase[$userId][$key]);
            }
        }
    }
}


function moveIdToFront($file)
{
    $idPosition = findIdPosition($file[0]);
    foreach ($file as $line){
        $moveId = $line[$idPosition];
        unset($line[$idPosition]);
        array_unshift($line, $moveId);
    }
}

function moveJoinIdToFront($file)
{
    $idPosition = findJoinIdPosition($file[0]);
    foreach ($file as $line){
        $moveId = $line[$idPosition];
        unset($line[$idPosition]);
        array_unshift($line, $moveId);
    }
}


function findIdPosition($line)
{
    $limit= count($line);
    for($iterator = 0; $iterator < $limit; $iterator++) {
        if(strpos($line[$iterator] ,"id")!==false) {
            return $iterator;
        }
    }
    return $iterator;
}

function findJoinIdPosition($line)
{
    $limit= count($line);
    for($iterator = 0; $iterator < $limit; $iterator++) {
        if(strpos($line[$iterator] ,"_id")!==false) {
            return $iterator;
        }
    }
    return $iterator;
}