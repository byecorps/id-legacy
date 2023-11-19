<?php

$result = db_query("SELECT * FROM apps");

$count_req = db_query("SELECT COUNT(*) FROM apps");
$count = $count_req->fetchColumn();

?>
<h1>Apps</h1>

<p>There is currently <?= $count ?> apps registered.</p>


<ul>
    <?php
    foreach ($result as $row) {
        echo "<li><pre>";
        print_r($row);
		echo "</li>";
    }
    ?>
</ul>

