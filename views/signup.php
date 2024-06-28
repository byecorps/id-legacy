

<!doctype html>
<html lang="en">
<head>
    <?php include 'partials/head.php' ?>
</head>
<body>
    <?php include 'partials/header.php' ?>

    <main><?php

            if ($_SESSION['auth']) {
                $error_body = 'You\'re already logged in';
                include 'partials/error.php';
            }

        ?>
        <h1>Sign up</h1>
        <form method="post">
            <p><label for="email">Email</label>
                <input type="email" name="email" id="email" /></p>
            <p><label for="password">Password</label>
                <input type="password" name="password" id="password" /></p>
            <p><label for="repeat_password">Confirm password</label>
                <input type="password" name="repeat_password" id="repeat_password" /></p>

            <button type="submit">Submit</button>
        </form>
    </main>

    <?php include 'partials/footer.php' ?>
</body>
</html>