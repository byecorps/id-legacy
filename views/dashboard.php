<!doctype html>
<html>
    <head>
        <?php include 'partials/head.php'; ?>
        <title><?= get_string('page.dashboard') ?> ~> ByeCorps ID</title>
        <link rel="stylesheet" href="/styles/dashboard.css" />
    </head>
    <body>
        <?php include 'partials/header.php'; ?>
        <main>
            <h1><?= get_string('page.dashboard') ?></h1>
            <div class="grid">
                <div class="id-card">
                    <img src="https://cdn.id.byecorps.com/profile/281G3NV" alt="" />
                    <div class="info">
                        <div class="display_name"><?= get_user_display_name($_SESSION['id']) ?></div>
                        <div class="id"><?= format_bcid($_SESSION['id']) ?></div>
                        <div class="join-date"><?= get_string('idcard.joined', ['date' => $user['created_date']]) ?></div>
                    </div>
                </div>
                <ul>
                    <li>
                        <a href="/profile" class="item">
                            <div class="icon"><span class="fa-fw fa-solid fa-id-card"></span></div>
                            <div class="label"><?= get_string('page.profile') ?></div>
                        </a>
                    </li>
                    <li>
                        <a href="/settings" class="item">
                            <div class="icon"><span class="fa-fw fa-solid fa-cog"></span></div>
                            <div class="label"><?= get_string('page.settings') ?></div>
                        </a>
                    </li>
                    <li>
                        <a href="/settings/apps" class="item">
                            <div class="icon"><span class="fa-fw fa-solid fa-lock"></span></div>
                            <div class="label"><?= get_string('page.manageAppAccess') ?></div>
                        </a>
                    </li>
                    <li>
                        <a href="/auth/signout" class="item">
                            <div class="icon"><span class="fa-fw fa-solid fa-right-to-bracket"></span></div>
                            <span class="label"><?= get_string('auth.signout') ?></span>
                        </a>
                    </li>
                </ul>
            </div>
        </main>
        <?php include 'partials/footer.php'; ?>
    </body>
</html>