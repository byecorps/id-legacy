<?php 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['init'] == 'Init') {
        echo("<p>Initialising DB...");
        echo "<p>Create table `accounts`";
        $stmt = $pdo->prepare('create table accounts
(
    id           varchar(7)                       not null
        primary key,
    email        text                             not null,
    created_date date default current_timestamp() not null,
    display_name text                             null,
    password     text                             not null,
    verified     tinyint(1)                       not null,
    constraint email
        unique (email) using hash
);');
        
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            echo('<p>An error occurred: '. $e->getMessage() .'. Will skip. (Most likely the table already exists.)');
        }

        echo '<p>Create the `password_resets` table</p>';
        $stmt = $pdo->prepare('create table password_resets
(
    id         int auto_increment
        primary key,
    auth_id    tinytext   not null,
    owner_id   varchar(7) not null,
    expiration int        not null,
    constraint password_resets_ibfk_1
        foreign key (owner_id) references accounts (id)
);

create index owner_id
    on password_resets (owner_id);
');

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
