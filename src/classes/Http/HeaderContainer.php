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
 * A container for HTTP headers.
*/
trait HeaderContainer
{
    /**
     * The headers.
     *
     * @return array
     */
    private $_headers = [];

    /**
     * Set header.
     *
     * @param string $name The header name.
     * @param string $value The header value.
     * @param bool $replace Replace the current value or append.
     */
    public function setHeader($name, $value, $replace=true)
    {
        if ($replace) {
            $this->removeHeader($name);
        }
        $this->_headers[] = [$name, $value];
    }

    /**
     * Get header.
     *
     * @param string $name The header name.
     * @param bool $all Return all values or just the first.
     * @return string|array The header value.
     */
    public function getHeader($name, $all=false)
    {
        $nameLower = strtolower($name);
        if ($all) {
            $values = [];
            foreach ($this->_headers as &$h) {
                if ($h && strtolower($h[0]) == $nameLower) {
                    $values[] = $h[1];
                }
            }
            return $values;
        } else {
            foreach ($this->_headers as &$h) {
                if ($h && strtolower($h[0]) == $nameLower) {
                    return $h[1];
                }
            }
        }
        return null;
    }

    /**
     * Remove header.
     *
     * @param $name The header name.
     */
    public function removeHeader($name)
    {
        $nameLower = strtolower($name);
        foreach ($this->_headers as &$h) {
            if ($h && strtolower($h[0]) == $nameLower) {
                $h = null;
            }
        }
    }

    /**
     * Get all headers.
     *
     * @return array All header name and values.
     */
    public function getHeaders()
    {
        $this->_headers = array_values(array_filter($this->_headers));
        return $this->_headers;
    }

    /**
     * Clear all headers.
     */
    public function clearHeaders()
    {
        $this->_headers = [];
    }
}
