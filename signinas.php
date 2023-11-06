<?php 

if ($_SESSION['id'] != "281G3NV") {
    http_response_code(401);
    die("<img src='https://http.cat/401.jpg'>");
}

$_SESSION['id'] = $query['id'];

header ('Location: /account');

?>