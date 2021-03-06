<?php

namespace Asbestos\Routing;

use \Asbestos\View\View;

class Route
{
    private $_methods;
    private $_pattern;
    private $_handler;

    public function __construct($methods, $pattern, $handler)
    {
        $this->_methods = $methods;
        $this->_pattern = $pattern;
        $this->_handler = $handler;
    }

    public function methods()
    {
        return $this->_methods;
    }

    public function matches($path)
    {
        if ($this->_pattern == '*') {
            return [$path];
        } elseif (preg_match('/^' . addcslashes($this->_pattern, '/') . '$/', $path, $matches)) {
            return array_slice($matches, 1);
        }
        return null;
    }

    public function invoke()
    {
        $ret = call_user_func_array($this->_handler, func_get_args());
        if ($ret) {
            if ($ret instanceof View) {
                echo $ret->render();
            } else {
                echo $ret;
            }
        }
    }
}
