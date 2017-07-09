<?php

use \Asbestos\Page;

Page::stylesheetFile('https://cdn.jsdelivr.net/normalize/7.0.0/normalize.css');
Page::stylesheetFile('https://fonts.googleapis.com/css?family=Bitter:400,700');

Page::head()->append('<style>
body { font-family: sans; text-align: center; max-width: 600px; margin: 0 auto; }
h1 { font-family: \'Bitter\', serif; }
</style>');

?>

<header>
	<h1>AsbestosPHP</h1>
</header>
<?php Page::createZone('main', 'main'); ?>

<footer>
	<p><small>by <a href="https://goncalomb.com/">goncalomb</a></small></p>
</footer>
<?php Page::setOutputZone('main'); ?>
