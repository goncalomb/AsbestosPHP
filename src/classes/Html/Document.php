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
 * A simplified HTML5 document.
 */
class Document
{
    /**
     * The root html element.
     *
     * @var \Asbestos\Html\Element
     */
    private $_htmlElement;

    /**
     * The head element.
     *
     * @var \Asbestos\Html\HeadElement
     */
    private $_headElement;

    /**
     * The body element.
     *
     * @var \Asbestos\Html\BodyElement
     */
    private $_bodyElement;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->_htmlElement = new Element('html');
        $this->_headElement = new HeadElement();
        $this->_bodyElement = new BodyElement();
        $this->_htmlElement->append($this->_headElement);
        $this->_htmlElement->append($this->_bodyElement);
    }

    /**
     * Get head element.
     *
     * @return \Asbestos\Html\HeadElement
     */
    public function head()
    {
        return $this->_headElement;
    }

    /**
     * Get body element.
     *
     * @return \Asbestos\Html\BodyElement
     */
    public function body()
    {
        return $this->_bodyElement;
    }

    /**
     * Set meta tag.
     *
     * @param string $name Meta tag name.
     * @param string $content Meta tag content.
     */
    public function metaTag($name, $content)
    {
        $this->_headElement->metaTag($name, $content);
    }

    /**
     * Set Open Graph tags.
     *
     * @param array $data Tag names and values.
     * @param bool $merge Merge with current tags.
     * @param string $prefix Tag name prefix.
     */
    public function ogTags($data, $merge=true, $prefix='og')
    {
        $this->_headElement->ogTags($data, $merge, $prefix);
    }

    /**
     * Add stylesheet file.
     *
     * @param string $href The stylesheet location.
     */
    public function stylesheetFile($href)
    {
        $this->_headElement->stylesheetFile($href);
    }

    /**
     * Add script file.
     *
     * @param string $src The script location.
     * @param bool $end Add script to end of body instead of head.
     */
    public function scriptFile($src, $end=false)
    {
        if ($end) {
            $this->_bodyElement->scriptFile($src);
        } else {
            $this->_headElement->scriptFile($src);
        }
    }

    /**
     * Set document title.
     *
     * @param string $title The document title.
     */
    public function title($title)
    {
        $this->_headElement->title($title);
    }

    /**
     * Output document with HTML5 DOCTYPE.
     */
    public function output()
    {
        echo '<!DOCTYPE html>', "\n";
        $this->_htmlElement->output();
    }
}
