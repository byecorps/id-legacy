<?php 

$_SESSION['id'] = null;
$_SESSION['auth'] = false;
session_destroy();

?>

<p>You've been signed out successfully. You may close the page.</p>
<p><a href="/signin">Sign back in</a> ~ <a href="/">Go to home</a></p>
