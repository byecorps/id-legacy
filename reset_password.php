<?php
if (!empty($query)) {
	$reset_id = $query['reset_id'];
	$reset_token = $query['reset_token'];
} else {
	die("Invalid URL.");
}


$password_valid = validate_password_reset($reset_id, $reset_token);

if ($password_valid) {
	echo "Valid url. You may reset!";
} else {
	die("This incident will be reported.");
}
