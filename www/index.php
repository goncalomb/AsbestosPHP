<?php

require './asbestos/core.php';
use \Asbestos\Asbestos;
use \Asbestos\Page;

Asbestos::startRouting(true);

$page = Asbestos::startThemedPage();
$page->title('AsbestosPHP');

?>

<p>Welcome to AsbestosPHP!</p>
<p>A small framework for creating web applications in PHP.</p>
<p><a href="/foo">/foo</a> <a href="/bar">/bar</a></p>
<p><a href="https://github.com/goncalomb/AsbestosPHP">AsbestosPHP on GitHub</a></p>
