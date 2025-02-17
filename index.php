<?php

$DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];

require_once $DOC_ROOT . '/vendor/autoload.php';
use Intervention\Image\ImageManager;

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

$bunny_client = new \Bunny\Storage\Client(BUNNY_ACCESS_KEY, BUNNY_STORAGE_ZONE, \Bunny\Storage\Region::STOCKHOLM);

require_once 'strings/en.php'; // This ensures strings will fall back to English if one is missing.

require_once 'common/strings.php';
require_once 'common/validation.php';
require_once 'common/database.php';
require_once 'common/account_utils.php';
require_once 'common/app_utils.php';
require_once 'common/files.php';
require_once 'common/misc.php';

$flash = [];

// Starts the session
// TODO: write this to use the database to work across more than one server (e.g. don't use PHP sessions)
session_start();

if (empty($_SESSION)) {
    $_SESSION['auth'] = false;
    $_SESSION['id'] = null;
}

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] =  get_language_code_based_on_browser_locale();
}

$user = null;

if ($_SESSION['auth']) {
    $user = get_user_by_id($_SESSION['id']);
    $_SESSION['lang'] = $user['language'];
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

// If there's a 'lang' query param, change the language!
if (array_key_exists('lang', $query)) {
    $_SESSION['lang'] = $query['lang'];
    location($path_raw);
}

patch_lang($_SESSION['lang']);


$routes = [
    '' => function () { global $user; require 'views/home.php'; },
    'admin' => function () {
        global $path, $query, $DOC_ROOT, $flash, $user;

        requires_auth();
        requires_admin();

        if (key_exists(2, $path)) {
            switch ($path[2]) {
                default: return 404;
                case 'files':
                    require 'views/admin/files.php';
            }
        } else {
            require 'views/admin/dashboard.php';
        }
    },
    'api' => function () {
        global $path, $query, $user;

        unset($path[1]);
        $path = array_values($path);

        require 'api.php'; /* Handoff further routing to API script. */
    },
    'auth' => function () {
        global $path, $query, $flash, $user;

        switch ($path[2]) {
            case 'signout':
                require 'views/signedout.php';
                break;
            case 'signup':
                require 'views/signup.php';
                break;
            case 'login':
                require 'views/login.php';
                break;
            case 'oauth':
                require 'views/oauth_login.php';
                break;
            default:
                return 404;
        }
        exit();
    },
    'dashboard' => function () {
        global $user;
        requires_auth();

        if (isset($path[2])) {
            return 404;
        }

        require 'views/dashboard.php';
        return 200;
    },
    'profile' => function () {
        global $path, $user, $profile_owner; // don't forget this lol

        if (isset($path[2])) {
            if (isset($path[3])) {
                return 404;
            }

            if ($path[2] == 'edit') {
                requires_auth();
                require 'views/profile_edit.php';
                return 200;
            }

            $profile_owner = $path[2];
            $profile_owner = get_user_by_id($profile_owner);
        } else {
            $profile_owner = $user;
        }

        require 'views/profile.php';
        return 200;
    },
    'settings' => function () {
        global $path, $flash, $user, $query;
        if (isset($path[2])) {
            switch ($path[2]) {
                default: return 404;
                case 'security':
                    require 'views/settings_security.php';
                    break;
                case 'region':
                    require 'views/settings_region.php';
                    break;
            }
        } else {
            require 'views/settings.php';
        }
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

