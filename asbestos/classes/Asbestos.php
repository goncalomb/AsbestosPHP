<?php

namespace Asbestos;

final class Asbestos {

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
