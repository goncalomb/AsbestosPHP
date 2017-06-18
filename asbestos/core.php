<?php

define('ASBESTOS_MICROTIME', (isset($_SERVER['REQUEST_TIME_FLOAT']) ? (float) $_SERVER['REQUEST_TIME_FLOAT'] : microtime(true)));
define('ASBESTOS_TIME', floor(ASBESTOS_MICROTIME));

define('ASBESTOS_VERSION', '0.0.0');
define('ASBESTOS_DIR', __DIR__);

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

define('ASBESTOS_CLASSES_DIR', ASBESTOS_DIR . DIRECTORY_SEPARATOR . 'classes');
define('ASBESTOS_THEME_DIR', ASBESTOS_ROOT_DIR . DIRECTORY_SEPARATOR . 'theme');

spl_autoload_register(function($name) {
	if (strncmp($name, 'Asbestos\\', 9) == 0) {
		$name = substr($name, 9);
		$file = ASBESTOS_CLASSES_DIR . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $name) . '.php';
		if (is_file($file)) {
			require $file;
		}
	}
});

?>
