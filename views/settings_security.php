<?php

?>


<!doctype html>
<html>
<head>
    <?php require 'partials/head.php'; ?>
    <title><?= get_string('page.settings'); ?> ~> ByeCorps ID </title>
    <link rel="stylesheet" href="/styles/dashboard.css" />
</head>
<body>
    <?php include "partials/header.php" ?>

    <main>
        <h1><span class="fa-solid fa-fw fa-cog"></span> <?= get_string('page.settings'); ?></h1>
        <div class="grid">
            <?php include 'partials/settings_list.php' ?>
            <div class="settingsthingy">
                <h2><?= get_string('settings.security') ?></h2>
                <h3><?= get_string('auth.password') ?></h3>
                <form class="settings-grid mini-form" method="post">
                    <div class="input">
                        <label for="current-password"><?= get_string('auth.currentPassword') ?></label>
                        <input type="password" name="current-password" id="current-password" autocomplete="current-password" />
                    </div>
                    <div class="input">
                        <label for="new-password"><?= get_string('auth.newPassword') ?></label>
                        <input type="password" name="new-password" id="new-password" autocomplete="new-password" />
                    </div>
                    <div class="input">
                        <label for="confirm-password"><?= get_string('auth.confirmPassword') ?></label>
                        <input type="password" name="confirm-password" id="confirm-password" autocomplete="new-password" />
                    </div>

                    <button type="submit"><?= get_string('button.changePassword') ?></button>
                </form>
                <form action="/settings/security/passkey" method="post" class="settings-grid">
                    <h3><span class="icon icon-24 align-vertically fluent--person-passkey-32-filled center"></span> <?= get_string('auth.passkeyPlural') ?></h3>
                    <div class="grid halfandhalf">
                        <div class="item">
                            <p>Passkeys allow you to log in to ByeCorps ID using your device instead of a password.</p>
                            <button id="add_passkey">Add a passkey</button>
                        </div>
                        <div class="item">
                            <?= get_string('settings.passkeysCountNone') ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <?php include 'partials/footer.php' ?>
</body>
</html>
