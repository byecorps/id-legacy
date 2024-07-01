<?php

$error_body = $error_body ?? "No message provided.";

?>

<div class="errorbox">
    <div class="icon">
        <span class="fa-solid fa-fw fa-circle-xmark"></span>
    </div>
    <h2><?= get_string("generic.error") ?></h2>
    <p><?= htmlspecialchars($error_body) ?></p>
</div>
