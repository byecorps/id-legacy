<?php

// Includes
try {
    require "config.php";
} catch (Error $e) {
    echo "<b>Critical error:</b> " . $e->getMessage() . "<br />This isn't your fault. Please contact the developers.";
    exit;
}

// TODO: Init PDO.

// Starts the session
// TODO: write this to use the database to work across more than one server
session_start();


$uri_string = $_SERVER['REQUEST_URI'];  // `/foo/bar?bar=foo&foo=bar`
$uri_explode = explode('?', $uri_string);
$path = $uri_explode[0]; // `/foo/bar`
$path_array = explode('/', $path);

// Remove trailing slashes
if (str_ends_with($path, '/') && $path != '/') {
    http_response_code(308);
    header('Location: '.substr($path,0, -1));
    exit;
}

$routes = [
    '' => function () { require 'views/home.php'; },
];

//print_r($path_array);

if (array_key_exists($path_array[1], $routes)) {
    $res = $routes[$path_array[1]]();
    if ($res == 404) {
        require "views/404.php";
    }
} else {
    require "views/404.php";
}

