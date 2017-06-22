<?php

namespace Asbestos;

final class Asbestos {

	private static $_htmlErrorNames = array(
		400 => 'Bad Request',
		403 => 'Forbidden',
		404 => 'Not Found',
		500 => 'Internal Server Error',
		503 => 'Service Temporarily Unavailable'
	);

	private static $_errorFn = null;

	public static function startThemedPage() {
		if (isset($_SERVER['REDIRECT_STATUS']) && $_SERVER['REDIRECT_STATUS'] != 200) {
			self::triggerHttpError($_SERVER['REDIRECT_STATUS']);
		}
		$page = Page::start();
		$theme_file = ASBESTOS_THEME_DIR . DIRECTORY_SEPARATOR . 'theme.php';
		if (is_file($theme_file)) {
			require $theme_file;
		}
		return $page;
	}

	public static function registerErrorFn($fn) {
		self::$_errorFn = $fn;
	}

	public static function triggerHttpError($error_code, $error_name=null) {
		if (!$error_name) {
			$error_name = (isset(self::$_htmlErrorNames[$error_code]) ? self::$_htmlErrorNames[$error_code] : 'Unknown Error');
		}
		$theme_file = ASBESTOS_THEME_DIR . DIRECTORY_SEPARATOR . 'theme.php';
		if (is_file($theme_file)) {
			header('Content-Type: text/html; charset=utf-8', true, $error_code);
			Page::start();
			require $theme_file;
			if (self::$_errorFn) {
				call_user_func(self::$_errorFn, $error_code, $error_name);
			} else {
				echo '<p style="color: crimson;">', $error_code, ' ', $error_name, '</p>';
			}
		} else {
			header('Content-Type: text/plain; charset=utf-8', true, $error_code);
			echo "{$error_code} {$error_name}\n";
		}
		exit();
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
