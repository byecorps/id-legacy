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

<link rel='stylesheet' href='/styles/settings.css' />

<div id="settings_split">
    <div id="mini_profile" class="left">
        <div class="image_container" data-backgroundcolour="white">
            <img src='<?= get_avatar_url($user['id']) ?>' />
        </div>
        <div class="texts">
            <span class="displayname"><?= htmlspecialchars(get_display_name($user['id'], false)) ?></span>
            <span class="id bcid"><?= format_bcid($user['id']) ?></span>
        </div>
    </div>
    <div class='right tiles'>
        <a href="/settings" class="tile double-height">
            <div class="wrapper"> <!-- SUPERIMPORTANTBECAUSE IM BAD AT CSS -->
                <span class="fa-solid fa-cog icon"></span>
                <span class="text">Settings</span>
            </div>

        </a>
        <a href="/profile" class="tile">
            <div class="wrapper"> <!-- SUPERIMPORTANTBECAUSE IM BAD AT CSS -->
                <span class="fa-solid fa-id-card-clip icon"></span>
                <span class="text">Profile</span>
            </div>
        </a>
    </div>
</div>
