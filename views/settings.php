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
        <?php include 'partials/settings_list.php' ?>
    </main>

    <?php include 'partials/footer.php' ?>
</body>
</html>
