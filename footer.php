<footer>
    &COPY; ByeCorps <?php echo(date("Y")); ?> <a href="/credits">Credits</a>
    <br>
    <b>Execution time: </b> <?= round((microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) * 1000, 3) ?> ms
</footer>