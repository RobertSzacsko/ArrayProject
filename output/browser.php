<?php

include_once "/var/www/sql/array.php";
/*
function printBrowser($database, $header, $select)
{
    echo '<table style="border-collapse: collapse">';
    printBrowserHeader($header,$select);
    printBrowserRows($database, $header,$select);
    echo "</table>";
}

function printBrowserHeader($header, $select)
{
    echo "<tr>";
    array_map(function ($element) {
        echo "<th style='border:1px solid black'>", $element, "</th>";
    }, $header);
    echo "</tr>";
}

function printBrowserRows($database, $header, $select)
{
    foreach ($database as $user) {
        echo "<tr>";
        $i = 0;
        printB($user, $header, $i);
        echo "</tr>";
    }
}
//
//function prepareNestedArray($array, $column)
//{
//    echo "<pre>";
//    //var_dump($array);
//    var_dump(array_column($array, "name"));
//}

function printBrowserData($string)
{
    echo "<td  style='border:1px solid black'>", $string, "</td>";
    return 0;
}

function printB($entity, $header, &$position)
{
    foreach ($entity as $information) {
        if (is_array($information) != true) {

            printBrowserData($information);
            $position++;

        } else {
            if (array_depth($information) == 2) {
                $numberOfFields = count($information[0]);
                $arrayKey = array_keys($information[0]);
                for ($i = 0; $i < $numberOfFields; $i++) {
                    if (strcmp($arrayKey[$i], $header[$position]) == 0) {
                        printBrowserData(implode(",", array_column($information, $header[$position])));
                    } else {
                        printBrowserData("NULL");
                        $i--;
                    }
                    $position++;
                }
            }
            if (array_depth($information) > 2) {
                printB($information, $header, $position);
            }
        }
    }
    for ($i = 0, $length = count($header) - $position; $i < $length; $i++) {
        printBrowserData("NULL");
    }
}

function printC($entity, $header, &$position)
{
    foreach ($entity as $information) {
        if (is_array($information) != true) {

            printBrowserData($information);
            $position++;

        } else {
            if (array_depth($information) < 2) {
                $copyPos = $position;
                foreach ($information as $key => $value) {
                    if (strcmp($key, $header[$position]) == 0) {
                        printBrowserData($value);
                    } else {
                        printBrowserData("NULL");
                    }
                    $position++;
                }
                $position = $copyPos;
            }
            if (array_depth($information) >= 2) {
                printC($information, $header, $position);
            }
        }
    }
}
*/
function printBrowser($database, $select)
{
    echo '<table style="border-collapse: collapse">';
    printBrowserHeader($select);
    printBrowserRows($database, $select);
    echo "</table>";
}

function printBrowserHeader($select)
{
    echo "<tr>";
    array_map(function ($element) {
        echo "<th style='border:1px solid black'>", $element, "</th>";
    }, $select);
    echo "</tr>";
}

function printBrowserRows($database, $select)
{
    foreach ($database as $user) {
        echo "<tr>";
        $i = 0;
        $rowAsArray = [];
        $rowAsArray = prepareRowAsArray($select, $user, $rowAsArray);
        printBrowserArray($rowAsArray);
        echo "</tr>";
    }
}

function prepareRowAsArray($columns, $entity, $array)
{
    foreach ($columns as $column) {
        $count = 0;
        foreach ($entity as $key => $information) {
            if (is_array($information) === true AND strcmp(explode(".", $column)[0], $key) === 0) {
                array_push($array, prepareInformationAsElementForArray($information, $column));
                $count++;
            }
            if (is_string($information) === true AND strcmp($column, $key) == 0) {
                array_push($array, $information);
                $count++;
            }
        }
        if ($count == 0) {
            array_push($array, "NULL");
        }
    }

    return $array;
}

function prepareInformationAsElementForArray($information, $infoToPrint)
{
    $newArray = [];
    foreach ($information[0] as $key => $column) {
        if (strcmp($key, $infoToPrint) == 0) {
            array_push($newArray, implode(",", array_column($information, $key)));
        }
    }

    return implode(";", $newArray);

}

function printBrowserArray($array)
{
    foreach ($array as $string) {
        printBrowserData($string);
    }
}

function printBrowserData($string)
{
    echo "<td  style='border:1px solid black'>", $string, "</td>";
    return 0;
}

function printB($entity, $select, &$position)
{
    foreach ($entity as $information) {
        if (is_array($information) != true) {

            printBrowserData($information);
            $position++;

        } else {
            if (array_depth($information) == 2) {
                $numberOfFields = count($information[0]);
                $arrayKey = array_keys($information[0]);
                for ($i = 0; $i < $numberOfFields; $i++) {
                    if (strcmp($arrayKey[$i], $select[$position]) == 0) {
                        printBrowserData(implode(",", array_column($information, $select[$position])));
                    } else {
                        printBrowserData("NULL");
                        $i--;
                    }
                    $position++;
                }
            }
            if (array_depth($information) > 2) {
                printB($information, $select, $position);
            }
        }
    }
    for ($i = 0, $length = count($select) - $position; $i < $length; $i++) {
        printBrowserData("NULL");
    }
}