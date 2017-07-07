<?php

require './asbestos/core.php';
use \Asbestos\Asbestos;

Asbestos::startRouting(true);

Asbestos::startThemedPage();

?>

<p>Welcome to AsbestosPHP!</p>
<p>A small framework for creating web applications in PHP.</p>
<p><a href="/foo">/foo</a> <a href="/bar">/bar</a> <a href="/debug">/debug</a></p>
<p><a href="https://github.com/goncalomb/AsbestosPHP">AsbestosPHP on GitHub</a></p>
