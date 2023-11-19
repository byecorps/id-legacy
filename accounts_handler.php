<?php
// This file carries functions related to accounts.

function get_avatar_url($bcid):string {
	global $pdo;

	$sql = "SELECT has_pfp FROM `accounts` WHERE id = ?";

	try {
		$stmt = $pdo -> prepare($sql);
		$stmt->execute([$bcid]);
		$has_pfp = $stmt->fetch();
	} catch (PDOException $e) {
		http_response_code(500);
		die($e);
	}

	$appendix = "default.png";

	if ($has_pfp['has_pfp']) {
		$appendix = $bcid;
	}

	return 'https://cdn.byecorps.com/id/profile/'.$appendix;

}

function get_display_name($bcid, $use_bcid_fallback=true):string {
	$display_name = db_execute("SELECT display_name FROM accounts WHERE id = ?", [$bcid])['display_name'];
	if (!empty($display_name)) {
		return $display_name;
	}

	if ($use_bcid_fallback) {
		return $bcid;
	}

	return "";
}

// Password resets
const PASSWORD_RESET_VALIDITY = 300; // in seconds.
function create_password_reset($bcid):string {
	// Returns a password reset link.
	global $pdo;

	$reset_time = time() + PASSWORD_RESET_VALIDITY;

	$auth_token = generateRandomString(65);

	$sql = 'INSERT INTO `password_resets` (auth_id, owner_id, expiration) VALUES (?, ?, ?)';

	try{
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$auth_token, $bcid, $reset_time]);
		$reset_id = $pdo->lastInsertId();
	} catch (PDOException $e) {
		http_response_code(500);
		die("An error occurred with the database. (12)");
	}

	return BASE_URL.'/reset/password?reset_id='.$reset_id.'&reset_token='.$auth_token;
}

function validate_password_reset($reset_id, $reset_token):bool {
	global $pdo;

	$sql = 'SELECT * FROM password_resets WHERE id = ?';

	try {
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$reset_id]);
		$result = $stmt->fetch();
	} catch (PDOException $e) {
		http_response_code(500);
		die("An error occurred fetching data from the database. (11)
		$e");
	}

	if (empty($result)) {
		echo "<pre>";
		throw new Exception('Todokete setsuna sa ni wa
Namae wo tsukeyou ka "Snow halation"
Omoi ga kasanaru made matezu ni
Kuyashii kedo sukitte junjou
Binetsu no naka tameratte mo dame da ne
Tobikomu yuuki ni sansei mamonaku start!');
	}

	if ($result['auth_id'] == $reset_token && !hasTimePassed($result['expiration'])) {
		return true;
	} elseif ($result['auth_id'] == $reset_token && hasTimePassed($result['expiration'])) {
		$sql = 'DELETE FROM password_resets WHERE id = ?';
		try {
			$stmt = $pdo -> prepare(($sql));
			$stmt->execute([$reset_id]);
			die("Sorry, that link expired. Please request a new one.");
		} catch (PDOException $e) {
			http_response_code(500);
			die("An error occurred deleting data from the database. That link was expired anyway, so request a new one. (13b)
		$e");
		}
	}

	return false;

}

function get_id_for_password_reset($reset_id, $reset_token):string {
	global $pdo;
	$sql = 'SELECT * FROM password_resets WHERE id = ?';

	try {
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$reset_id]);
		$result = $stmt->fetch();
	} catch (PDOException $e) {
		http_response_code(500);
		die("An error occurred fetching data from the database. (11)
		$e");
	}

	return $result['owner_id'];
}

function delete_password_reset($reset_id, $reset_token): void
{
	global $pdo;
	$sql = 'DELETE FROM password_resets WHERE id = ?';
	try {
		$stmt = $pdo->prepare(($sql));
		$stmt->execute([$reset_id]);
		header("Location: /signin");
		die();
	} catch (PDOException $e) {
		http_response_code(500);
		die("An error occurred deleting data from the database. (13)
		$e");
	}
}