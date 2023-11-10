<?php
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_SESSION['auth'])) {
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

        try {
            $safe_display_name = format_bcid($user['id']);
        } catch (Exception $e) {
            die("Bad BCID.");
        }

        if ($user['display_name'] != '') {
            $safe_display_name = $user['display_name'];
        }

        $mail = new PHPMailer();

        try {
            //Server settings
//            $mail->SMTPDebug = SMTP::DEBUG_SERVER; Verbose output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = MAIL_HOST;                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = MAIL_USERNAME;                     //SMTP username
            $mail->Password   = MAIL_PASSWORD;                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;

            $mail->setFrom('id@byecorps.com', 'ByeCorps ID');
            $mail->addAddress($user['email'], $safe_display_name);
            $mail->addReplyTo('hello@byecorps.com', 'ByeCorps Support');

            $mail->Subject = 'Reset your password';
            $mail->Body    = 'Hey there '.$safe_display_name.'! Here is that password reset you requested. Just click the following link and you\'ll be sorted:
'.$password_reset_link.'
This link expires in 5 minutes.';

            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

    } 
}

?>

<h1>Forgot password</h1>

<?php if(isset($message)) echo "<p>".$message."</p>"; ?>

<p>Forgot your password? We'll send you an email to reset it.</p>

<form method="post">
    <input placeholder="a.dent@squornshellous.cloud" name="email" id="email" type="email">
    <button type="submit">Request password reset</button>
</form>