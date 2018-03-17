<?php
/**
 * Part of the AsbestosPHP framework.
 * https://github.com/goncalomb/asbestos
 *
 * Copyright (C) 2017-2018 Gonçalo Baltazar <me@goncalomb.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

namespace Asbestos\Http;

/**
 * A HTTP request.
 */
final class Request
{
    use HeaderContainer;

    /**
     * Is request secure?
     *
     * @var bool
     */
    private $_isSecure = false;

    /**
     * Request method.
     *
     * @var string
     */
    private $_method = 'GET';

    /**
     * Request URI.
     *
     * @var string
     */
    private $_uri = '/';

    /**
     * Request path.
     *
     * @var string
     */
    private $_path = '/';

    /**
     * Request query.
     *
     * @var string
     */
    private $_query = '';

    /**
     * Create request from PHP globals.
     */
    public static function fromGlobals()
    {
        $request = new self();

        foreach ([
            'Accept', 'Accept-Charset', 'Accept-Encoding', 'Accept-Language',
            'Connection', 'Host', 'Referer', 'User-Agent'
        ] as $name) {
            $key = 'HTTP_' . str_replace('-', '_', strtoupper($name));
            if (isset($_SERVER[$key])) {
                $request->setHeader($name, $_SERVER[$key], false);
            }
        }

        $request->_isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off');

        $request->_method = $_SERVER['REQUEST_METHOD'];
        $request->_uri = $_SERVER['REQUEST_URI'];

        $request->_path = strstr($_SERVER['REQUEST_URI'], '?', true);
        if ($request->_path == false) {
            $request->_path = $_SERVER['REQUEST_URI'];
        }
        $request->_query = strstr($_SERVER['REQUEST_URI'], '?');
        if ($request->_query == false) {
            $request->_query = '';
        }

        return $request;
    }

    /**
     * Constructor.
     */
    private function __construct()
    {
    }

    /**
     * Check if this request is secure.
     *
     * @return bool
     */
    public function isSecure()
    {
        return $this->_isSecure;
    }

    /**
     * Get the request scheme.
     *
     * @return string
     */
    public function getScheme()
    {
        return ($this->_isSecure ? 'https' : 'http');
    }

    /**
     * Get the request method.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->_method;
    }

    /**
     * Get the request URI.
     *
     * @return string
     */
    public function getUri()
    {
        return $this->_uri;
    }

    /**
     * Get the request host.
     *
     * @return string|null
     */
    public function getHost()
    {
        return $this->getHeader('Host');
    }

    /**
     * Get the request path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->_path;
    }

    /**
     * Get the request query.
     *
     * @return string
     */
    public function getQuery()
    {
        return $this->_query;
    }

    /**
     * Get the request URL.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->getScheme() . '://' . $this->getHost() . $this->getUri();
    }
}
