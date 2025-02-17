<?php

function csfr(): string
{
    $token = bin2hex(random_bytes(32));
    $_SESSION['CSFR_TOKEN'] = $token;
    return $token;
}

function csfr_input($echo = false): string
{
    $token = csfr();
    $output = "<input type='hidden' name='CSFR_TOKEN' value='$token' />";
    if ($echo) echo $output;
    else return $output;
}

function validate_csfr($token = null): bool
{
    $token = $token ?: $_REQUEST['CSFR_TOKEN'];

    if ($_SESSION['CSFR_TOKEN'] == $token) {
        return true;
    }

    return false;
}

function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Password rules:
 * - At least 8 characters long
 * That's it
 */
function validate_password($password) {
    $password_min_length = 8;
    if (strlen($password) >= $password_min_length) {
        return $password;
    }
    return false;
}
