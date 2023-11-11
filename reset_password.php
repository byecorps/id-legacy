<?php
global $pdo;
if (!empty($query)) {
	$reset_id = $query['reset_id'];
	$reset_token = $query['reset_token'];
} else {
	die("Invalid URL.");
}


$password_valid = validate_password_reset($reset_id, $reset_token);

if (!$password_valid) {
	die("This incident will be reported.");
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $password = $_POST['password'];
    $repeat_password = $_POST['repeat_password'];
    $bcid = get_id_for_password_reset($reset_id, $reset_token);

    if ($password == $repeat_password) {
        $new_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = 'UPDATE accounts SET password = ? WHERE id = ?';

        try {
	        $pdo->prepare($sql)->execute([$new_password, $bcid]);
        } catch (PDOException $e) {
            die ($e);
        }

        // delete the password reset stuff
        delete_password_reset($reset_id, $reset_token);
    }
}

?>

<p>Please submit your new password:</p>
<form method="post">
    <label for="password">New password</label>
	<input type="password" name="password" id="repeat_password">
    <label for="repeat_password">Repeat new password</label>
	<input type="password" name="repeat_password" id="repeat_password">
	<button type="submit">Reset password</button>
</form>
