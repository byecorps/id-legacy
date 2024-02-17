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
    verified     tinyint(1) default 0             not null,
    has_pfp      tinyint(1) default 0             not null,
    is_admin     tinyint(1) default 0             not null,
    constraint email
        unique (email) using hash
);');
        
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            echo('<p>An error occurred: '. $e->getMessage() .'. Will skip. (Most likely the table already exists.)');
        }

        echo '<p>Create the `password_resets` table';
        $stmt = $pdo->prepare('create table password_resets
(
    id         int auto_increment
        primary key,
    auth_id    tinytext   not null,
    owner_id   varchar(7) not null,
    expiration int        not null,
    constraint password_resets_ibfk_1
        foreign key (owner_id) references accounts (id)
);');

        try {
            $stmt->execute();
        } catch (PDOException $e) {
            echo('<p>An error occurred: '. $e->getMessage() .'. Most likely this is already set.');
        }

        echo '<p>Create the `apps` table';

        try {
            db_execute('create table apps (
            id int auto_increment
                    primary key,
            owner_id    varchar(7)  not null,
            title       text        not null,
            description text,
            image       text    default "https://id.byecorps.com/assets/default.png"   not null,
            type        text    null,
            callback    text    null,
            constraint apps_ibfk_1 
                foreign key (owner_id) references accounts (id)
            );');
        } catch (PDOException $e) {
            echo('<p>An error occurred: '. $e->getMessage() .'. Most likely this is already set.');
        }


        echo '<p>Create the `badges` table';

        try {
            db_execute('create table badges (
            id int auto_increment
                    primary key,
            app_id      int     not null,
            title       text    not null,
            description text,
            image       text    default "https://id.byecorps.com/assets/default.png"   not null,
            constraint badges_ibfk_1 
                foreign key (app_id) references apps (id)
            );');
        } catch (PDOException $e) {
            echo('<p>An error occurred: '. $e->getMessage() .'. Most likely this is already set.');
        }

        echo '<p>Create the `profiles` table';

        try {
            db_execute('create table profiles (
            id varchar(7)
                      primary key,
            description         text        null,
            public_avatar       tinyint(1)  default 0,
            public_display_name tinyint(1)  default 0,
            constraint profiles_ibfk_1 
                foreign key (id) references accounts (id)
            );');
        } catch (PDOException $e) {
            echo('<p>An error occurred: '. $e->getMessage() .'. Most likely this is already set.');
        }


        echo "<p>Database initialised.</p>";
    }
}

?>

<h1>Init database</h1>

<p>Assuming you have the database config configured, you can click this button to create the tables required for this thing to function.</p>

<form method="post">
    <button name="init" value="Init" class="primary">Init DB</button>
</form>
