
<?php 

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
        mail($user['email'], "ByeCorps ID Password Reset Confirmation", "The email was sent!");
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