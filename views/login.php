<?php

if ($_SESSION['auth']) {
    if (key_exists('callback', $query)) {
        header('Location: ' . urldecode($query['callback']));
    } else {
        header('Location: /dashboard');
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Figure out if it's a user
    $user_to_log_in_as = db_execute('SELECT id, email, password FROM accounts WHERE email = ?', [$_POST['email']]);
    if (!$user_to_log_in_as) {
        $error_body = get_string('error.incorrectAuth');
        goto skip;
    }

    if (password_verify($_POST['password'], $user_to_log_in_as['password'])) {
        $_SESSION['auth'] = true;
        $_SESSION['id'] = $user_to_log_in_as['id'];

        if (key_exists('callback', $query)) {
            header('Location: ' . urldecode($query['callback']));
        } else {
            header('Location: /dashboard');
        }
        exit();
    }
}

skip:

?>

<!doctype html>
<html lang="en">
<head>
    <?php include 'partials/head.php' ?>
</head>
<body>
<?php include 'partials/header.php' ?>

<main><?php

    if ($_SESSION['auth']) {
        $error_body = get_string('error.loggedIn');
    }

    ?>
    <h1><?= get_string('page.login') ?></h1>
    <?php
        if (isset($error_body)) {
            include 'partials/error.php';
        }
    ?>
    <form method="post">
        <p><label for="email"><?= get_string("auth.email") ?></label>
            <input type="email" name="email" id="email" /></p>
        <p><label for="password"><?= get_string("auth.password") ?></label>
            <input type="password" name="password" id="password" /></p>

        <button type="submit">Submit</button>
    </form>
</main>

<?php include 'partials/footer.php' ?>
</body>
</html>