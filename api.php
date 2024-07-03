<?php

header('Content-Type: application/json');

$routes = [
    '' => function () {
        header('Location: /api/status');
        exit();
    },

    'status' => function () {
        http_response_code(200);

        return [
            'response' => [
                'success' => true,
                'status_code' => 200
            ],
            'body' => [
                'message' => 'Science compels us to explode the sun!',
                'time' => time()
            ]
        ];
    }
];

if (array_key_exists($path[1], $routes)) {
    $res = $routes[$path[1]]();
    echo json_encode($res, JSON_PRETTY_PRINT);
} else {
    echo json_encode([
        "response" => [
            "success" => false,
            "status_code" => 404
        ],
        'body' => [
            'message' => 'That endpoint could not be found.'
        ]
    ], JSON_PRETTY_PRINT);
}
