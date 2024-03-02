<?php

if (!$_SESSION['auth']) {
    echo("You are being redirected...");
    http_response_code(302);
    header('Location '. BASE_URL .'/signin?callback=/dashboard');
    die();
}

?>

<h1>Hey there <?= $user['display_name'] ?>!</h1>
