<?php 

if ($_SESSION['id'] != "281G3NV") {
    http_response_code(401);
    die("<img src='https://http.cat/401.jpg'>");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['init'] == 'Init') {
        echo("<p>Initialising DB...");
        $pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD, PDO_OPTIONS);
        echo "<p>Create table `accounts`";
        $stmt = $pdo->prepare('CREATE TABLE `accounts` (
            `id` tinytext NOT NULL,
            `email` text NOT NULL,,
            `display_name` text NULL,
            `password` text NOT NULL,
            `verified` tinyint(1) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;');
        
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            echo('<p>An error occurred: '. $e->getMessage() .'. Will skip. (Most likely the table already exists.)');
        }

        echo '<p>Set indexes for table `accounts`';
        $stmt = $pdo->prepare('ALTER TABLE `accounts`
        ADD PRIMARY KEY (`id`(7)),
        ADD UNIQUE KEY `email` (`email`) USING HASH;');

        try {
            $stmt->execute();
        } catch (PDOException $e) {
            echo('<p>An error occurred: '. $e->getMessage() .'. Most likely this is already set.');
        }

        echo "<p>Database initialised.</p>";
    }
}

?>

<h2 class="subheading">Admin</h2>
<h1>Init database</h1>

<p>Assuming you have the database config configured, you can click this button to create the tables required for this thing to function.</p>

<form method="post">
    <button name="init" value="Init" class="primary">Init DB</button>
</form>
