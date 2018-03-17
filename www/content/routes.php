<?php

use \Asbestos\Asbestos;
use \Asbestos\Routing\Router;

Router::match('GET', '/foo', ['ExamplePageController', 'foo']);
Router::match('GET', '/bar/?(\d+)?', ['ExamplePageController', 'bar']);
Router::match('GET', '/debug', ['ExamplePageController', 'debug']);

Router::match('GET', '/json', function () {
    $res = Asbestos::response();
    $res->setContent([
        '__comment' => 'This is a test JSON object',
        'time' => time()
    ]);
});
