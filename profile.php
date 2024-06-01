
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

?>

