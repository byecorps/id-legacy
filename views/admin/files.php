<?php

if (!requires_admin()) {exit;} // failsafe in case this file is opened from "not the index".

if (isset($query['delete'])) {
    delete_file_by_id($query['delete']);
}

$files = db_execute_all('SELECT * FROM files');

?>

<!doctype html>
<html>
    <head>
        <?php include $DOC_ROOT.'/views/partials/head.php' ?>
        <title>[A] Files ~> ByeCorps ID</title>
    </head>
    <body>
        <?php include $DOC_ROOT.'/views/partials/header.php' ?>
        <main>
            <h1>[ADMIN] Files</h1>
            <p>There are <?= count($files) ?> files.</p>

            <ul>
                <?php
                    foreach ($files as $file) {
                        echo '<li>';

                        if ($file['blurhash']) {
                            echo '<img src="https://cdn.id.byecorps.com/'. $file['path'] .'" />';
                        }

                        echo '<p><a href="https://cdn.id.byecorps.com/'.$file['path'].'">ID: '.$file['id'].' ~~ '.$file['path'].'</a></p>';

                        if ($file['uploader']) {
                            echo '<p>Owned by <b>'. get_user_display_name($file['uploader']) .'</b></p>';
                        } else {
                            echo '<p>No owner on file</p>';
                        }

                        echo '<p>Uploaded on <b>'. $file['uploaded_date'] .'</b></p>';

                        $avatar = db_execute('select * from avatars where file_id = ?', [$file['id']]);
                        if (!empty($avatar)) {
                            echo '<p>Is ' . get_user_display_name($avatar['owner']) . '\'s avatar</p>';
                        }

                        echo '<p>Options: <a href="?delete='. $file['id'] .'">Delete</a></p>';

                        echo '</li>';
                    }
                ?>
            </ul>

        </main>
        <?php include $DOC_ROOT.'/views/partials/footer.php' ?>
    </body>
</html>
