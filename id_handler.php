<?php

function generate_bcid() {
    $CHARS = str_split("ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890");
    return $CHARS[array_rand($CHARS)].$CHARS[array_rand($CHARS)].$CHARS[array_rand($CHARS)].$CHARS[array_rand($CHARS)].$CHARS[array_rand($CHARS)].$CHARS[array_rand($CHARS)].$CHARS[array_rand($CHARS)];
}

function validate_bcid($bcid) {
    $stripped_bcid = str_replace([" ", "-"], "", $bcid);
    $stripped_bcid = strtoupper($stripped_bcid);

    if (!preg_match('/^[^A-Z^0-9]^/', $stripped_bcid) && strlen($stripped_bcid) == 7) {
        return 1;
    }

    return 0; // fail condition
}

function format_bcid ($bcid) { // Formats to XXX-XXXX
    $stripped_bcid = str_replace([' ','-'], '', $bcid);
    $stripped_bcid = strtoupper($stripped_bcid);

    if (!validate_bcid($stripped_bcid)) {
        throw new Exception('Invalid BCID.');
    }

    return substr($stripped_bcid, 0, 3).'-'.substr($stripped_bcid, -4, 4);
}


$BCID = generate_bcid();
?>