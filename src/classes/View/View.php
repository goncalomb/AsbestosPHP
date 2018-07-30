<?php

namespace Asbestos\View;

use \Asbestos\Asbestos;
use \Asbestos\Page;

function __asbestos_view_render($view, $data) {
    $req = Asbestos::request();
    $res = Asbestos::response();
    extract($data);
    require $view->getPath();
}

class View {

    private $_path;
    private $_data = array();

    public function __construct($path)
    {
        $this->_path = $path;
    }

    public function getPath() {
        return ASBESTOS_VIEWS_DIR . DIRECTORY_SEPARATOR . $this->_path . '.php';
    }

    public function bind($key, $value=null)
    {
        if (is_array($key)) {
            $this->_data = array_merge($this->_data, $key);
        } else {
            $this->_data[$key] = $value;
        }
        return $this;
    }

    public function render()
    {
        ob_start();
        __asbestos_view_render($this, $this->_data);
        Page::end();
        return ob_get_clean();
    }

}
