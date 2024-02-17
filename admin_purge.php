<?php


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if ($_POST['purge'] == 'purge') {
        db_execute("DELETE FROM `password_resets` WHERE expiration < ?", [time()]);
        db_execute("DELETE FROM `tokens` WHERE expiry < ?", [time()]);
    }
}

$expired_password_resets = db_execute("SELECT * FROM `password_resets` WHERE expiration < ?", [time()]);
$expired_tokens = db_execute("SELECT * FROM `tokens` WHERE expiry < ?", [time()]);

?>



<h1>Purge</h1>
<form method="post">
    <p>
        <button name="purge" value="purge" type="submit" class="primary">Purge</button>
    </p>
</form>

<h2>Expired password resets</h2>
<pre><?php print_r($expired_password_resets) ?></pre>

<h2>Expired Login tokens</h2>
<pre><?php print_r($expired_tokens) ?></pre>
