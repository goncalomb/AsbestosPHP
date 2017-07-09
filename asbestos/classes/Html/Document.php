<?php

namespace Asbestos\Html;

class Document {

	private $_htmlElement;
	private $_headElement;
	private $_bodyElement;

	public function __construct() {
		$this->_htmlElement = new Element('html');
		$this->_headElement = new HeadElement();
		$this->_bodyElement = new BodyElement();
		$this->_htmlElement->append($this->_headElement);
		$this->_htmlElement->append($this->_bodyElement);
	}

	public function head() {
		return $this->_headElement;
	}

	public function body() {
		return $this->_bodyElement;
	}

	public function metaTag($name, $content) {
		$this->_headElement->metaTag($name, $content);
	}

	public function ogTags($data, $merge=true, $prefix='og') {
		$this->_headElement->ogTags($data, $merge, $prefix);
	}

	public function stylesheetFile($href) {
		$this->_headElement->stylesheetFile($href);
	}

	public function scriptFile($src, $end=false) {
		if ($end) {
			$this->_bodyElement->scriptFile($src);
		} else {
			$this->_headElement->scriptFile($src);
		}
	}

	public function title($title) {
		$this->_headElement->title($title);
	}

	public function output() {
		echo '<!DOCTYPE html>', "\n";
		$this->_htmlElement->output();
		echo "\n";
	}

}

?>
