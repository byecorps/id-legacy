<?php

$sql = "SELECT * FROM accounts";
$result = $pdo-> query($sql);
if (!$result) {
    http_response_code(500);
    die("<img src='https://http.cat/500.jpg'>");
}

$count_req = $pdo->query("SELECT COUNT(*) FROM accounts");
$count = $count_req->fetchColumn();


?>

<h1>Accounts</h1>

<p>There is currently <?= $count ?> accounts registered.</p>


<ul>
    <?php
    foreach ($result as $row) {
        echo "<li>";
        echo $row['id'];
        echo "<p><a href='/admin/signinas?id=".$row['id']."'>Sign in as ".htmlspecialchars($row['display_name'])."</a></li>";
    }
    ?>
</ul>
