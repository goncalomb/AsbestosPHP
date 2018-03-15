<?php

namespace Asbestos;

final class Response
{
    private static $_types = [
        'html' => 'text/html; charset=utf-8',
        'plain' => 'text/plain; charset=utf-8',
        'json' => 'application/json; charset=utf-8',
    ];

    public static function contentType($type, $response_code=null)
    {
        if (isset(static::$_types[$type])) {
            $type = static::$_types[$type];
        }
        if ($response_code) {
            header("Content-Type: {$type}", true, $response_code);
            if (function_exists('http_response_code')) {
                http_response_code($response_code);
            }
        } else {
            header("Content-Type: {$type}", true);
        }
    }

    private function __construct()
    {
    }
}
