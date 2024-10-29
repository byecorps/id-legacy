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

function flash(string $text, array &$flash, string $type="warning") {
    $flash[] = ['text' => $text, 'type' => $type];
}

function show_flash(array $flash) {
    $output = '<ul class="flash">';
    foreach ($flash as $item) {
        $output .= '<li>'. $item['text'] .'</li>';
    }
    return $output;
}
