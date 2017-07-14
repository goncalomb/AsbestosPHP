<?php

namespace Asbestos;

final class Request {

	private static $_scheme = null;
	private static $_host = null;
	private static $_path = null;
	private static $_query = null;
	private static $_url = null;

	public static function _initialize() {
		if (self::$_scheme === null) {
			self::$_scheme = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https' : 'http');
			self::$_host = $_SERVER['HTTP_HOST'];
			self::$_path = strstr($_SERVER['REQUEST_URI'], '?', true);
			if (self::$_path == false) {
				self::$_path = $_SERVER['REQUEST_URI'];
			}
			self::$_query = strstr($_SERVER['REQUEST_URI'], '?');
			if (self::$_query == false) {
				self::$_query = '';
			}
			self::$_url = self::$_scheme . '://' . self::$_host . $_SERVER['REQUEST_URI'];
		}
	}

	public static function scheme() {
		return self::$_scheme;
	}

	public static function host() {
		return self::$_host;
	}

	public static function path() {
		return self::$_path;
	}

	public static function query() {
		return self::$_query;
	}

	public static function url($as_array=false) {
		return ($as_array ? [
			'url' => self::$_url,
			'scheme' => self::$_scheme,
			'host' => self::$_host,
			'path' => self::$_path,
			'query' => self::$_query
		] : self::$_url);
	}

	private function __construct() { }

}

Request::_initialize();

?>
