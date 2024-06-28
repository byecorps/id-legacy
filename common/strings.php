<?php

function get_string($key="generic.generic") {
    global $LANG;

    $key_path = explode('.', $key);

    $result = $LANG;

    foreach ($key_path as $k) {
        if (isset($result[$k])) {
            $result = $result[$k];
        } else {
            return $key;
        }
    }

    return $result;
}

function patch_lang($lang="en") {

    global $LANG, $DOC_ROOT;

    $temp = $LANG;

    if (file_exists("$DOC_ROOT/strings/$lang.php")) {
        require_once("$DOC_ROOT/strings/$lang.php");
    }

    function merge_arrays($original, $new) {
        foreach ($new as $key => $value) {
            if (is_array($value)) {
                if (!isset($original[$key])) {
                    $original[$key] = [];
                }
                $original[$key] = merge_arrays($original[$key], $value);
            } else {
                // Replace only if the value is not blank
                if ($value !== '') {
                    $original[$key] = $value;
                }
            }
        }
        return $original;
    }

    $LANG = merge_arrays($temp, $LANG);
}
