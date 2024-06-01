<?php 


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $DB_SERVER = DB_ADDRESS;
    $DB_USER = DB_USERNAME;
    $DB_PASSWD = DB_PASSWORD;
    $DB_BASE = DB_DATABASE;

    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $BCID = generate_bcid();
    if (!validate_bcid($BCID)) {
        die("Server-side error with your BCID #. Try again.");
    }

    // First: check if restraints will be broken
    $sql = "SELECT * FROM accounts WHERE email = ?";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
	    $result = $stmt->fetch();

        if (!empty($result)) {
            die("Email is already registered. (923)");
        }
    } catch (PDOException $e) {
	    http_response_code(500);
	    die("An error occured: $e");
    }
    
    try {
        $sql = "INSERT INTO `accounts` (`id`, `email`, `password`, `verified`) VALUES (?, ?, ?, ?)";
        try{
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$BCID, $email, $password, 0]);
            $result = $stmt->fetch();
            echo "You've signed up!";
        } catch (PDOException $e) {
                http_response_code(500);
                die("An error occured: $e");
        }
    } 
    catch(PDOException $e) {
        die ("Connection failed: " . $e->getMessage());
    }
    
    $_SESSION["auth"] = true;
    $_SESSION["id"] = $BCID;

    exit;
}

?>

<h2>Sign up for ByeCorps ID</h2>
<form method="post">
    <input type="email" required name="email" id="email" placeholder="Email">
    <input type="password" required name="password" id="password" placeholder="Password">
    <button type="submit">Sign up</button>
</form>