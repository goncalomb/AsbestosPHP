<?php

use \Asbestos\Asbestos;

class ExamplePageController {

	public function foo() {
		Asbestos::startThemedPage('foo');
		echo 'foo page';
	}

	public function bar($params) {
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

	private function __construct() { }

}

?>
