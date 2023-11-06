<?php

session_start();

include("config.php");
include("id_handler.php");
include("time_handler.php");

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

$pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD, PDO_OPTIONS);

$include = "404.html";
// routing

$paths = array(
    "/" => ["landing.php"],
    "/admin/init/database" => ["admin_initdatabase.php"],
    "/admin/accounts" => ["admin_accounts.php"],
    "/account" => ["account.php", "Your account"],
    "/signin" => ["signin.php", "Sign in"],
    "/signup" => ["signup.php", "Sign up"],
    "/signout" => ["signout.php", "Signed out"],
    "/forgot_password" => ["forgot_password.php", "Forgot password"],
    "/admin/signinas" => ["signinas.php"]
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
        
        if ($uri[0] == "admin" && $_SESSION['id'] != "281G3NV") {
            http_response_code(401);
            die("<img src='https://http.cat/401.jpg'>");
        }

        include($include); ?>
    </main>
    <?php include("footer.php"); ?>
</body>
</html>