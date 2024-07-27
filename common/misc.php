<?php

/**
 * Redirects to $url.
 *
 * @param $url string
 *
 */
function location(string $url):void
{
    header('Location: '. $url);
    exit();
}

function flash(string $text, string $type, array &$flash) {
    $flash[] = ['text' => $text, 'type' => $type];
}
