<?php

function update_language(): void
{
    global $user;
    if (is_null($user)) {
        $user['id'] = DEMO_USER;
    }
    set_user_language($_POST['lang'], $user['id']);
    location('/settings/region?success=true');
}

if (array_key_exists('success', $query)) {
    if ($query['success'] == 'true') {
        flash(get_string('generic.languageUpdated'), $flash);
    }
}

if (isset($path[3])) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        switch ($path[3]) {
            case 'set_language':
                update_language();
                break;
            default:
                location('/settings/region');
                exit;
        }
    } else {
        location('/settings/region');
    }
}

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
        <?php
            if ($_SESSION['auth']) {
                include 'partials/settings_list.php';
            }
        ?>
        <div class="settingsthingy">
            <h2><?= get_string('settings.region') ?></h2>
            <p>Here you can set the language ByeCorps ID is displayed in.</p>
            <form action="/settings/region/set_language" method="post">
                <?= show_flash($flash); ?>
                <div class="language-selector">
                    <?php
                    foreach (LANGAUGES as $lang) {
                        $checked = '';
                        if ($lang['code'] == $_SESSION['lang']) {
                            $checked = 'checked="checked"';
                        }
                        echo '<label>
                    <input type="radio" name="lang" '.$checked.' id="lang" value="'. $lang['code'] . '" />
                    '. get_string('language.'.$lang['code']) .' - '. $lang['name'] .'
                </label>';
                    }
                    ?>
                </div>
                <button class='primary' type="submit"><?= get_string('button.submit') ?></button>
            </form>
        </div>
    </div>
</main>

<?php include 'partials/footer.php' ?>
</body>
</html>
