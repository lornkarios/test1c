<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Page\Asset;

?>
</main>

<footer class="text-muted">
    <div class="container">
        <p class="float-right">
            <a href="#">Back to top</a>
        </p>
        <p>Album example is © Bootstrap, but please download and customize it for yourself!</p>
        <p>New to Bootstrap? <a href="https://getbootstrap.com/">Visit the homepage</a> or read our <a href="/docs/4.4/getting-started/introduction/">getting started guide</a>.</p>
    </div>
</footer>

<?php
$asset = Asset::getInstance();
$asset->addJs('/local/js/jquery.min.js');
$asset->addJs('/local/js/popper.min.js');
$asset->addJs('/local/js/bootstrap.min.js');
?>
</body>
</html>
