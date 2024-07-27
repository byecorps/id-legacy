<?php

function generate_bcid($duplicate_check=false): string
{
    $CHARS = str_split("ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890");
    $bcid = $CHARS[array_rand($CHARS)].$CHARS[array_rand($CHARS)].$CHARS[array_rand($CHARS)].$CHARS[array_rand($CHARS)].$CHARS[array_rand($CHARS)].$CHARS[array_rand($CHARS)].$CHARS[array_rand($CHARS)];

    if ($duplicate_check) {
        $same_accounts = db_execute('SELECT id FROM accounts WHERE id = ? LIMIT 1', [$bcid]);
        if ($same_accounts) {
            $bcid = generate_bcid(true);
        }
    }

    return $bcid;
}

function validate_bcid($bcid): bool
{
    $stripped_bcid = str_replace([" ", "-"], "", $bcid);
    $stripped_bcid = strtoupper($stripped_bcid);

    if (!preg_match('/^[^A-Z^0-9]^/', $stripped_bcid) && strlen($stripped_bcid) == 7) {
        return true;
    }

    return false; // fail condition
}

function format_bcid ($bcid): string
{ // Formats to XXX-XXXX
    $stripped_bcid = str_replace([' ','-'], '', $bcid);
    $stripped_bcid = strtoupper($stripped_bcid);

    if (!validate_bcid($stripped_bcid)) {
        return '999-9999';
    }

    return substr($stripped_bcid, 0, 3).'-'.substr($stripped_bcid, -4, 4);
}

function get_user_by_id($bcid) {
    return db_execute('SELECT * FROM accounts WHERE id = ? LIMIT 1', [$bcid]);
}

function get_user_display_name($userId, $escape = true) {
    global $user;

    $target = array();
    if ($userId == $user['id']) {
        $target = $user;
    } else {
        $target = get_user_by_id($userId);
    }

    if (is_null($user['display_name'])) {
        try {
            return format_bcid($user['id']);
        } catch (Exception $e) {
            return 'Invalid BCID';
        }
    }

    $display_name = $user['display_name'];
    if ($escape) {
        $display_name = htmlspecialchars($display_name);
    }

    return $display_name;
}

function get_user_avatar($userId) {
    global $user;

    if (!$_SESSION['auth']) {
        return 'https://cdn.id.byecorps.com/profile/default.png';
    }

    $target = array();
    if ($userId == $user['id']) {
        $target = $user;
    } else {
        $target = get_user_by_id($userId);
    }

    $avatar = db_execute('SELECT * FROM avatars JOIN neo_id.files f on f.id = avatars.file_id WHERE avatars.owner = ?',
                        [ $target['id'] ]);

    if ($avatar) {
//        echo '<pre>'; print_r($avatar); echo '</pre>';
        return 'https://cdn.id.byecorps.com/' . $avatar['path'];
    }

    return 'https://cdn.id.byecorps.com/profile/default.png';
}

function set_user_language(string $lang_code, string $id): void
{
    db_execute(
        'UPDATE accounts SET language = ? WHERE id = ?',
        [$lang_code, $id]
    );
    $_SESSION['lang'] = $lang_code;
}

function requires_auth($redirect = '/auth/login') {
    global $path_raw;

    if ($_SESSION['auth']) {
        return true;
    }

    http_response_code(307);
    header('Location: '.$redirect.'?callback='.urlencode($path_raw));
    exit();
}

function requires_admin() {
    global $user;

    if ($user['is_admin']) {
        return true;
    }

    return false;
}
