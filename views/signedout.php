<?php

$_SESSION['auth'] = false;
session_destroy();

?>

<!doctype html>
<html lang="$lang_code">
<head>
    <title>Signed out ~> ByeCorps ID</title>
    <?php include 'partials/head.php'; ?>
</head>
<body>
    <?php include 'partials/header.php'; ?>
    <main>
        <center>
            <div class="largeicon">
                <span class="fa-fw fa-solid fa-person-through-window"></span>
            </div>
            <p><?= get_string('auth.signedout'); ?></p>
        </center>
    </main>
    <?php include 'partials/footer.php'; ?>
</body>
</html>