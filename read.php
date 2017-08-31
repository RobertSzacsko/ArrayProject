<?php

function readOptions()
{
    $functionName = "readOptions";
    if (checkReadConsole() === true) {
        $functionName .= "Console";
    } else {
        if (checkRequestMethodIsPost() == true) {
            $functionName .= "Form";
        } else {
            if (checkReadQuery() == true) {
                $functionName .= "Query";
            } else {
                throw new Exception("Crapa!!!!");
            }
        }
    }

    return $functionName();
}

/**
 * Return true if the Query String is set, false otherwise.
 *
 * @return bool
 */
function checkReadQuery()
{
    return isset($_SERVER["QUERY_STRING"]) === true;
}

/**
 * Return true if the request method is post, false otherwise.
 *
 * @return bool
 */
function checkRequestMethodIsPost()
{
    return  $_SERVER["REQUEST_METHOD"] === "POST";
}

/**
 * Returns true if the medium is console, false otherwise.
 *
 * @return bool
 */
function checkReadConsole()
{
    return php_sapi_name() === "cli";
}

/**
 * Read from console all options and save into an array. Return that array.
 *
 * @return array
 */
function readOptionsConsole()
{
    $defaultOptions = setDefaultOptions( "console", "all");
    $options = getopt("", [
        FROM . ":",
        SELECT . "::",
        JOIN . ":",
        WHERE . ":",
        OUTPUT . "::"
    ]);
    $options = array_merge($defaultOptions, $options);

    return $options;
}

/**
 * Read from Query String all options and save into an array. Return that array.
 *
 * @return array
 */
function readOptionsQuery()
{
    $queryFirstSplit = preg_split("/[\&]/", $_SERVER["QUERY_STRING"]);
    $options = setDefaultOptions("browser", "all");

    foreach ($queryFirstSplit as $value) {
        $tempArray = preg_split("/[\=]/", $value);
        $options[$tempArray[0]] = $tempArray[1];
    }

    return $options;
}

/**
 * Read from form all options and save into a array. Return that array.
 *
 * @return array
 */
function readOptionsForm()
{
    $options = setDefaultOptions("browser", "all");
    foreach ($_POST as $key => $value) {
        $options[$key] = $value;
    }

    return $options;
}

function setDefaultOptions($outputOption, $selectOption)
{
    return [
        OUTPUT => $outputOption,
        SELECT => $selectOption
    ];
}