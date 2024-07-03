<?php

$DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];

require_once $DOC_ROOT . '/vendor/autoload.php';

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
    echo "<b>Critical error:</b> " . $e->getMessage() . "<br />Please contact the developers.";
}

require_once 'strings/en.php'; // This ensures strings will fall back to English if one is missing.

require_once 'common/strings.php';
require_once 'common/account_utils.php';
require_once 'common/database.php';

patch_lang('en_UWU');

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

if(isset($uri_explode[1])) {
    $uri_string = $uri_explode[0];
    $uri_explode = explode('&', $uri_explode[1]);
    $query = array();
    foreach($uri_explode as $string) {
        $bits = explode('=', $string);
        $query[$bits[0]] = $bits[1];
    }
}
else {
    $query = array();
}

// Remove trailing slashes
if (str_ends_with($path_raw, '/') && $path_raw != '/') {
    http_response_code(308);
    header('Location: '.substr($path_raw,0, -1));
    exit;
}

$routes = [
    '' => function () { require 'views/home.php'; },
    'api' => function () {
        global $path, $query;

        unset($path[1]);
        $path = array_values($path);

        require 'api.php'; /* Handoff further routing to API script. */
    },
    'auth' => function () {
        global $path, $query;

        if ($path[2] == 'signout') {
            require 'views/signedout.php';
        } else if ($path[2] == 'signup') {
            require 'views/signup.php';
        } else if ($path[2] == 'login') {
            require 'views/login.php';
        } else {
            return 404;
        }
        exit();
    },
    'profile' => function () {
        global $path, $user, $profile_owner; // don't forget this lol

        if (isset($path[2])) {
            if (isset($path[3])) {
                return 404;
            }
            $profile_owner = $path[2];
            $profile_owner = get_user_by_id($profile_owner);
        } else {
            $profile_owner = $user;
        }

        require 'views/profile.php';
        return 200;
    },
    'dashboard' => function () {
        requires_auth();

        if (isset($path[2])) {
            return 404;
        }

        require 'views/dashboard.php';
        return 200;
    }
];

if (array_key_exists($path[1], $routes)) {
    $res = $routes[$path[1]]();
    if ($res == 404) {
        require "views/404.php";
    }
} else {
    require "views/404.php";
}

