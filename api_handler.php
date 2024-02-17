<?php

$output_format = "json";
header('Content-type: application/json');

if (array_key_exists('HTTP_AUTHORIZATION', $_SERVER)) {
    $access_token = str_replace("Bearer ", "", $_SERVER['HTTP_AUTHORIZATION']);
}

if (!empty($access_token)) {
    // Check who the access token belongs to
    $token = db_execute("SELECT * FROM tokens WHERE access_token = ?", [$access_token]);
    // if the token doesn't exist...
    if (empty($token)) {
        $invalid_token = true; // We won't tell this to the end-user immediately because I'd prefer to tell them about
                                // 404 first.
    } else {
        $token_owner = $token['owner_id'];
    }
}

function check_authorisation($token): int
{
    // Validate token
    if (!validate_access_token($token)) {
        return 0; // Unauthorised
    }

    // Check the type of token
    $token_row = db_execute("SELECT * FROM tokens WHERE access_token = ?", [$token]);

    if (null == $token_row) {
        return 0;
    }

    return match ($token_row['type']) {
        "basic" => 1,
        default => 0,
    };
}

// Misc (unauthorised)

function redirect_to_documentation(): void
{
    header('Location: /docs/api');
}

// Health check

function api_health_check(): array
{
    return ["message" => "Science compels us to explode the sun!", "time" => time(), "response_code" => 200];
}

// User (REQUIRES AUTHORISATION)

function api_user_info() {
    global $access_token, $token_owner;
    // Authorisation levels:
    // `display_name`   = 1 (basic)
    // `id`             = 1 (basic)
    // `email`          = 1 (basic)

    $level = check_authorisation($access_token);

    $data = null;

    if ($level == 1) {
        $data = db_execute("SELECT id, email, display_name FROM accounts WHERE id = ? LIMIT 1", [$token_owner]);
    }

    if (null != $data) {
        return [
            "response_code" => 200,
            "id" => $data['id'],
            "email" => $data['email'],
            "display_name" => $data['display_name']
        ];
    }

    http_response_code(401);
    return [
        "response_code" => 401,
        "message" => "Unauthorized."
    ];

}

$api_routes = [ // base url is base_url.'/api'
    // "/path" => "function_name"
    // Misc
    "" => "redirect_to_documentation",
    "/status" => "api_health_check",

    // Account stuff
    "/account/me" => "api_user_info"
];

$path = str_replace("/api", "", $path);

if (isset($api_routes[$path])) {
    if (isset($invalid_token)) {
        http_response_code(498);
        echo (json_encode([
            "response_code" => "498",
            "message" => "Token expired or invalid."
        ]));
    }
    echo json_encode($api_routes[$path]());
} else {
    http_response_code(404);
    echo (json_encode([
        "response_code" => "404",
        "message" => "Route not found."
    ]));
}
