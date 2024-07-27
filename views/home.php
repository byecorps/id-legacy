
<html>
    <head>
        <?php include 'partials/head.php'; ?>
        <title>ByeCorps ID</title>
    </head>
    <body>
        <?php include 'partials/header.php'; ?>

        <main>
            <div class="hero">
                <div class="hero-text">
                    <img src="https://cdn.id.byecorps.com/assets/bcid.svg" alt="ByeCorps ID Logo" class="logo">
                    <h1><span class="bc-1">Bye</span><span class="bc-2">Corps</span><span class="bc-3"> ID</span></h1>
                    <p>Log into ByeCorps and beyond with a single ID.</p>
                    <!-- <p><input type="email" name="loginEmail" id="loginEmail" placeholder="Email" /></p> -->

                    <a href="/auth/login" class="button primary"><?= get_string('auth.login') ?></a>
                    <a href="/auth/signup" class="button"><?= get_string('auth.signup') ?></a>
                </div>
            </div>
        </main>

        <?php include 'partials/footer.php'; ?>
    </body>
</html>
