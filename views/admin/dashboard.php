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
    <title>[A] Dashboard ~> ByeCorps ID</title>
</head>
<body>
<?php include $DOC_ROOT.'/views/partials/header.php' ?>
<main>
    <h1>[ADMIN] Dashboard</h1>

    <nav>
        <ul>
            <li>
                <a href="/admin/files">Manage files</a>
            </li>
            <li>
                <a href="/admin/applications">Manage applications</a>
            </li>
        </ul>
    </nav>

</main>
<?php include $DOC_ROOT.'/views/partials/footer.php' ?>
</body>
</html>
