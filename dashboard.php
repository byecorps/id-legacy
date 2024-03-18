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

$doc_title = get_display_name($user['id']) . "'s Dashboard" ;

$output = $output .
    "<link rel='stylesheet' href='/styles/settings.css' />

<div id=\"settings_split\">
    <div id=\"mini_profile\" class=\"left\">
        <div class=\"image_container\" data-backgroundcolour=\"white\">
            <img src='" . get_avatar_url($user['id']) . "' />
        </div>
        <div class=\"texts\">
            <span class=\"displayname\">" . htmlspecialchars(get_display_name($user['id'], false)) . "</span>
            <span class=\"id bcid\">" .  format_bcid($user['id']) . "</span>
        </div>
    </div>
    <div class='right'>
        <h1>". htmlspecialchars(get_display_name($user['id'], false)) ."'s Dashboard</h1>
    </div>
</div>
";
