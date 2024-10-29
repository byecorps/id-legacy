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
    if (!validate_csfr()) {
        flash(get_string('error.generic'), $flash);
        goto skip;
    }

    // Validate email address
    if (!validate_email($_POST['email'])) {
        $error_body = get_string('error.invalidEmail');
    }

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
} else {
    if (key_exists('callback', $query)) {
        $subtitle = get_string('auth.logInToContinue');
    }
}

skip:

?>

<!doctype html>
<html lang="en">
<head>
    <?php include 'partials/head.php' ?>
    <link rel="stylesheet" href="/styles/login_form.css" />
</head>
<body>
<?php include 'partials/header.php' ?>

<main><?php

    if ($_SESSION['auth']) {
        $error_body = get_string('error.loggedIn');
    }

    ?>

    <div id="wrapper">
        <h1 class="center"><?= get_string('page.login') ?></h1>
        <p class="center">Don't have one? <a href="/auth/signup">Sign up</a>.</p>
        <?php
        if (isset($subtitle)) {
            echo '<p class="subtitle center">'. $subtitle .'</p>';
        }

        if (isset($error_body)) {
            include 'partials/error.php';
        }
        ?>
        
        <form class="login-form" method="post">
            <?= csfr_input() ?>

            <div class="input"><label for="email"><?= get_string("auth.email") ?></label>
                <input type="email" name="email" id="email" /></div>
            <div class="input"><label for="password"><?= get_string("auth.password") ?></label>
                <input type="password" name="password" id="password" /></div>

            <button class="primary" type="submit"><?= get_string('auth.login') ?></button>
        </form>
    </div>

    <div class="spacer"></div>

    <div class="passkey center">
        <h2><span class="icon icon-32 align-vertically fluent--person-passkey-32-filled"></span>
            <span class="label"><?= get_string('auth.passkey') ?></span></h2>
        <p><?= get_string('auth.logInWithPasskeyExplainer'); ?></p>
        <p><button><?= get_string('auth.logInWithPasskey') ?></button></p>
    </div>

</main>

<?php include 'partials/footer.php' ?>
</body>
</html>