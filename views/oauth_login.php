<?php

$please_exit = false;
$passed_callback = false;

$app = null;

// Try to get the app details
try {
    $app = get_app_by_id($query['appid']);
} catch (TypeError $e) {
    flash(get_string('error.noAppId'), $flash);
    $please_exit = true;
}

if (empty($app)) {
    flash(get_string('error.invalidAppId'), $flash);
}

if (key_exists('callback', $query)) {
    if ($query['callback'] == $app['callback']) {
        $passed_callback = true;
    }
}

$signed_in = !is_null($user);

?>

<!doctype html>
<html lang="en">
<head>
    <?php include 'partials/head.php' ?>
    <link rel="stylesheet" href="/styles/login_form.css" />
</head>
<body>
<?php include 'partials/header.php' ?>

<main>
    <?=
        show_flash($flash);
        if ($please_exit) {
            goto pls_quit;
        }
    ?>

    <h1><?= htmlspecialchars($app['title']) ?> wants to sign in with your ByeCorps ID</h1>
    <p><i><?= htmlspecialchars($app['description']) ?></i><br>(The above was provided by the developers)</p>

    <?php
        if ($signed_in && $passed_callback) {
            echo 'PASSED!!';
        }
    ?>

    <?php
    pls_quit:
    ?>
</main>

<?php include 'partials/footer.php' ?>
</body>
</html>