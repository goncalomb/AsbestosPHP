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

	private static $_configs = array();

	public static function setConfig($name, $value) {
		self::$_configs[$name] = $value;
	}

	public static function getConfig($name, $default=null) {
		return (isset(self::$_configs[$name]) ? self::$_configs[$name] : $default);
	}

	private static function loadTheme($title=null) {
		$theme_file = ASBESTOS_THEME_DIR . DIRECTORY_SEPARATOR . 'theme.php';
		if (is_file($theme_file)) {
			$page = Page::start();
			require $theme_file;
			if ($title) {
				$title_prefix = self::getConfig('title_prefix', '');
				$title_suffix = self::getConfig('title_suffix', '');
				$title = "{$title_prefix}{$title}{$title_suffix}";
			} else {
				$title = self::getConfig('title_default');
			}
			if ($title) {
				$page->title($title);
			}
			return $page;
		}
		return null;
	}

	public static function startThemedPage($title=null) {
		if (isset($_SERVER['REDIRECT_STATUS']) && $_SERVER['REDIRECT_STATUS'] != 200) {
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
			if (isset(self::$_configs['error_callback']) && is_callable(self::$_configs['error_callback'])) {
				call_user_func(self::$_configs['error_callback'], $error_code, $error_name);
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
