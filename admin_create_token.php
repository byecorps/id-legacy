<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    $token = generate_token($_POST['owner'], $_POST['application'], $_POST['permissions']);

    echo "<p>Created token. Access token: <code>". $token['access'] ."</code></p>";
}

?>

<h1>Token generator</h1>

<form method="post">
    <div class="container">
        <label for="owner">Token owner</label>
        <select name="owner" required id="owner">
            <?php
            $users = db_query("SELECT * FROM accounts");
            foreach ($users as $row) {
                echo "<option value='".$row['id']."'>".get_display_name($row['id'])." (".$row['id'].") </option>";
            }
            ?>
        </select>
    </div>
    <div class="container">
        <label for="app">Token app</label>
        <select name="app" id="app">
            <option value="null">None</option>
            <?php
            $users = db_query("SELECT * FROM apps");
            foreach ($users as $row) {
                echo "<option value='".$row['id']."'>". $row['title'] ."</option>";
            }
            ?>
        </select>
    </div>

    <input type="hidden" id="permissions" name="permissions" value="0" />

    <h2>Permissions</h2>
    <p>Permission number: <span id="permissionnumber"></span></p>
    <div class="checkboxes container">
        <input type="checkbox" id="account.email" value="1" /><label for="account.email"><code>account.email</code></label>
        <input type="checkbox" id="account.settings" value="2" /><label for="account.settings"><code>account.settings</code></label>
    </div>

    <button type="submit">Generate!</button>
</form>

<style>
    form .container {
        display: unset;
    }
</style>

<script>
    const displayNumber = document.getElementById("permissionnumber");
    const permissionsInput = document.getElementById("permissions");
    const checkboxes = document.querySelectorAll("input[type='checkbox']");

    console.log(checkboxes);

    function updateCheckboxes() {
        let permissions = 0;

        for (let checkbox of checkboxes) {
            if (checkbox.checked) {
                permissions += Number(checkbox.value);
            }
        }

        displayNumber.innerText = permissions.toString();
        permissionsInput.value = permissions;
    }

    for (let checkbox of checkboxes) {
        checkbox.onchange = updateCheckboxes;
    }

    updateCheckboxes();

</script>
