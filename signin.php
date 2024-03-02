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
//
//        print_r($_POST);
//        echo(is_string($_POST['keep_logged_in']));

        if (array_key_exists('keep_logged_in', $_POST)) {
            if ($_POST['keep_logged_in'] == "on") {
                $token = generate_cookie_access_token($user['id']);
//            print_r($token);
                setcookie("keep_me_logged_in", $token['access']);
            }
        }

//
        if (isset($query['callback'])) {
            header("Location: ".$query['callback']);
        } else {
            header("Location: /profile");
        }

        exit;
    } else {
        $message = "Email or password incorrect.";
    }
}

?>


<div id="loginform">
    <h2>Sign in to ByeCorps ID</h2>
    <?php
    if (isset($message)) {
        echo "<div class='flash'>$message</div>";
    }?>
    <form class="login" method="post">
        <input type="email" required name="email" id="email" placeholder="Email" />
        <input type="password" required name="password" id="password" placeholder="Password" />
        <div class="checkbox"><input type="checkbox" name="keep_logged_in" id="keep_logged_in" />
            <label for="keep_logged_in">Keep me logged in (for 365 days)</label></div>
        <button class="primary" type="submit">Sign in</button>
    </form>

    <p class="center">
        <a href="/forgot/password">Forgot password?</a> &bull; New? <a href="/signup">Sign up</a> for a ByeCorps ID.
    </p>
</div>
