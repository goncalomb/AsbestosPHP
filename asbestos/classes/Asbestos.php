<?php

namespace Asbestos;

final class Asbestos {

	public static function startThemedPage() {
		$page = Page::start();
		$theme_file = ASBESTOS_THEME_DIR . DIRECTORY_SEPARATOR . 'theme.php';
		if (is_file($theme_file)) {
			require $theme_file;
		}
		return $page;
	}

	public static function executionTime($asFloat=false) {
		if ($asFloat) {
			return microtime(true) - ASBESTOS_MICROTIME;
		} else {
			return floor((microtime(true) - ASBESTOS_MICROTIME)*100000)/100 . 'ms';
		}
	}

	private function __construct() { }

}

?>
