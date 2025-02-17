<?php

const LANGAUGES = [
    [
        "code" => 'en',
        "name" => "English (Traditional)",
        "flag" => "uk"
    ],
    [
        'code' => 'en_US',
        "name" => 'English (Simplified)',
        'flag' => 'usa'
    ],
    [
        'code' => 'fi',
        'name' => 'suomi'
    ],
    [
        'code' => 'ga',
        'name' => 'Gaeilge',
        'flag' => 'ie'
    ],
    [
        'code' => 'nb_NO',
        'name' => 'Norsk bokmål',
        'flag' => 'no'
    ],

    // Joke languages
    [
        'code' => 'en_UWU',
        'name' => 'Cute English',
        'flag' => 'owo'
    ],
];

function get_string($key="generic.generic", $substitutes=[]) {
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

    if (count($substitutes) > 0) {
        foreach ($substitutes as $key => $substitute) {
            $re = '/{{('. $key .')}}/';
            $subst = $substitute;

            $result = preg_replace($re, $subst, $result, 1);
        }
    }

    return $result;
}

function get_language_code_based_on_browser_locale(): string
{
    $locale = locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE']);

    $locales = [ // Converts locale to its respective language.
        'en_GB' => 'en',
        'en_IE' => 'en',
        'en' => 'en',
    ];

    if (array_key_exists($locale, $locales)) {
        return $locales[$locale];
    }

    if (str_starts_with("en", $locale)) {
        return "en";
    }

    return $locale;
}

function patch_lang($lang="en"): void
{

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
