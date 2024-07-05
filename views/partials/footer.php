<footer>
    <div class="item">
        <div>&copy; ByeCorps 2024 :: <a href="/credits"><?= get_string('page.credits') ?></a></div>
        <div><?= get_string('footer.executionTime', ['time'=>round((microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) * 1000, 3)]) ?></div>
    </div>
    <div class="item">
        <script src="/scripts/langauge_switcher.js" defer></script>
    </div>
</footer>