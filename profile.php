
<link rel="stylesheet" href="/styles/profiles.css">

<?php

if (!$_SESSION['auth']) {
    header('Location: /signin?callback=/profile');
}

$profile = db_execute("SELECT * FROM `profiles` WHERE id = ? LIMIT 1", [$user['id']]);

if (empty($profile)) {
    $profile = [
            "id" => "0000000",
            "public_display_name" => false,
            "public_avatar" => false,
            "description" => null,
    ];
}

$avatar = "https://cdn.byecorps.com/id/profile/default.png";
$display_name = "";

if ($_SESSION['id'] != $profile['id']) {
    if ($profile['public_avatar']) {
        $avatar = get_avatar_url($profile['id']);
    }
    if ($profile['public_display_name']) {
        $display_name = get_display_name($profile['id'], false);
    }
} else {
    $avatar = get_avatar_url($profile['id']);
    $display_name = get_display_name($profile['id'], false);
}



?>

<div id="profile">
    <img src="<?= $avatar ?>" class="avatar" alt="Avatar">
    <div class="info">
        <div class="displayname"><?= $display_name ?></div>
        <div class="bcid"><?= format_bcid( $profile['id'] ); ?></div>
    </div>
</div>

<div id="details">
    <div id="badges">
        <h2>Badges</h2>
    </div>

    <div id="info">
        <h2>Info</h2>

        <table>
            <tr>
                <th>Joined</th>
                <td><?= $user['created_date'] ?></td>
            </tr>
        </table>

    </div>
</div>
