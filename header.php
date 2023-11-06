<!-- This is a testing file for the header used on BCID. Copy of header on ByeCorps.com -->

<?php 

if (!isset($_SESSION['auth'])) goto skip_auth;


if ($_SESSION['auth']) {
    $sql = "SELECT display_name FROM accounts WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_SESSION['id']]);
    $name = $stmt->fetchColumn();
}

if ($name == '') {
    $name = '<code class=bcid>'.format_bcid($_SESSION['id']).'</code>';
}

skip_auth:

?>

<link rel="stylesheet" href="./styles/global.css">
<link rel="stylesheet" href="./fontawesome/css/all.css">

<header>
    <div class="start">
        <a href="/" id="sitetitle"><span class="bc-1">Bye</span><span class="bc-2">Corps</span><span class="bc-3"> ID</span></a></div>

    <div class="end">

        <?php if (!isset($_SESSION['auth'])) goto signed_out; ?>
        <div class="loggedin">
            <a href="/account" class="account">Hey there, <?= $name ?>! <i class="fa-solid fa-fw fa-angle-right"></i></a>
        </div>
        <?php signed_out: ?>

    </div>
</header>