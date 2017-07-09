<?php

namespace Asbestos;

class Config {

	private static $_data = [
		'timezone' => 'UTC',
		'routing' => [
			'robots-txt' => [
				'enable' => false,
				'disallow' => false
			]
		],
		'site' => [
			'title' => 'AsbestosPHP',
			'title-format' => '{} - AsbestosPHP',
			'metadata' => [
				'description' => null,
				'keywords' => null,
				'author' => null,
				'twitter' => [
					'card' => 'summary',
					// 'site' => '',
					// 'creator' => ''
					// ... https://dev.twitter.com/cards/markup
				],
				'og' => [
					// 'title' => '',       // set automatically
					'type' => 'website',
					// 'image' => '',       // NOT set automatically
					// 'url' => '',         // set automatically
					// 'description' => '', // set automatically
					// ... http://ogp.me/
				]
			]
		]
	];

	public static function load($file) {
		$data = require $file;
		if (is_array($data)) {
			static::set(null, $data);
		}
	}

	public static function set($path, $value) {
		// parse the path
		if (!is_array($path)) {
			$path = explode('.', $path);
			if (count($path) == 1 && !$path[0]) {
				$path = [];
			}
		}
		// walk to the location
		$data = &static::$_data;
		foreach ($path as $key) {
			if (!is_array($data)) {
				$data = [];
			}
			$data = &$data[$key];
		}
		// recursively merge the data
		$recursive_set_helper = function(&$value, &$data) use (&$recursive_set_helper) {
			if (is_array($value) && isset($data) && is_array($data)) {
				foreach ($value as $k => &$v) {
					$recursive_set_helper($v, $data[$k]);
				}
			} else {
				$data = $value;
			}
		};
		$recursive_set_helper($value, $data);
	}

	public static function get($path, $default=null) {
		// parse the path
		if (!is_array($path)) {
			$path = explode('.', $path);
			if (count($path) == 1 && !$path[0]) {
				$path = [];
			}
		}
		// walk to the location and return the value
		$data = self::$_data;
		foreach ($path as $key) {
			if (isset($data[$key])) {
				$data = $data[$key];
			} else {
				return $default;
			}
		}
		if ($default === null || is_array($data) == is_array($default)) {
			// is default is set then the the data
			// must be of the same type (array or not array)
			return $data;
		} else  {
			return $default;
		}
	}

}

?>
