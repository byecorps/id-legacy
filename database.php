<?php
// Functions for interacting with the database. Requires PDO is initialised at $pdo.

function db_execute($sql, $variables=[]) {
	global $pdo;

	$stmt = $pdo->prepare($sql);
	$stmt->execute($variables);
	return $stmt->fetch();

}

function db_query($sql) {
	global $pdo;

	return $pdo->query($sql);
}
