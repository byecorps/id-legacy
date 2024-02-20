<?php

function generate_app_id(): int
{
	return mt_rand(100000000, 999999999);
}

function check_app_id($app_id): bool
{
    $app = db_execute("SELECT * FROM apps WHERE id = ? LIMIT 1", [$app_id]);
    return empty($app);
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $app_id = generate_app_id();
    db_execute("INSERT INTO apps (id, owner_id, title, description, type, callback) VALUES (?, ?, ?, ?, ?, ?)", [$app_id, $_POST['owner'], $_POST['title'], $_POST['description'], $_POST['type'], $_POST['callback']]);
	die();
}

?>

<h1>App Creator</h1>

<form method="post">
	<label for="title">Title</label>
	<input type="text" name="title" id="title">
	<label for="description">Description</label>
	<textarea name="description" id="description" cols="30" rows="10"></textarea>
	<label for="owner">App owner</label>
	<select name="owner" id="owner">
		<?php
			$users = db_query("SELECT * FROM accounts");
			foreach ($users as $row) {
				echo "<option value='".$row['id']."'>".get_display_name($row['id'])." (".$row['id'].") </option>";
			}
		?>
	</select>
    <label for="type">App type</label>
    <select name="type" id="type">
        <option value="null">None</option>
        <option value="basic_login">Basic login</option>
    </select>
    <label for="callback">Callback</label>
    <input type="url" id="callback" name="callback" />
	<button type="submit" class="primary">Create app</button>
</form>