<?php

session_start();

$host_string = $_SERVER['HTTP_HOST'];
$host = explode('.', $host_string);
$uri_string = $_SERVER['REQUEST_URI'];
$query_string = explode('?', $uri_string);
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


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ByeCorps ID</title>

    <link rel="stylesheet" href="/styles/global.css">
    <link rel="stylesheet" href="/fontawesome/css/all.css">
</head>
<body>
    <?php include("header.php"); ?>
    <main>
        <?php 
            // routing
            if (!$uri) {
                // empty array means index
                include("landing.html"); 
            }
            else if ($query_string[0] == "/signin") {
                include("signin.php");
            }
            else {
                http_response_code(404);
                include("404.html");
            }
        ?>
    </main>
    <?php include("footer.php"); ?>
</body>
</html>