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

	private static $_routing = false;

	private static function loadTheme($title=null) {
		$theme_file = ASBESTOS_THEME_DIR . DIRECTORY_SEPARATOR . 'theme.php';
		if (!is_file($theme_file)) {
			$theme_file = ASBESTOS_CONTENT_DIR . DIRECTORY_SEPARATOR . 'theme.php';
		}
		if (is_file($theme_file)) {
			$page = Page::start();
			Page::setMetadata($title);
			safe_require($theme_file);
			return $page;
		}
		return null;
	}

	public static function startRouting($index_fallback=false) {
		self::$_routing = true;
		if (isset($_SERVER['REDIRECT_STATUS']) && $_SERVER['REDIRECT_STATUS'] != 200 && $_SERVER['REDIRECT_STATUS'] != 404) {
			self::triggerHttpError($_SERVER['REDIRECT_STATUS']);
		}
		if (Config::get('routing.robots-txt.enable', false)) {
			Routing\Router::match('GET', '/robots\.txt', function() {
				header('Content-Type: text/plain; charset=utf-8', true, 200);
				echo "User-agent: *\n";
				if (Config::get('routing.robots-txt.disallow', false)) {
					echo "Disallow: /\n";
				} else {
					echo "Disallow:\n";
				}
			});
		}
		safe_require(ASBESTOS_CONTENT_DIR . DIRECTORY_SEPARATOR . 'routes.php');
		if (Routing\Router::run(Request::method(), Request::path())) {
			exit();
		} else if (!$index_fallback || Request::path() != '/') {
			self::triggerHttpError(404);
		}
	}

	public static function startThemedPage($title=null) {
		if (!self::$_routing && isset($_SERVER['REDIRECT_STATUS']) && $_SERVER['REDIRECT_STATUS'] != 200) {
			self::triggerHttpError($_SERVER['REDIRECT_STATUS']);
		}
		header('Content-Type: text/html; charset=utf-8', true, 200);
		$page = self::loadTheme($title);
		if (!$page) {
			$page = Page::start();
		}
		return $page;
	}

	public static function triggerHttpError($error_code, $error_name=null) {
		// TODO: move error handling code to new class
		if (!$error_name) {
			$error_name = (isset(self::$_htmlErrorNames[$error_code]) ? self::$_htmlErrorNames[$error_code] : 'Unknown Error');
		}
		header('Content-Type: text/html; charset=utf-8', true, $error_code);
		if (self::loadTheme("{$error_code} {$error_name}")) {
			$error_callback = Config::get('site.onerror');
			if (is_callable($error_callback)) {
				call_user_func($error_callback, $error_code, $error_name);
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
