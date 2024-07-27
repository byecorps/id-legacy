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
        if (isset($error)) {
            include 'partials/error.php';
            include 'partials/footer.php';
            exit();
        }
        ?>

        <div id="profile-wrapper">
            <img id="profile-image"
                 src="<?= get_user_avatar($profile_owner['id']) ?>"
                 alt="<?= get_user_display_name($profile_owner['id']) ?>'s avatar" />
            <div id="profile-user-info">
                <span id="profile-display-name"><?= get_user_display_name($profile_owner['id']) ?></span>
                <span id="profile-bcid"><?= format_bcid($profile_owner['id']) ?></span>
            </div>
        </div>
    </main>

    <?php include 'partials/footer.php' ?>

</body>
</html>
