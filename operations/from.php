<?php

function from($tableName)
{
    $path = "database/" . $tableName . ".csv";
    fileExistValidation($path);

    $handle = fopen($path, "r");
    fileHandleValidation($handle);

    $database = readFromCsv($handle);

    return $database;
}

function fileExistValidation($path)
{
    if (file_exists($path) == false) {
        throw new Exception("File doesn't exist!!");
    }
}

function fileHandleValidation($handle)
{
    if (is_bool($handle) == true) {
        throw new Exception("Handle error!!");
    }
}

function readFromCsv($handle)
{
    global $header;
    $database = [];
    $headerLine = fgetcsv($handle);
    $idColumnPosition = array_search(USER_ID, $headerLine);

    setHeader($headerLine, $idColumnPosition);

    while (($line = fgetcsv($handle)) != false) {
        $user = [];

        $userId = $line[$idColumnPosition];
        unset($line[$idColumnPosition]);
        $line = array_values($line);

        foreach ($line as $key => $value) {
            $user[$header[$key]] = $value;
        }
        $database[$userId] = $user;
    }
    fclose($handle);

    return $database;
}

function setHeader($line, $position)
{
    global $header;

    $header = array_merge($header, $line);
    unset($header[$position]);
    $header = array_values($header);
}