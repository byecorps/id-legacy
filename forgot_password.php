<?php

if ($_SESSION['auth']) {
    header('Location: /account');
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $message = "We've sent an email to that inbox if we find an associated account.";
    $sql = "SELECT * FROM accounts WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_POST['email']]);
    $user = $stmt->fetch();

    if ($user != null) { // account exists

        // create a password reset
        $password_reset_link = create_password_reset($user['id']);

        $safe_display_name = get_display_name($user['id'], use_bcid_fallback: true);


        try {
            $resend->emails->send([
                'from' => 'ByeCorps ID <noreply@id.byecorps.com>',
                'to' => [$safe_display_name . "<" . $user['email']. ">"],
                'subject' => 'Reset your password',
                'text' => 'Hey there '.$safe_display_name.'! Here is that password reset you requested. Just click the following link and you\'ll be sorted:
'.$password_reset_link.'

This link expires in 5 minutes.

If you did not request this password reset, please ignore it (or tighten your account\'s security)']);

//            echo("<a href='$password_reset_link'>This is a security issue.</a>");
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: $e";
        }

    } 
}

?>

<h1>Forgot password</h1>

<?php if(isset($message)) echo "<p>".$message."</p>"; ?>

<p>Forgot your password? We'll email you to reset it.</p>

<form method="post">
    <input placeholder="a.dent@squornshellous.cloud" name="email" id="email" type="email">
    <button type="submit">Request password reset</button>
</form>