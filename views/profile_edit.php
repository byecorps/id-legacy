
<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mime_avatar = mime_content_type($_FILES['avatar']['tmp_name']);
    if (str_contains($mime_avatar, 'image')) {
        echo $mime_avatar;
        $new_avatar = upload_avatar($_FILES['avatar'], $user);
    }

//    location('/profile');
}


?>

<!doctype html>
<html lang="en">
<head>
    <?php include "partials/head.php" ?>
</head>
<body>
<?php include "partials/header.php" ?>

<main>

    <h1>Editing profile</h1>

    <form method="post" enctype="multipart/form-data">
        <fieldset>
            <legend>Images</legend>

            <label for="avatar">Avatar</label>
            <input type="file" accept="image/*" name="avatar" id="avatar" />
            
        </fieldset>

        <button type="submit"><?= get_string('button.submit') ?></button>
    </form>

</main>

<?php include 'partials/footer.php' ?>

</body>
</html>
