<?php

namespace Asbestos\Routing;

class Router
{
    private static $_routes = array();

    public static function match($methods, $path, $handler)
    {
        self::$_routes[] = new Route(explode(',', $methods), $path, $handler);
    }

    public static function run($method, $path)
    {
        foreach (self::$_routes as $route) {
            if (in_array($method, $route->methods()) && strlen($path)) {
                $matches = $route->matches($path);
                if ($matches !== null) {
                    $route->invoke($matches);
                    return true;
                }
            }
        }
        return false;
    }

    public static function routes()
    {
        return self::$_routes;
    }

    private function __construct()
    {
    }
}
