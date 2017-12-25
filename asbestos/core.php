<?php

define('ASBESTOS_MICROTIME', (isset($_SERVER['REQUEST_TIME_FLOAT']) ? (float) $_SERVER['REQUEST_TIME_FLOAT'] : microtime(true)));
define('ASBESTOS_TIME', floor(ASBESTOS_MICROTIME));

define('ASBESTOS_VERSION', '0.0.0');
define('ASBESTOS_DIR', __DIR__);

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('html_errors', 0);

define('ASBESTOS_COMPOSER', class_exists('Composer\Autoload\ClassLoader'));

if (ASBESTOS_COMPOSER) {
	define('ASBESTOS_ROOT_DIR', dirname(dirname(dirname(dirname(ASBESTOS_DIR)))) . DIRECTORY_SEPARATOR . 'www');
} else {
	$path = get_included_files()[0];
	do {
		$path_last = $path;
		$path = dirname($path);
		if ($path == $path_last) {
			trigger_error('AsbestosPHP: unable to find root directory', E_USER_ERROR);
			exit();
		}
	} while (realpath($path . DIRECTORY_SEPARATOR . 'asbestos') != ASBESTOS_DIR);
	define('ASBESTOS_ROOT_DIR', $path);
	unset($path, $path_last);
}

define('ASBESTOS_CLASSES_DIR', ASBESTOS_DIR . DIRECTORY_SEPARATOR . 'classes');
define('ASBESTOS_CONTENT_DIR', ASBESTOS_ROOT_DIR . DIRECTORY_SEPARATOR . 'content');
define('ASBESTOS_CLASSES_ALT_DIR', ASBESTOS_CONTENT_DIR . DIRECTORY_SEPARATOR . 'classes');
define('ASBESTOS_THEME_DIR', ASBESTOS_ROOT_DIR . DIRECTORY_SEPARATOR . 'theme');

define('ASBESTOS_CONFIG_FILE', ASBESTOS_CONTENT_DIR . DIRECTORY_SEPARATOR . 'config.php');
define('ASBESTOS_INIT_FILE', ASBESTOS_CONTENT_DIR . DIRECTORY_SEPARATOR . 'init.php');

require ASBESTOS_DIR . DIRECTORY_SEPARATOR . 'functions.php';

spl_autoload_register(function($name) {
	if (strncmp($name, 'Asbestos\\', 9) == 0) {
		Asbestos\load_class(substr($name, 9));
	} else {
		Asbestos\load_class($name, ASBESTOS_CLASSES_ALT_DIR);
	}
});

Asbestos\ErrorHandling::register();

if (is_file(ASBESTOS_CONFIG_FILE)) {
	Asbestos\Config::load(ASBESTOS_CONFIG_FILE);
}

if ($timezone = Asbestos\Config::get('timezone', 'UTC')) {
	date_default_timezone_set($timezone);
}
unset($timezone);

if (is_file(ASBESTOS_INIT_FILE)) {
	require ASBESTOS_INIT_FILE;
}

?>
