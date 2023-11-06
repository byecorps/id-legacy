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
    
    try {
        $sql = "INSERT INTO `accounts` (`id`, `email`, `password`, `verified`) VALUES (?, ?, ?, ?)";
        try{
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$BCID, $email, $password, 0]);
            $result = $stmt->fetch();
            echo "Failed successfully: $result";
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
    <input type="email" name="email" id="email" placeholder="Email">
    <input type="password" name="password" id="password" placeholder="Password">
    <button type="submit">Sign up</button>
</form>