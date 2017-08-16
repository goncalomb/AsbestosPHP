<?php

namespace Asbestos;

class ErrorHandling {

	private static $_lastError = null;

	public static function getErrorConstantName($c) {
		foreach (get_defined_constants(true)['Core'] as $name => $value) {
			if (strlen($name) > 2 && $name[0] == 'E' && $name[1] == '_' && $value == $c) {
				return $name;
			}
		}
		return 'E_UNKNOWN';
	}

	public static function lastError() {
		return static::$_lastError;
	}

	public static function register() {
		error_reporting(~E_ALL);
		set_error_handler(function($errno, $errstr, $errfile, $errline) {
			if (error_reporting() == 0) {
				return false;
			}
			static::handleError($errstr, $errno, $errfile, $errline);
		});
		set_exception_handler(function($ex) {
			static::handleError($ex->getMessage(), $ex->getCode(), $ex->getFile(), $ex->getLine(), $ex);
		});
		register_shutdown_function(function() {
			$error = error_get_last();
			if ($error) {
				static::handleError($error['message'], $error['type'], $error['file'], $error['line']);
			}
		});
	}

	private static function handleError($message, $code, $file, $line, $ex=null) {
		$data = ['message' => $message, 'code' => $code, 'file' => $file, 'line' => $line, 'name' => null, 'description' => null];
		if ($ex) {
			$data['exception'] = get_class($ex);
			$data['name'] = 'EXCEPTION';
			$data['description'] = "Uncaught exception '{$data['exception']}' with message '{$data['message']}' in {$data['file']} on line {$data['line']}";
		} else {
			$data['name'] = static::getErrorConstantName($code);
			$data['description'] = "{$data['name']}({$code}): {$data['message']} in {$data['file']} on line {$data['line']}";
		}

		static::$_lastError = [$data, $ex];

		@error_log($data['description']);

		if (!headers_sent()) {
			header_remove();
			while (ob_get_level()) {
				ob_end_clean();
			}
			ob_start();
			Response::contentType('plain', 500);
		}

		echo "Internal Server Error.\n";

		exit();
	}

	private function __construct() { }

}

?>
