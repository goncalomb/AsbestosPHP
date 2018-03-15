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

namespace Asbestos\Html;

/**
 * A simplified HTML element.
 */
class Element
{
    /**
     * The tag name.
     *
     * @var string
     */
    private $_tag;

    /**
     * The contents.
     *
     * @var array
     */
    private $_html = array();

    /**
     * The attributes.
     *
     * @var array
     */
    private $_attributes = array();

    /**
     * Used flag to avoid element reuse.
     *
     * @var bool
     */
    private $_used = false;

    /**
     * Constructor.
     *
     * @param string $tag Tag name.
     */
    public function __construct($tag)
    {
        $this->_tag = $tag;
    }

    /**
     * Set/Unset element attribute.
     *
     * @param string $name Attribute name.
     * @param string|null $value Attribute value (null to remove).
     */
    public function attribute($name, $value=null)
    {
        if ($value === null) {
            unset($this->_attributes[$name]);
        } else {
            $this->_attributes[$name] = $value;
        }
    }

    /**
     * Append content or other elements.
     *
     * @param mixed Things to be appended.
     */
    public function append()
    {
        $args = func_get_args();
        foreach ($args as $arg) {
            if ($arg instanceof Element) {
                if ($arg->_used) {
                    continue;
                }
                $arg->_used = true;
            }
            $this->_html[] = $arg;
        }
    }

    /**
     * Clear contents.
     */
    public function clear()
    {
        $this->_html = array();
    }

    /**
     * Output opening HTML.
     */
    protected function outputOpeningTag()
    {
        echo '<', htmlspecialchars($this->_tag);
        foreach ($this->_attributes as $name => $value) {
            echo ' ', htmlspecialchars($name), '="', htmlspecialchars($value), '"';
        }
        echo '>';
        if ($this->_tag == 'html' || $this->_tag == 'head' || $this->_tag == 'body') {
            echo "\n";
        }
    }

    /**
     * Output inner HTML.
     */
    protected function outputContent()
    {
        foreach ($this->_html as $part) {
            if ($part instanceof Element) {
                $part->output();
            } else {
                echo $part;
            }
        }
    }

    /**
     * Output closing HTML.
     */
    protected function outputClosingTag()
    {
        echo '</', htmlspecialchars($this->_tag), '>';
        if ($this->_tag == 'html' || $this->_tag == 'head' || $this->_tag == 'body') {
            echo "\n";
        }
    }

    /**
     * Output outer HTML.
     */
    public function output()
    {
        $this->outputOpeningTag();
        $this->outputContent();
        $this->outputClosingTag();
    }
}
