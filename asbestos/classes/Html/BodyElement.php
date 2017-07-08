<?php

namespace Asbestos\Html;

class BodyElement extends Element {

	private $_scripts = array();

	public function __construct() {
		parent::__construct('body');
	}

	public function scriptFile($src) {
		$this->_scripts[] = $src;
	}

	public function output() {
		$this->outputOpeningTag();
		$this->outputContent();
		foreach ($this->_scripts as $src) {
			echo '<script type="text/javascript" src="', $src, '"></script>';
		}
		$this->outputClosingTag();
	}

}

?>
