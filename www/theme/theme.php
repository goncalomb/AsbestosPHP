<?php

use \Asbestos\Page;
use \Asbestos\HtmlElement;

Page::metaTag('description', 'A small framework for creating web applications in PHP.');
Page::metaTag('author', 'goncalomb');

Page::stylesheetFile('https://cdn.jsdelivr.net/normalize/7.0.0/normalize.css');
Page::stylesheetFile('https://fonts.googleapis.com/css?family=Bitter:400,700');

Page::head()->append('<style>
body { font-family: sans; text-align: center; }
h1 { font-family: \'Bitter\', serif; }
</style>');

$main = new HtmlElement('main');
Page::zone('main', $main);

?>

<header>
	<h1>AsbestosPHP</h1>
</header>
<?php Page::append('body', $main); ?>

<footer>
	<p><small>by <a href="https://goncalomb.com/">goncalomb</a></small></p>
</footer>
<?php Page::setOutputZone('main'); ?>
