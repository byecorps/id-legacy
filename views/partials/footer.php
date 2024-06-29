<footer>
    <div>&copy; ByeCorps 2024 :: <a href="/credits"><?= get_string('page.credits') ?></a></div>
    <div><b><?= get_string('footer.executionTime') ?>: </b> <?= round((microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) * 1000, 3) ?> ms</div>
</footer>