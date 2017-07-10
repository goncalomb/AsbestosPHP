<?php

namespace Asbestos;

final class Http {

	const USER_AGENT = 'AsbestosPHP';

	private static $_context = null;

	private static function getContext() {
		if (!self::$_context) {
			self::$_context = stream_context_create([
				'http' => [
					'user_agent' => self::USER_AGENT
				]
			]);
		}
		return self::$_context;
	}

	// TODO: add more methods, get() post() etc.

	public static function requestSimple($url) {
		if (function_exists('curl_init')) {
			$ch = curl_init();
			curl_setopt_array($ch, array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_USERAGENT => self::USER_AGENT
			));
			$data = curl_exec($ch);
			if (curl_error($ch)) {
				trigger_error('Request cURL error:' . curl_error($ch), E_USER_ERROR);
			}
			$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if ($status != 200) {
				trigger_error("Request ({$url}) failed with status code {$status} (!= 200)", E_USER_ERROR);
			}
			curl_close($ch);
			return $data;
		} else {
			return file_get_contents($url, false, self::getContext());
		}
	}

	private function __construct() { }

}
