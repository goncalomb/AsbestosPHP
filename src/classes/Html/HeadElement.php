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
 * A simplified HTML head element.
 */
class HeadElement extends Element
{
    /**
     * Meta tags.
     *
     * @var array
     */
    private $_metatags = array();

    /**
     * Open Graph tag data.
     *
     * @var array
     */
    private $_ogtags = array();

    /**
     * Stylesheet file locations.
     *
     * @var array
     */
    private $_styles = array();

    /**
     * Script file locations.
     *
     * @var array
     */
    private $_scripts = array();

    /**
     * The document title.
     *
     * @var string
     */
    private $_title = 'A Page';

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct('head');
    }

    /**
     * Set meta tag.
     *
     * @param string $name Meta tag name.
     * @param string $content Meta tag content.
     */
    public function metaTag($name, $content)
    {
        $this->_metatags[$name] = $content;
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
        if ($merge && isset($this->_ogtags[$prefix])) {
            $this->_ogtags[$prefix] = array_merge($this->_ogtags[$prefix], $data);
        } else {
            $this->_ogtags[$prefix] = $data;
        }
    }

    /**
     * Add stylesheet file.
     *
     * @param string $href The stylesheet location.
     */
    public function stylesheetFile($href)
    {
        $this->_styles[] = $href;
    }

    /**
     * Add script file.
     *
     * @param string $src The script location.
     */
    public function scriptFile($src)
    {
        $this->_scripts[] = $src;
    }

    /**
     * Set document title.
     *
     * @param string $title The document title.
     */
    public function title($title)
    {
        $this->_title = $title;
    }

    /**
     * {@inheritdoc}
     */
    public function output()
    {
        $this->outputOpeningTag();
        echo '<meta charset="utf-8">', "\n";
        foreach ($this->_metatags as $name => $content) {
            echo '<meta name="', htmlspecialchars($name), '" content="', htmlspecialchars($content), "\">\n";
        }
        foreach ($this->_ogtags as $prefix => $data) {
            foreach ($data as $property => $content) {
                echo '<meta property="', htmlspecialchars($prefix . ':' . $property), '" content="', htmlspecialchars($content), "\">\n";
            }
        }
        foreach ($this->_styles as $href) {
            echo '<link rel="stylesheet" type="text/css" href="', htmlspecialchars($href), "\">\n";
        }
        foreach ($this->_scripts as $src) {
            echo '<script type="text/javascript" src="', htmlspecialchars($src), "\"></script>\n";
        }
        if ($this->_title) {
            echo '<title>', htmlspecialchars($this->_title), "</title>\n";
        }
        $this->outputContent();
        $this->outputClosingTag();
    }
}
