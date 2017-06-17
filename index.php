<?php

require './asbestos/core.php';
use \Asbestos\Asbestos;
use \Asbestos\Page;

$page = Asbestos::startThemedPage();
$page->title('AsbestosPHP');

Page::append('header', '<h1>AsbestosPHP</h1>');
Page::append('footer', '<p><small>by <a href="https://goncalomb.com/">goncalomb</a></small></p>');

echo "<p>Welcome to AsbestosPHP!</p>";

?>
