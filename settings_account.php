<?php

if (empty($_SESSION)) {
    http_response_code(307);
    header('Location: /signin?callback=/dashboard');
    exit();
}
if (!$_SESSION['auth']) {
    http_response_code(307);
    header('Location: /signin?callback=/dashboard');
    exit;
}

?>

<link href="/styles/settings.css" rel="stylesheet" />

<div id="settings_split">
    <div id="mini_profile" class="left">
        <div class="image_container" data-backgroundcolour="white">
            <img src="<?= get_avatar_url($user['id']) ?>"  alt="<?= htmlspecialchars(get_display_name($user['id'])) ?>'s avatar"/>
        </div>
        <div class="texts">
            <span class="displayname"><?= htmlspecialchars(get_display_name($user['id'], false)) ?></span>
            <span class="id bcid"><?= format_bcid($user['id']) ?></span>
        </div>
    </div>

    <div class="other">
        <h1>Account</h1>
        <form method="post">
            <div class="container">
                <label for="display_name"><span id="display_name_label"></span> Display name</label>
                <input type="text" name="display_name" id="display_name" data-field="account.display_name" placeholder="<?= htmlspecialchars(format_bcid($user['id'])) ?>" value="<?= htmlspecialchars($user['display_name']) ?>">
            </div>

            <button type="submit">Save</button>
        </form>
    </div>
</div>

<style>
    /*form label {*/
    /*    padding-right: 1rem;*/
    /*}*/
</style>

<script>
    const display_name_box = document.getElementById("display_name");

    async function updateJsonSettings(data) {
        const response = await fetch("https://id.byecorps.com/api/settings", {
            method: "POST", // or 'PUT'
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(data),
        });

        const result = await response.json();
        console.log("Success:", result);
        return result;
    }

    display_name_box.onchange = _ => {
        document.getElementById("display_name_label").classList = "fa-solid fa-spinner fa-spin-pulse";

        updateJsonSettings({
            account: {
                display_name: display_name_box.value,
            }
        }).then(_=>{
            document.getElementById("display_name_label").classList = "fa-solid fa-check";
        }).catch(_=>{
            document.getElementById("display_name_label").classList = "fa-solid fa-triangle-exclamation";
        });

    }
</script>
