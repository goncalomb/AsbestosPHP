<?php

use \Asbestos\Routing\Router;

Router::match('GET', '/foo', ['ExamplePageController', 'foo']);
Router::match('GET', '/bar/?(\d+)?', ['ExamplePageController', 'bar']);

?>
