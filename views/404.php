<?php
http_response_code(404);
?>

<!doctype html>
<html lang="en">
<head>
    <?php require "partials/head.php"; ?>
    <title>404 ~> ByeCorps ID</title>
</head>
<body>
    <?php include "partials/header.php"; ?>

    <main>
        <div id="content">
            <h1>404</h1>
            <p>Sorry, but that doesn't exist anymore.</p>
            <p><small>(or it never existed)</small></p>
        </div>
    </main>

    <?php include 'partials/footer.php'; ?>
</body>
</html>

