<?php

namespace Asbestos\Html;

class Element {

	private $_tag;
	private $_html = array();
	private $_attributes = array();
	private $_used = false;

	public function __construct($tag) {
		$this->_tag = $tag;
	}

	public function attribute($name, $value=null) {
		if ($value === null) {
			unset($this->_attributes[$name]);
		} else {
			$this->_attributes[$name] = $value;
		}
	}

	public function append() {
		$args = func_get_args();
		foreach ($args as $arg) {
			if ($arg instanceof Element) {
				if ($arg->_used) {
					continue;
				}
				$arg->_used = true;
			}
			$this->_html[] = $arg;
		}
	}

	public function clear() {
		$this->_html = array();
	}

	protected function outputOpeningTag() {
		echo '<', htmlspecialchars($this->_tag);
		foreach ($this->_attributes as $name => $value) {
			echo ' ', htmlspecialchars($name), '="', htmlspecialchars($value), '"';
		}
		echo '>';
		if ($this->_tag == 'html' || $this->_tag == 'head' || $this->_tag == 'body') {
			echo "\n";
		}
	}

	protected function outputContent() {
		foreach ($this->_html as $part) {
			if ($part instanceof Element) {
				$part->output();
			} else {
				echo $part;
			}
		}
	}

	protected function outputClosingTag() {
		echo '</', htmlspecialchars($this->_tag), '>';
		if ($this->_tag == 'html' || $this->_tag == 'head' || $this->_tag == 'body') {
			echo "\n";
		}
	}

	public function output() {
		$this->outputOpeningTag();
		$this->outputContent();
		$this->outputClosingTag();
	}

}

?>
