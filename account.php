<?php 

if (!$_SESSION['auth']) {
    header('Location: /signin?callback=/account');
    exit;
}

function get_gravatar_url( $email ) {
    // Trim leading and trailing whitespace from
    // an email address and force all characters
    // to lower case
    $address = strtolower( trim( $email ) );
  
    // Create an SHA256 hash of the final string
    $hash = hash( 'sha256', $address );
  
    // Grab the actual image URL
    return 'https://www.gravatar.com/avatar/' . $hash;
}

$stmt = $pdo->prepare('SELECT * FROM accounts WHERE id = ? LIMIT 1');
$stmt->execute([$_SESSION['id']]);
$user = $stmt->fetch();


if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if (isset($_POST["old_password"]) && $_POST["old_password"] != "") {
        // means password reset is wanted.

        if (!password_verify($_POST["old_password"], $user["password"])) {
            $password_error = "Incorrect password. (Error 901)";
        }

        if (password_verify($_POST['new_password'], $user["password"])) {
            $password_error = "New password may not be same as old password. (Error 902)";
        }

        if ($_POST['new_password'] != $_POST['repeat_new_password']) {
            $password_error = "The passwords must match. (Error 900)";
        }


        if (isset($password_error)) {
            $message = $password_error;
            goto skip_submit;
        }

        $new_password = password_hash($_POST["new_password"], PASSWORD_DEFAULT);
        
        $sql = "UPDATE accounts SET password = ? WHERE id = ?";
        $pdo->prepare($sql)->execute([$new_password, $user["id"]]);
    }

    if (isset($_POST["display_name"])) {
        $sql = "UPDATE accounts SET display_name = ? WHERE id = ?";
        $pdo->prepare($sql)->execute([$_POST["display_name"], $user["id"]]);
    }

    $message = "Updated sucessfully. Changes might take a few minutes to take effect.";

    header('Location: /profile');
    die("Redirecting...");

}

skip_submit:

?>

<h1>Your account</h1>

<?php 
if (isset($message )) {
            echo "<div class='flash'>".$message."</div>";
        } 
?>

<div id="wrapper">
    <div id="profile">
        <img src="<?= get_avatar_url($user['id']); ?>">
        <div class="details">
            <span class="displayname"><?= $user['display_name'] ?></span>
            <span class="bcid"><?= format_bcid($user['id']); ?></span>
            <time datetime="<?= $user["created_date"] ?>">Since <?= $user["created_date"]; ?></time>
        </div>
    </div>

    <aside>

        <form method="post">
            <fieldset>
                <legend>Profile</legend>
                <div class="container">
                    <label>BCID</label>
                    <input type="text" disabled value="<?= format_bcid($user['id']) ?>">
                </div>

                <div class="container">
                    <input type="checkbox" disabled checked="<?= $user['verified'] ?>" >
                    <label> Verified email</label>
                </div>

                <div class="container">
                    <label for="email">Email address</label>
                    <input type="email" name="email" id="email" value="<?= $user['email'] ?>">
                </div>

                <div class="container">
                    <label for="display_name">Display name</label>
                    <input type="text" name="display_name" id="display_name" value="<?= $user['display_name'] ?>">
                </div>
            </fieldset>
            <fieldset>
                <legend>Password</legend>
                <p>You only need to insert values here if you're resetting your password.</p>
                <div class="container">
                    <label for="old_password">Current password</label>
                    <input type="password" name="old_password" id="old_password">
                </div>
                <div class="container">
                    <label for="new_password">New password</label>
                    <input type="password" name="new_password" id="new_password">
                </div>
                <div class="container">
                    <label for="repeat_new_password">Repeat new password</label>
                    <input type="password" name="repeat_new_password" id="repeat_new_password">
                </div>
            </fieldset>

            <button class="primary" type="submit"><i class="fa-fw fa-solid fa-floppy-disk"></i> Save</button>
        </form>

        <div class="dangerzone">
            <h2>Danger Zone</h2>
            <p><a href="/signout" class="button"><i class="fa-fw fa-solid fa-person-through-window"></i> Sign out</a>
                <a href="/dangerous/delete_account" class="button danger"><i class="fa-fw fa-solid fa-trash"></i> Delete account</a></p>
        </div>
    </aside>
</div>



