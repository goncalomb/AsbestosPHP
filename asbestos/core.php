<?php

define('ASBESTOS_MICROTIME', (isset($_SERVER['REQUEST_TIME_FLOAT']) ? (float) $_SERVER['REQUEST_TIME_FLOAT'] : microtime(true)));
define('ASBESTOS_TIME', floor(ASBESTOS_MICROTIME));

define('ASBESTOS_VERSION', '0.0.0');

define('ASBESTOS_DIR', __DIR__);
define('ASBESTOS_ROOT_DIR', dirname(__DIR__));
define('ASBESTOS_CLASSES_DIR', realpath(__DIR__ . DIRECTORY_SEPARATOR . 'classes'));

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
