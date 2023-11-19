<?php
require_once __DIR__ . '/vendor/autoload.php';

session_start();

use kornrunner\Blurhash\Blurhash;

if (empty($_SESSION)) {
    $_SESSION['auth'] = false;
}

include("config.php");

$pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD, PDO_OPTIONS);

include("time_handler.php");
require "misc_functions.php";
require "database.php";
include("id_handler.php");
include("accounts_handler.php");

if ($_SESSION['auth']) {
	$user = db_execute("SELECT * FROM `accounts` WHERE id = ? LIMIT 1", [$_SESSION['id']]);
}

\Sentry\init([
    'dsn' => SENTRY_DSN,
    // Specify a fixed sample rate
    'traces_sample_rate' => 1.0,
    // Set a sampling rate for profiling - this is relative to traces_sample_rate
    'profiles_sample_rate' => 1.0,
]);

function does_variable_exists( $variable ) {
    return (isset($$variable)) ? "true" : "false";
}

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

    "/account" => ["account.php", "Your account"],
    "/signin" => ["signin.php", "Sign in"],
    "/signup" => ["signup.php", "Sign up"],
    "/signout" => ["signout.php", "Signed out"],
    "/forgot/password" => ["forgot_password.php", "Forgot password"],
    "/admin/signinas" => ["signinas.php"],
    "/reset/password" => ["reset_password.php", "Reset password"],
    "/docs" => ["docs.php", "Docs"],
    "/credits" => ["credits.html", "Credits"],
    "/profile" => ["profile.php", "Profile"],
);

if (isset($paths[$path])) {
    $include = $paths[$path][0];
    if (isset($paths[$path][1])) {
        $doc_title = $paths[$path][1];
    }
}

else {
    $doc_title = "404";
    http_response_code(404);
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
		        die("<img src='https://http.cat/401.jpg'>");
	        }
            if ($uri[0] == "docs") {
                $include = "docs.php";
            }
        }


        include($include); ?>
    </main>
    <?php include("footer.php"); ?>
</body>
</html>