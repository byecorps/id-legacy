<?php

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
