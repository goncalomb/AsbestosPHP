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

	public static function ogTags($data, $merge=true, $prefix='og') {
		if (self::$_page) {
			self::$_page->ogTags($data, $merge, $prefix);
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

	public static function setMetadata($title=null, $data=[], $merge=true) {
		if (self::$_page) {
			// format the title
			$simple_title = $title;
			if ($title) {
				$title = str_replace('{}', $title, Config::get('site.title-format', '{}'));
			} else {
				$simple_title = $title = Config::get('site.title', '');
			}
			self::$_page->title($title);
			// merge data with the global configuration
			if ($merge) {
				if ($config_data = Config::get('site.metadata', [])) {
					$data = array_merge($config_data, $data);
				}
			}
			// set basic meta tags
			foreach (['description', 'keywords', 'author'] as $name) {
				if (!empty($data[$name])) {
					self::$_page->metaTag($name, $data[$name]);
				}
			}
			// set tags for Twitter Cards
			if (isset($data['twitter']) && is_array($data['twitter'])) {
				self::$_page->ogTags($data['twitter'], false, 'twitter');
			} else {
				self::$_page->ogTags([], false, 'twitter');
			}
			// set Open Graph tags
			if (isset($data['og']) && is_array($data['og'])) {
				$og_tags = [
					'title' => $simple_title,
					'url' => ASBESTOS_REQUEST_URL
				];
				if (!empty($data['description'])) {
					$og_tags['description'] = $data['description'];
				}
				self::$_page->ogTags(array_merge($og_tags, $data['og']), false);
			} else {
				self::$_page->ogTags([], false);
			}
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
