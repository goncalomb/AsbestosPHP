<?php

namespace Asbestos;

final class Asbestos
{
    private static $_request = null;
    private static $_response = null;

    private static $_htmlErrorNames = array(
        400 => 'Bad Request',
        403 => 'Forbidden',
        404 => 'Not Found',
        500 => 'Internal Server Error',
        503 => 'Service Temporarily Unavailable'
    );

    private static $_routing = false;

    public static function start()
    {
        if (PHP_SAPI == 'cli') {
            return;
        }
        self::$_request = Http\Request::fromGlobals();
        self::$_response = new Http\Response();
        register_shutdown_function(array(__CLASS__, 'end'));
    }

    public static function request()
    {
        return self::$_request;
    }

    public static function response()
    {
        return self::$_response;
    }

    public static function end()
    {
        if (PHP_SAPI == 'cli' || !self::$_response) {
            return;
        }
        self::$_response->send();
        Page::end();
    }

    private static function loadTheme($title=null)
    {
        $theme_file = ASBESTOS_THEME_DIR . DIRECTORY_SEPARATOR . 'theme.php';
        if (!is_file($theme_file)) {
            $theme_file = ASBESTOS_CONTENT_DIR . DIRECTORY_SEPARATOR . 'theme.php';
        }
        if (is_file($theme_file)) {
            $page = Page::start();
            Page::setMetadata($title);
            safe_require($theme_file);
            return $page;
        }
        return null;
    }

    public static function startRouting($index_fallback=false)
    {
        self::$_routing = true;

        if (isset($_SERVER['REDIRECT_STATUS']) && $_SERVER['REDIRECT_STATUS'] != 200 && $_SERVER['REDIRECT_STATUS'] != 404) {
            self::triggerHttpError($_SERVER['REDIRECT_STATUS']);
        }
        self::$_response->setContentType('html', 200);

        if (Config::get('routing.robots-txt.enable', false)) {
            Routing\Router::match('GET', '/robots\.txt', function () {
                self::$_response->setContentType('plain', 200);
                echo "User-agent: *\n";
                if (Config::get('routing.robots-txt.disallow', false)) {
                    echo "Disallow: /\n";
                } else {
                    echo "Disallow:\n";
                }
            });
        }
        safe_require(ASBESTOS_CONTENT_DIR . DIRECTORY_SEPARATOR . 'routes.php');
        if (Routing\Router::run(self::$_request->getMethod(), self::$_request->getPath())) {
            exit();
        } elseif (!$index_fallback || self::$_request->getPath() != '/') {
            self::triggerHttpError(404);
        }
    }

    public static function startThemedPage($title=null)
    {
        if (!self::$_routing && isset($_SERVER['REDIRECT_STATUS']) && $_SERVER['REDIRECT_STATUS'] != 200) {
            self::triggerHttpError($_SERVER['REDIRECT_STATUS']);
        }
        self::$_response->setContentType('html', 200);

        $page = self::loadTheme($title);
        if (!$page) {
            $page = Page::start();
        }
        return $page;
    }

    public static function triggerHttpError($error_code, $error_name=null)
    {
        /*
        // TODO: move error handling code to new class
        if (!$error_name) {
            $error_name = (isset(self::$_htmlErrorNames[$error_code]) ? self::$_htmlErrorNames[$error_code] : 'Unknown Error');
        }
        Response::contentType('html', $error_code);
        if (self::loadTheme("{$error_code} {$error_name}")) {
            $error_callback = Config::get('site.onerror');
            if (is_callable($error_callback)) {
                call_user_func($error_callback, $error_code, $error_name);
            } else {
                echo '<p style="color: crimson;">', $error_code, ' ', $error_name, '</p>';
            }
        } else {
            Response::contentType('plain', $error_code);
            echo "{$error_code} {$error_name}\n";
        }
        exit();
        */
    }

    public static function executionTime($asFloat=false)
    {
        if ($asFloat) {
            return microtime(true) - ASBESTOS_MICROTIME;
        } else {
            return floor((microtime(true) - ASBESTOS_MICROTIME)*100000)/100 . 'ms';
        }
    }

    private function __construct()
    {
    }
}
