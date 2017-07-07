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
			require $theme_file;
			if ($title) {
				$title_prefix = Config::get('site.title.prefix', '');
				$title_suffix = Config::get('site.title.suffix', '');
				$title = "{$title_prefix}{$title}{$title_suffix}";
			} else {
				$title = Config::get('site.title.default', '');
			}
			if ($title) {
				$page->title($title);
			}
			return $page;
		}
		return null;
	}

	public static function startRouting($index_fallback=false) {
		self::$_routing = true;
		if (isset($_SERVER['REDIRECT_STATUS']) && $_SERVER['REDIRECT_STATUS'] != 200 && $_SERVER['REDIRECT_STATUS'] != 404) {
			self::triggerHttpError($_SERVER['REDIRECT_STATUS']);
		}
		safe_require(ASBESTOS_CONTENT_DIR . DIRECTORY_SEPARATOR . 'routes.php');
		if (Routing\Router::run(ASBESTOS_REQUEST_METHOD, ASBESTOS_REQUEST_PATH)) {
			exit();
		} else if (!$index_fallback || ASBESTOS_REQUEST_PATH != '/') {
			self::triggerHttpError(404);
		}
	}

	public static function startThemedPage($title=null) {
		if (!self::$_routing && isset($_SERVER['REDIRECT_STATUS']) && $_SERVER['REDIRECT_STATUS'] != 200) {
			self::triggerHttpError($_SERVER['REDIRECT_STATUS']);
		}
		$page = self::loadTheme($title);
		if (!$page) {
			$page = Page::start();
		}
		return $page;
	}

	public static function triggerHttpError($error_code, $error_name=null) {
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
