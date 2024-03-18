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

function check_authorisation($token=""): int
{
    global $token_owner;
    // Validate token
    if (!validate_access_token($token) && "" != $token) {
        echo "invalid";
        return 0; // Unauthorised
    }

    // Check the type of token
    $token_row = db_execute("SELECT * FROM tokens WHERE access_token = ?", [$token]);

    if (null == $token_row) {
        if (array_key_exists('auth', $_SESSION)) {
            if ($_SESSION['auth']) {
                $token_row = [
                    "type" => "dangerous"
                ];
                $token_owner = $_SESSION['id'];
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    return match ($token_row['type']) {
        "dangerous" => 22,
        "basic"     => 1,
        default     => 0,
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

// Potentially authenticated image endpoints

function get_avatar(): array
{
    if (!array_key_exists('id', $query)) {
        return [
            'response_code' => 404,
            'message' => 'ID not assigned/found'
        ];
    }
    $user_id = $query['id'];
    return [];
}

// User (REQUIRES AUTHORISATION)

function api_user_info(): array
{
    global $access_token, $token_owner;
    // Authorisation levels:
    // `display_name`   = 1 (basic)
    // `id`             = 1 (basic)
    // `email`          = 1 (basic)

    $level = check_authorisation($access_token);

    $data = null;

    if ($level >= 1) {
        $data = db_execute("SELECT id, email, display_name FROM accounts WHERE id = ? LIMIT 1", [$token_owner]);
    } if ($level == 22) {
        $data = db_execute("SELECT * FROM accounts WHERE id = ? LIMIT 1", [$token_owner]);
        unset($data['password']);
    }

    if (null != $data) {
        return [
            "response_code" => 200,
            "data" => $data
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
    "/account/me" => "api_user_info",

    // Get avatar
    "/avatars/get" => "get_avatar"
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
    $response = $api_routes[$path]();
    if (array_key_exists('response_code', $response)) {
        http_response_code($response['response_code']);
    }
    echo json_encode($response);
} else {
    http_response_code(404);
    echo (json_encode([
        "response_code" => "404",
        "message" => "Route not found."
    ]));
}
