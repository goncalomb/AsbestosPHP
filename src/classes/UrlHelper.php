<?php

namespace Asbestos;

final class UrlHelper
{
    public static function baseUrl()
    {
        return Request::scheme() . '://' . Request::host();
    }

    public static function makeUrl($path)
    {
        return self::baseUrl() . $path;
    }

    private function __construct()
    {
    }
}
