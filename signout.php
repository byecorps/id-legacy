<?php 

$_SESSION['id'] = null;
$_SESSION['auth'] = false;
setcookie('keep_me_logged_in', '', time()-3600);
session_destroy();

?>

<p>You've been signed out successfully. You may close the page.</p>
<p><a href="/signin">Sign back in</a> ~ <a href="/">Go to home</a></p>
