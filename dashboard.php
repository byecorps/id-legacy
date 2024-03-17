<?php

if (empty($_SESSION)) {
    http_response_code(307);
    header('Location: /signin?callback=/dashboard');
    exit();
}
if (!$_SESSION['auth']) {
    http_response_code(307);
    header('Location: /signin?callback=/dashboard');
    exit;
}

$doc_title = get_display_name($user['id']) . "'s Dashboard" ;

$output = $output .
    "<h1>Hey there ". $user['display_name'] ."!</h1>";
