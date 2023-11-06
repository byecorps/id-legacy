<div class="hero">
    <div class="hero-text">
        <h1><span class="bc-1">Bye</span><span class="bc-2">Corps</span><span class="bc-3"> ID</span></h1>
        <p>Log into ByeCorps and beyond with a single ID.</p>
        <!-- <p><input type="email" name="loginEmail" id="loginEmail" placeholder="Email" /></p> -->

        <?php 
        if ( $_SESSION['auth']) { echo "<a href='/account' class='button primary'>Manage account</a>"; } 
        else { echo "<a href='/signin' class='button primary'>Sign in</a><a href='/signup' class='button'>Create an account</a>"; } 
        ?>

    </div>
</div>