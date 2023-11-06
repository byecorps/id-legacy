<?php 

if ($_SESSION['id'] != "281G3NV") {
    http_response_code(401);
    die("<img src='https://http.cat/401.jpg'>");
}

$sql = "SELECT * FROM accounts";
$result = $pdo-> query($sql);
if (!$result) {
    http_response_code(500);
    die("<img src='https://http.cat/500.jpg'>");
}


$count_req = $pdo->query("SELECT COUNT(*) FROM accounts");
$count = $count_req->fetchColumn();


?>

<h2 class="subheading">Admin</h2>
<h1>Accounts</h1>

<p>There is currently <?= $count ?> accounts registered.</p>


<ul>
    <?php
    foreach ($result as $row) {
        echo "<li><pre>";
        print_r($row);
        echo "</pre><p><a href='/admin/signinas?id=".$row['id']."'>Sign in as ".$row['display_name']."</a></li>";
    }
    ?>
</ul>
