<?php

use \Asbestos\Asbestos;
use \Asbestos\Config;
use \Asbestos\Request;

class ExamplePageController {

	public static function foo() {
		Asbestos::startThemedPage('foo');
		echo 'foo page';
	}

	public static function bar($params) {
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

	public static function debug() {
		Asbestos::startThemedPage('debug');
		echo '<pre style="text-align: left;">';
		echo date('c'), "\n\n";
		echo '<strong>Config::get(null)</strong> = ';
		var_dump(Config::get(null));
		echo "\n";
		echo '<strong>Request::url(true)</strong> = ';
		var_dump(Request::url(true));
		echo "\n";
		echo '<strong>array_keys($GLOBALS)</strong> = ';
		var_dump(array_keys($GLOBALS));
		echo '</pre>';
	}

	private function __construct() { }

}

?>
