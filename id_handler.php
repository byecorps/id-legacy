<?php

function ganerate_bcid() {
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

$BCID = ganerate_bcid();

echo "<pre>";
echo "Random BCID (unformatted): $BCID
";
echo "Check if BCID is valid: ".validate_bcid($BCID)."
";

if ($query['bcid']) {
    echo "BCID provided in the query: ".$query['bcid']."
";
    echo "Checking the BCID provided in the query: ".validate_bcid($query['bcid'])."
";
}

?>