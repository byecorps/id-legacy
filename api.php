<?php

header('Content-Type: application/json');

$response = [
    "response" => [
        "success" => false,
        "status_code" => 404
    ],
    'body' => [
        'message' => 'That endpoint could not be found.'
    ]
];

echo json_encode($response, JSON_PRETTY_PRINT);
