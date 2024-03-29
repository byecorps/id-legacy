<?php
require_once __DIR__ . '/vendor/autoload.php';

session_start();

error_reporting(E_ERROR | E_WARNING | E_PARSE);

if (empty($_SESSION)) {
    $_SESSION['auth'] = false;
}

include "config.php";

// MySQL
$pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD, PDO_OPTIONS);
// Email
if (defined("RESEND_API_KEY")) {
    $resend = Resend::client(RESEND_API_KEY);
}

require "misc_functions.php";
require "database.php";
include("time_handler.php");
include("id_handler.php");
include("accounts_handler.php");

// Attempt to log the user in using their cookie if auth isn't set.
if (!$_SESSION['auth']) {
    if (key_exists('keep_me_logged_in', $_COOKIE)) {
        if (validate_access_token($_COOKIE['keep_me_logged_in'])) {
            // Work out who the key belongs to
            $cookie_owner = db_execute("SELECT * FROM tokens WHERE access_token = ?", [$_COOKIE['keep_me_logged_in']]);
            if ($cookie_owner['type'] != "cookie") {
                setcookie('keep_me_logged_in', '', time()-3600);
                goto skip_cookie;
            }
            $_SESSION['auth'] = true;
            $_SESSION['id'] = $cookie_owner['owner_id'];

        } else {
            setcookie('keep_me_logged_in', '', time()-3600);
        }
    }
}

skip_cookie:

$host_string = $_SERVER['HTTP_HOST'];
$host = explode('.', $host_string);
$uri_string = $_SERVER['REQUEST_URI'];
$query_string = explode('?', $uri_string);
$path = $query_string[0];
if (str_ends_with($path,'/') && $path != "/") {
    header('Location: '.substr($path,0, -1));
    exit;
}
$uri = array_values(array_filter(explode('/', $uri_string)));
try {
    if ($_SESSION['auth']) {
        $user = db_execute("SELECT * FROM `accounts` WHERE id = ? LIMIT 1", [$_SESSION['id']]);
        if (!$user) {
            // Account doesn't exist. Log the user out.

            // We won't redirect to the logout endpoint because if this is going off there's something
            // broken anyway.
            session_destroy();
            die("Your session was invalid so we've logged you out.");
        }
    }
}
catch (Exception) {
    echo('<header>Database is broken. Please tell an admin.</header>');
    if ($uri_string == "/admin/init/database") { // Allows access to this page even if user doesn't have admin rights
                                                 // because you can't check the rights.
        echo "<main>";
        include "admin_initdatabase.php";
        die ("</main>");

    }
}


if (defined("SENTRY_DSN")) {
    \Sentry\init([
        'dsn' => SENTRY_DSN,
        // Specify a fixed sample rate
        'traces_sample_rate' => 1.0,
        // Set a sampling rate for profiling - this is relative to traces_sample_rate
        'profiles_sample_rate' => 1.0,
    ]);
}


function does_variable_exists( $variable ) {
    return (isset($$variable)) ? "true" : "false";
}

if(isset($query_string[1])) {
    $uri_string = $query_string[0];
    $query_string = explode('&', $query_string[1]);
    $query = array();
    foreach($query_string as $string) {
                $bits = explode('=', $string);
                $query[$bits[0]] = $bits[1];
            }
}
else {
    $query = array();
}


$include = "404.html";
// routing

$paths = array(
    "/" => ["landing.php"],

    "/admin" => ['admin.php'],
    "/admin/init/database" => ["admin_initdatabase.php"],
    "/admin/list/accounts" => ["admin_accounts.php"],
    "/admin/list/apps" => ["admin_apps.php"],
    "/admin/create/app" => ["admin_apps_create.php"],
    "/admin/signinas" => ["signinas.php"],
    "/admin/purge" => ["admin_purge.php"],

    // Settings
    "/dashboard" => ["dashboard.php", "Dashboard"],
    "/settings" => ["settings.php", "Settings"],

    "/account" => ["account.php", "Your account"],
    "/signin" => ["signin.php", "Sign in"],
    "/signup" => ["signup.php", "Sign up"],
    "/signout" => ["signout.php", "Signed out"],
    "/forgot/password" => ["forgot_password.php", "Forgot password"],
    "/reset/password" => ["reset_password.php", "Reset password"],
    "/docs" => ["docs.php", "Docs"],
    "/credits" => ["credits.php", "Credits"],
    "/profile" => ["profile.php", "Profile"],

    "/signin/external/basic" => ["login_external_basic.php"]
);

if (!empty($uri) ) { // Go to jail. Go directly to jail. Do not pass Go.
    if ($uri[0] == "api") {
        include("api_handler.php");
        exit(); // fuck this shit i'm out
    }
    if ($uri[0] == "public" && $uri[1] == "avatars") {
        include("image_grabber.php");
        exit();
    }
}

$migrated = false;
if (isset($paths[$path])) {
    $include = $paths[$path][0];
    if (isset($paths[$path][1])) {
        $doc_title = $paths[$path][1];
    }
    if (isset($paths[$path][2])) {
        $migrated = $paths[$path][2];
    }
}

else {
    $doc_title = "404";
    http_response_code(404);
}


if ($migrated) {
    $output = "";

    include($include);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include("head.php"); ?>
</head>
<body>
    <?php include("header.php"); ?>
    <main>
        <?php
        if (!empty($uri)) {
//            print_r ($uri);

            if ($uri[0] == "admin") {
                echo "<h2 class=\"subheading\">Admin</h2>";
            }

	        if ($uri[0] == "admin" && !$user['is_admin']) {
		        http_response_code(401);
		        die("<img src='https://http.cat/401.jpg' alt='A cat standing in front of a door with a No Cats Allowed sign on it.' />");
	        }

            if ($uri[0] == "docs") {
                $include = "docs.php";
            }
        }

        if ($migrated) {
            echo $output;
        }
        else {
            include ($include);
        }
        ?>
    </main>
    <?php include("footer.php"); ?>
</body>
</html>
