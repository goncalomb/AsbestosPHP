<?php

use \Asbestos\Page;
use \Asbestos\HtmlElement;

Page::metaTag('description', 'A small framework for creating web applications in PHP.');
Page::metaTag('author', 'goncalomb');

$header = new HtmlElement('header');
Page::zone('header', $header);
Page::append('body', $header);

$main = new HtmlElement('main');
Page::zone('main', $main);
Page::append('body', $main);

$footer = new HtmlElement('footer');
Page::zone('footer', $footer);
Page::append('body', $footer);

Page::setOutputZone('main');

?>
