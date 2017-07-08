<?php

namespace Asbestos;

final class Page {

	private static $_page;
	private static $_zones = array();
	private static $_outputZone = 'body';

	public static function start() {
		if (self::$_page) {
			return null;
		}
		self::$_page = new Html\Document();
		self::$_zones['head'] = self::$_page->head();
		self::$_zones['body'] = self::$_page->body();
		ob_start();
		register_shutdown_function(array(__CLASS__, 'end'));
		return self::$_page;
	}

	public static function createZone($tag, $name) {
		if (self::$_page && !isset(self::$_zones[$name])) {
			$element = new Html\Element($tag);
			self::$_zones[$name] = $element;
			self::append(self::$_outputZone, $element);
			return $element;
		}
		return null;
	}

	public static function zone($name, $element=null) {
		if ($element && self::$_page && !isset(self::$_zones[$name])) {
			self::$_zones[$name] = $element;
		}
		return (isset(self::$_zones[$name]) ? self::$_zones[$name] : null);
	}

	public static function setOutputZone($name) {
		if (isset(self::$_zones[$name])) {
			self::flushBuffer();
			self::$_outputZone = $name;
		}
	}

	public static function append($zone_name) {
		if (self::$_page && isset(self::$_zones[$zone_name])) {
			self::flushBuffer();
			call_user_func_array(array(self::$_zones[$zone_name], 'append'), array_slice(func_get_args(), 1));
		}
	}

	public static function flushBuffer() {
		if (self::$_page && ob_get_length()) {
			self::$_zones[self::$_outputZone]->append(ob_get_clean());
			ob_start();
		}
	}

	public static function stylesheetFile($href) {
		if (self::$_page) {
			self::$_page->stylesheetFile($href);
		}
	}

	public static function metaTag($name, $content) {
		if (self::$_page) {
			self::$_page->metaTag($name, $content);
		}
	}

	public static function scriptFile($src, $end=false) {
		if (self::$_page) {
			self::$_page->scriptFile($src, $end);
		}
	}

	public static function title($title) {
		if (self::$_page) {
			self::$_page->title($title);
		}
	}

	public static function head() {
		return (self::$_page ? self::$_page->head() : null);
	}

	public static function body() {
		return (self::$_page ? self::$_page->body() : null);
	}

	public static function get() {
		return self::$_page;
	}

	public static function end() {
		if (!self::$_page) {
			return;
		}
		self::$_zones[self::$_outputZone]->append(ob_get_clean());
		self::$_page->output();
		echo '<!-- ';
		echo '~', Asbestos::executionTime();
		echo " -->\n";
	}

}

?>
