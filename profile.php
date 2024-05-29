
<link rel="stylesheet" href="/styles/profiles.css" />

<?php

if (!$_SESSION['auth']) {
    header('Location: /signin?callback=/profile');
    exit();
}

$profile = db_execute("SELECT * FROM `profiles` WHERE id = ? LIMIT 1", [$user['id']]);

if (empty($profile)) {
    $profile = [
            "id" => "9999999",
            "public_display_name" => false,
            "public_avatar" => false,
            "description" => null,
    ];
}

$avatar = "/assets/default.png";
$display_name = "";

if ($_SESSION['id'] != $profile['id']) {
    $avatar = get_avatar_url($profile['id']);
    if ($profile['public_display_name']) {
        $display_name = get_display_name($profile['id'], false);
    }
} else {
    $avatar = get_avatar_url($profile['id']);
    $display_name = get_display_name($profile['id'], false);
}

// Get badges owned by this person
$badges = db_execute_all('SELECT * FROM badge_owners INNER JOIN badges b on badge_owners.badge_id = b.id WHERE owner_id = ?; ', [$profile['id']]);
if (!empty($badges)) {
    if (!array_is_list($badges)) {
        $badges = array (0 => $badges);
    }
}

?>

