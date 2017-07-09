<?php

namespace Asbestos\Html;

class HeadElement extends Element {

	private $_metatags = array();
	private $_ogtags = array();
	private $_styles = array();
	private $_scripts = array();
	private $_title = 'A Page';

	public function __construct() {
		parent::__construct('head');
	}

	public function metaTag($name, $content) {
		$this->_metatags[$name] = $content;
	}

	public function ogTags($data, $merge=true, $prefix='og') {
		if ($merge && isset($this->_ogtags[$prefix])) {
			$this->_ogtags[$prefix] = array_merge($this->_ogtags[$prefix], $data);
		} else {
			$this->_ogtags[$prefix] = $data;
		}
	}

	public function stylesheetFile($href) {
		$this->_styles[] = $href;
	}

	public function scriptFile($src) {
		$this->_scripts[] = $src;
	}

	public function title($title) {
		$this->_title = $title;
	}

	public function output() {
		$this->outputOpeningTag();
		echo '<meta charset="utf-8">';
		foreach ($this->_metatags as $name => $content) {
			echo '<meta name="', $name, '" content="', $content, '">';
		}
		foreach ($this->_ogtags as $prefix => $data) {
			foreach ($data as $property => $content) {
				echo '<meta property="', $prefix, ':', $property, '" content="', $content, '">';
			}
		}
		foreach ($this->_styles as $href) {
			echo '<link rel="stylesheet" type="text/css" href="', $href, '">';
		}
		foreach ($this->_scripts as $src) {
			echo '<script type="text/javascript" src="', $src, '"></script>';
		}
		if ($this->_title) {
			echo '<title>', $this->_title, '</title>';
		}
		$this->outputContent();
		$this->outputClosingTag();
	}

}

?>
