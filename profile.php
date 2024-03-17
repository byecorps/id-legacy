
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
$badges = db_execute_all('SELECT * FROM badge_owners INNER JOIN badges b on badge_owners.badge_id = b.id; ', []);
if (!empty($badges)) {
    if (!array_is_list($badges)) {
        $badges = array (0 => $badges);
    }
}

?>

<div id="profile">
    <img src="<?= $avatar ?>" class="avatar" alt="Avatar">
    <div class="info">
        <div class="displayname"><?= htmlspecialchars($display_name) ?></div>
        <div class="bcid"><?= format_bcid( $profile['id'] ); ?></div>
    </div>
</div>

<div id="details">
    <div id="badges">
        <h2>Badges</h2>
        <?php
        if (empty($badges)) {
            echo '<p>This profile has no badges :(</p>';
        } else {
            foreach ($badges as $badge) {
                echo "<div class='badge'>
<img src='". $badge['image'] ."' alt='". htmlspecialchars($badge['title']) ."' />
<div class='details'>
<span class='title'>" . htmlspecialchars($badge['title']) . "</span>
<p>". htmlspecialchars($badge['description']) ."</p>
<p class='subtitle'>". htmlspecialchars($badge['description']) ."</p>
<p class='earned subtitle'>Earned " . $badge['earned'] . "</p>
</div>
</div>";
            }
        }
        ?>
    </div>

    <div id="info">
        <h2>Info</h2>

        <table>
            <tr>
                <th>Joined</th>
                <td><?= $user['created_date'] ?></td>
            </tr>
            <tr>
                <th>Badges earned</th>
                <td><?= count($badges) ?></td>
            </tr>
        </table>

    </div>
</div>
