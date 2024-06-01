<?php

if (array_key_exists(2, $uri)) {
    $avatar_links = db_execute('SELECT public FROM avatars WHERE id = ? LIMIT 1', [$uri[2]]);

    if (empty($avatar_links)) {
        $fp = fopen('./assets/default.png', 'rb');
    } else {
        $fp = fopen(DATA_LOCATION . $avatar_links['public'], 'rb');
    }

    header("Content-Type: image/png");
    header("Content-Length: " . filesize(DATA_LOCATION . $avatar_links['public']));

    fpassthru($fp);
    exit;
}
