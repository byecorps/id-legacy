<?php 

if ($_SESSION['auth']) {
    header('Location: /account');
}

if (isset($query['callback'])) {
    $message = "You must sign in to continue.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM accounts WHERE email = :email";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array("email"=> $email));
        $user = $stmt->fetch();
    }
    catch (PDOException $e) {
        die ("Something happened: ". $e->getMessage());
    }

    if (password_verify($password, $user["password"])) {
        $_SESSION["id"] = $user["id"];
        $_SESSION["auth"] = true;
        if (isset($query['callback'])) {
            header("Location: ".$query['callback']);
        } else {
            header("Location: /account");
        }

        exit;
    } else {
        $message = "Email or password incorrect.";
    }
}

?>


<h2>Sign in to ByeCorps ID</h2>
<?php 
if (isset($message)) {
    echo "<div class='flash'>$message</div>";
}?>
<form method="post">
    <input type="email" name="email" id="email" placeholder="Email">
    <input type="password" name="password" id="password" placeholder="Password">
    <button type="submit">Sign in</button>
</form>

<p class="center">
    <!--<a href="/forgot_password">Forgot password?</a> ·--> New? <a href="/register">Register</a> for a ByeCorps ID.
</p>