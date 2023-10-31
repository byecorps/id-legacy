<?php

session_start();

include("config.php");

$host_string = $_SERVER['HTTP_HOST'];
$host = explode('.', $host_string);
$uri_string = $_SERVER['REQUEST_URI'];
$query_string = explode('?', $uri_string);
$path = $query_string[0];
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
if (!$uri) {
    // empty array means index
    $include = "landing.html";
}
else if ($path == "/signin") {
    $doc_title = "Sign in";
    include("signin.php");
    exit;
}
else if ($path == "/register") {
    $doc_title = "Register";
    include("register.php");
    exit;
}
else if ($path == "/tests/id") {
    include("id_handler.php");
    exit;
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
        <?php include($include); ?>
    </main>
    <?php include("footer.php"); ?>
</body>
</html>