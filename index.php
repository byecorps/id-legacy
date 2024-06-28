<?php

$DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];

// Includes
try {
    require_once "config.php";
} catch (Error $e) {
    echo "<b>Critical error:</b> " . $e->getMessage() . "<br />This isn't your fault. Please contact the developers.";
    exit;
}

// Connect to database
try {
    $pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD, PDO_OPTIONS);
} catch (PDOException $e) {
    echo "<b>Critical error:</b> " . $e->getMessage() . "<br />";
}

require_once 'strings/en.php'; // This ensures strings will fallback to English if one is missing.

require_once 'common/strings.php';
require_once 'common/account_utils.php';
require_once 'common/database.php';

// Starts the session
// TODO: write this to use the database to work across more than one server (e.g. don't use PHP sessions)
session_start();

$user = null;

if ($_SESSION['auth']) {
    $user = get_user_by_id($_SESSION['id']);
}

$uri_string = $_SERVER['REQUEST_URI'];  // `/foo/bar?bar=foo&foo=bar`
$uri_explode = explode('?', $uri_string);
$path_raw = $uri_explode[0]; // `/foo/bar`
$path = explode('/', $path_raw);

// Remove trailing slashes
if (str_ends_with($path_raw, '/') && $path_raw != '/') {
    http_response_code(308);
    header('Location: '.substr($path_raw,0, -1));
    exit;
}

$routes = [
    '' => function () { require 'views/home.php'; },
    'api' => function () { require 'api.php'; /* Handoff further routing to API script. */ },
    'auth' => function () {
        global $path;

        if ($path[2] == 'signup') {
            require 'views/signup.php';
        }
    },
    'profile' => function () {
        global $path, $user, $profile_owner; // don't forget this lol

        if (isset($path[2])) {
            $profile_owner = $path[2];
            $profile_owner = get_user_by_id($profile_owner);
        } else {
            $profile_owner = $user;
        }

        require 'views/profile.php';
    },
];

if (array_key_exists($path[1], $routes)) {
    $res = $routes[$path[1]]();
    if ($res == 404) {
        require "views/404.php";
    }
} else {
    require "views/404.php";
}

