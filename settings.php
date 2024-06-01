<?php

if (empty($_SESSION)) {
    http_response_code(307);
    header('Location: /signin?callback=/dashboard');
    exit();
}
if (!$_SESSION['auth']) {
    http_response_code(307);
    header('Location: /signin?callback=/dashboard');
    exit;
}

?>

<link href="/styles/settings.css" rel="stylesheet" />

<div id="settings_split">
    <div id="mini_profile" class="left">
        <div class="image_container" data-backgroundcolour="white">
            <img src=<?= get_avatar_url($user['id']) ?> />
        </div>
        <div class="texts">
            <span class="displayname"><?= htmlspecialchars(get_display_name($user['id'], false)) ?></span>
            <span class="id bcid"><?= format_bcid($user['id']) ?></span>
        </div>
    </div>

    <ul id="settings_list" class="right">
        <h1>Settings</h1>
        <li>
            <a href="/settings/account">
                <i class="fa-solid fa-fw fa-person icon"></i>
                Account
            </a>
        </li>
        <li>
            <a href="/dashboard">
                <i class="fa-solid fa-fw fa-arrow-left icon"></i>
                Return to Dashboard
            </a>
        </li>
    </ul>
</div>
