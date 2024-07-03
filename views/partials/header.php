
<header>
    <div>
        <a href="/" id="sitetitle">
            <span class="bc-1">Bye</span><span class="bc-2">Corps</span><span class="bc-3"> ID</span>
        </a>
    </div>

    <div class="section">
        <?php
            if ($_SESSION['auth']) {
                echo '<div class="item">' . get_string("header.hello", ['display_name' => get_user_display_name($_SESSION['id'])]) . '</div>';
                echo '<div class="item"><a href="/auth/signout">'. get_string('auth.signout') .'</a></div>';
            }
            else {
                echo '<a href="/auth/signup">' . get_string("auth.signup")
                    . '</a> <a href="/auth/login">'. get_string("auth.login") . '</a>';
            }
        ?>
    </div>
</header>
