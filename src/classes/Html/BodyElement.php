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
 * A simplified HTML body element.
 */
class BodyElement extends Element
{
    /**
     * Script file locations.
     *
     * @var array
     */
    private $_scripts = array();

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct('body');
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
     * {@inheritdoc}
     */
    public function output()
    {
        $this->outputOpeningTag();
        $this->outputContent();
        foreach ($this->_scripts as $src) {
            echo '<script type="text/javascript" src="', htmlspecialchars($src), "\"></script>\n";
        }
        $this->outputClosingTag();
    }
}
