<?php

use \Asbestos\Asbestos;
use \Asbestos\View\View;

class ExamplePageController
{
    public static function foo()
    {
        return (new View('foo'))->bind([
            'readme' => 'some important information',
            'note' => 'this is data sent to the view'
        ]);
    }

    public static function bar($params)
    {
        Asbestos::startThemedPage('bar');
        echo '<pre style="text-align: left;">';
        for ($i = 0; $i < 5; $i++) {
            $r = mt_rand();
            echo "<a href=\"/bar/$r\">/bar/$r</a>\n";
        }
        echo "\n";
        var_dump($params);
        echo '</pre>';
    }

    public static function debug()
    {
        return new View('debug');
    }

    private function __construct()
    {
    }
}
