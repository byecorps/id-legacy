<?php

//global $user;
if (is_null($user)) {
    $error = true;
    $error_body = "Not logged in.";
}

?>

<!doctype html>
<html lang="en">
<head>
    <?php include "partials/head.php" ?>
</head>
<body>
    <?php include "partials/header.php" ?>

    <main>
        <?php
        if ($error) {
            include 'partials/error.php';
            include 'partials/footer.php';
            exit();
        }
        ?>
        <p><?= $profile_owner['id'] ?></p>

    </main>

    <?php include 'partials/footer.php' ?>

</body>
</html>
