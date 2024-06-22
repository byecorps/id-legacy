
<?php

// Disable warnings lol
error_reporting(E_ALL ^ E_WARNING);

// Determine the app we are dealing with.

$flash = "";
$error = "";

if (null != $query['appid']) {
    $app_id = $query['appid'];
} else {
    $error = ["No app ID specified.", 200];
    goto login;
}

$app = db_execute("SELECT * FROM apps WHERE id = ? LIMIT 1", [$app_id]);
$doc_title = "Sign in to " . $app['title'];

// Lets check that the callback matches the app...
if (null == $query['callback']) {
    $disable_logging_in = true;
    $error = ["No callback URL.", 400];
    goto login;
}
if ($query['callback'] != $app['callback']) {
    $disable_logging_in = true;
    $error = ["Callback URL doesn't match our records.", 400];
    goto login;
}

if ($_SESSION['auth'] && $_SERVER['REQUEST_METHOD'] == 'GET') {
    // We can check if there's already a valid token of the same level and just pass that on instead.
    $valid_tokens = db_execute_all("SELECT * FROM tokens WHERE owner_id = ? AND type = ? AND application_id = ? AND expiry > ?",
        [$_SESSION['id'], "basic", $app_id, time()]);

    if (sizeof($valid_tokens) > 0) {
        print_r($valid_tokens);

        $token = $valid_tokens[0];

        header('Location: '. $_GET['callback'].'?access_token='.$token['access_token'].'&refresh='.$token['refresh_token']
                .'&expiry='.$token['expiry']);
        exit();
    }

//    if (validate_access_token())


}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Here's a few easy steps to figure out if we should give the other party a token or not.
//    print_r($_POST);

    // First: match the session ids. If they aren't the same it's probably Not Ok.
    if (session_id() != $_POST['sessionid']) {
        echo "<h1>401 Unauthorised</h1><p>You are not permitted to view this content.</p>";
        exit(401);
    }

    // Now let's determine if we're logged in or not. We can use the session for this, and verify using the
    // `bcid` value (which only appears if youre logged in!!!)
    if ($_SESSION['auth']) {
        if (null == $_POST['bcid'] || $_SESSION['id'] != $_POST['bcid']) {
            // Both of these suggest tampering,
            // let's log the user out and throw an error.
            $_SESSION['auth'] = false;
            $_SESSION['id'] = null;

            $flash = "Sorry, something went wrong. Please sign in again.";

            goto login;
        }
    }
    else { // of course, there's also the case that you WERENT logged in. Let's verify if you're logged in or not.
        $user_db_version = db_execute("SELECT * FROM accounts WHERE email = ?", [$_POST['email']]);
        if (!password_verify($_POST['password'], $user_db_version['password']) || null == $user_db_version) {
            // INCORRECT PASSWORD!!!!
            // or the account doesn't exist. we don't care either way.

            $flash = "Incorrect email or password.";
        } else {
            // if it's correct, we'll still force them to click log in again anyway. I'll also be nice and set the
            // cookies properly.

            $_SESSION['id'] = $user_db_version['id'];
            $_SESSION['auth'] = true;
            $user = $user_db_version;
            goto login;
        }
    }

    // The following gets run assuming we know the client is the one CLICKING the button.
    $tokens = generate_basic_access_token($_POST['bcid'], $app_id);

    header('Location: '. $_POST['callback'].'?access_token='.$tokens['access'].'&refresh='.$tokens['refresh']
                                .'&expiry='.$tokens['expiry']);
    exit();

}

login:

?>

        <div id="loginform">
            <?php if ("" != $error) {goto error_no_app;} ?>
            <div id="connection_img">
                <img src="<?= get_avatar_url($_SESSION['id']) ?>" alt="<?= htmlspecialchars($user['display_name']) ?>'s avatar" />
                <span class="sep">×</span>
                <img src="<?= $app['icon'] ?>" alt="<?= htmlspecialchars($app['title']) ?>" />
            </div>
            <h1>Sign into <?= htmlspecialchars($app['title']) ?></h1>
            <p class="subtitle">Owned by <strong><?= htmlspecialchars( get_display_name($app['owner_id'], put_bcid_in_parenthesis: true) ) ?></strong></p>
<!--            <p>--><?php //= htmlspecialchars($app['description']) ?><!--</p>-->
            <?php
            error_no_app:
            if ($error) {
                http_response_code($error[1]);
                echo "
<div class='error center vertical-center'>
<span class='fg-error fa-regular fa-2xl center fa-xmark-circle'></span>
<h2>Something went wrong!</h2>
<p>Server returned error:<br /><code>$error[0]</code> (HTTP response code $error[1])</p>
</div>
";
                goto dont_show_form;
            }
            ?>
            <p><strong><?= htmlspecialchars($app['title']) ?></strong> uses ByeCorps ID for authentication.</p>
            <p>Please double-check the information and avoid signing in with your BCID if you do not trust this app.</p>
            <p>Please confirm that you'd like to sign into <strong><?= htmlspecialchars($app['title']) ?></strong>.</p>
            <?php
            if (null != $flash) {
                echo "<p class='flash'>$flash</p>";
            } else {
                echo "<br />";
            }
            ?>
            <form class="login" method="post" action="">
                <input type="hidden" name="sessionid" value="<?= session_id() ?>" />
                <?php if ($_SESSION['auth'])
                { $bcid = $user['id']; echo "<input type='hidden' name='bcid' value='$bcid' />";
                    echo "<p class='subtitle'>You are signed in as ". get_display_name($_SESSION['id'],
                            put_bcid_in_parenthesis: true) . ". <a>Not you?</a>.";
                    goto signedin; } ?>
                <p class="subtitle">You will need to sign in first.</p>
                <input type="email" autocomplete="email" name="email" id="email" placeholder="Email" />
                <input type="password" name="password" id="password" placeholder="Password" />
                <?php signedin: ?>
                <button class="primary" type="submit">Sign into <?= htmlspecialchars($app['title']) ?></button>
                <p class="subtitle center">
                    You will be brought to <strong><?= htmlspecialchars($query['callback']) ?></strong>.
                    <br /><strong><?= htmlspecialchars($app['title']) ?></strong> will be able to see your email and display name.
                </p>
                <input type="hidden" name="callback" value="<?= $query['callback'] ?>" />
            </form>
            <?php dont_show_form: ?>

        </div>
