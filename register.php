<?php 


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $DB_SERVER = DB_ADDRESS;
    $DB_USER = DB_USERNAME;
    $DB_PASSWD = DB_PASSWORD;
    $DB_BASE = DB_DATABASE;

    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    try {
        $conn = new PDO("mysql:host=$DB_SERVER;dbname=$DB_BASE", $DB_USER, $DB_PASSWD);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO `accounts` (`email`, `password`, `verified`) VALUES ('$email', '$password', '0')";
        try{
            $stmt = $conn->prepare($sql);
            $stmt->execute($query);
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
    echo '<pre>';
    print_r($_POST);

    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include("head.php"); ?>
</head>
<body>
    <?php include("header.php"); ?>
    <main>
        <h2>Sign in</h2>
        <form action="#" method="post">
            <input type="email" name="email" id="email" placeholder="Email">
            <input type="password" name="password" id="password" placeholder="Password">
            <button type="submit">Submit</button>
        </form>
    </main>
    <?php include("footer.php"); ?>
</body>
</html>