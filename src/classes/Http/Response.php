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
 * A HTTP response.
 */
final class Response
{
    use HeaderContainer;

    /**
     * Content type map.
     *
     * @var array
     */
    private static $_types = [
        'html' => 'text/html; charset=utf-8',
        'plain' => 'text/plain; charset=utf-8',
        'json' => 'application/json; charset=utf-8',
    ];

    /**
     * Response status code.
     *
     * @var int
     */
    private $_statusCode = 200;

    /**
     * Response content.
     *
     * @var string
     */
    private $_content = null;

    /**
     * Set the response status code.
     *
     * @param int $code Response status code.
     */
    public function setStatusCode($code)
    {
        $this->_statusCode = $code;
    }

    /**
     * Set the response content (converts arrays to JSON).
     *
     * @param string|array $content Response content.
     */
    public function setContent($content)
    {
        if (is_array($content)) {
            $this->setContentType('json');
            $this->_content = json_encode($content, JSON_PRETTY_PRINT);
        } else {
            $this->_content = (string) $content;
        }
    }

    /**
     * Set the response content type header and status code.
     *
     * @param string $type Response content type.
     * @param int $statusCode Response status code.
     */
    public function setContentType($type, $statusCode=null)
    {
        if (isset(static::$_types[$type])) {
            $type = static::$_types[$type];
        }
        $this->setHeader('Content-Type', $type);
        if ($statusCode) {
            $this->_statusCode = $statusCode;
        }
    }

    /**
     * Send the response using the normal PHP functions.
     */
    public function send()
    {
        if (headers_sent()) {
            return false;
        }
        // set headers
        foreach ($this->getHeaders() as $h) {
            header($h[0] . ': ' . $h[1], false, $this->_statusCode);
        }
        // set status code
        if (function_exists('http_response_code')) {
            http_response_code($this->_statusCode);
        }
        // send content
        echo $this->_content;
        return true;
    }
}
