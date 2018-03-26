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

namespace Asbestos;

/**
 * A static container for a HTML page.
 *
 * It uses page zones (HTML elements) to capture the output and build the page.
 */
final class Page
{
    /**
     * The page instance.
     *
     * @var \Asbestos\Html\Document
     */
    private static $_page;

    /**
     * Page zones.
     *
     * @var array
     */
    private static $_zones = array();

    /**
     * Page zone stack.
     *
     * @var array
     */
    private static $_zoneStack = array();

    /**
     * Current output zone.
     *
     * @var string
     */
    private static $_outputZone = 'body';

    /**
     * Initialize page.
     *
     * @return \Asbestos\Html\Document
     */
    public static function start()
    {
        if (self::$_page) {
            return null;
        }
        self::$_page = new Html\Document();
        self::$_zones['head'] = self::$_page->head();
        self::$_zones['body'] = self::$_page->body();
        ob_start();
        return self::$_page;
    }

    /**
     * Create a new page zone.
     *
     * @param string $zoneName Zone name.
     * @param string $tag Tag name (defaults to div).
     * @return \Asbestos\Html\Element
     */
    public static function createZone($zoneName, $tag='div')
    {
        if (self::$_page && !isset(self::$_zones[$zoneName])) {
            $element = new Html\Element($tag);
            self::$_zones[$zoneName] = $element;
            self::append(self::$_outputZone, $element);
            return $element;
        }
        return null;
    }

    /**
     * Get zone element.
     *
     * @param string $zoneName Zone name (defaults to current zone).
     * @return \Asbestos\Html\Element
     */
    public static function getZone($zoneName=null)
    {
        if ($zoneName === null) {
            $zoneName = self::$_outputZone;
        }
        return (isset(self::$_zones[$zoneName]) ? self::$_zones[$zoneName] : null);
    }

    /**
     * Get current zone name
     *
     * @param string $zoneName Zone name (defaults to current zone).
     * @return string
     */
    public static function getZoneName()
    {
        return (self::$_page ? self::$_outputZone : null);
    }

    /**
     * Set the current output zone (pushes last to stack).
     *
     * @param string $zoneName Zone name.
     * @return boolean
     */
    public static function startZone($zoneName)
    {
        if (isset(self::$_zones[$zoneName])) {
            self::flushBuffer();
            self::$_zoneStack[] = self::$_outputZone;
            self::$_outputZone = $zoneName;
            return true;
        }
        return false;
    }

    /**
     * Resets current output zone (removes last from stack).
     *
     * @return boolean
     */
    public static function endZone()
    {
        $zoneName = array_pop(self::$_zoneStack);
        if ($zoneName !== null && isset(self::$_zones[$zoneName])) {
            self::flushBuffer();
            self::$_outputZone = $zoneName;
            return true;
        }
        return false;
    }

    /**
     * Append data to to zone.
     *
     * @param string $zoneName Zone name.
     * @param mixed $data Data to be appended.
     * @return boolean
     */
    public static function append($zoneName, ...$data)
    {
        if (isset(self::$_zones[$zoneName])) {
            self::flushBuffer();
            call_user_func_array(array(self::$_zones[$zoneName], 'append'), $data);
            return true;
        }
        return false;
    }

    /**
     * Flushes the output buffer to the current zone element.
     *
     * @return boolean
     */
    public static function flushBuffer()
    {
        if (self::$_page && ob_get_length()) {
            self::$_zones[self::$_outputZone]->append(ob_get_clean());
            ob_start();
            return true;
        }
        return false;
    }

    /**
     * Add stylesheet file.
     *
     * @param string $href The stylesheet location.
     */
    public static function stylesheetFile($href)
    {
        if (self::$_page) {
            self::$_page->stylesheetFile($href);
        }
    }

    /**
     * Set meta tag.
     *
     * @param string $name Meta tag name.
     * @param string $content Meta tag content.
     */
    public static function metaTag($name, $content)
    {
        if (self::$_page) {
            self::$_page->metaTag($name, $content);
        }
    }

    /**
     * Set Open Graph tags.
     *
     * @param array $data Tag names and values.
     * @param bool $merge Merge with current tags.
     * @param string $prefix Tag name prefix.
     */
    public static function ogTags($data, $merge=true, $prefix='og')
    {
        if (self::$_page) {
            self::$_page->ogTags($data, $merge, $prefix);
        }
    }

    /**
     * Add script file.
     *
     * @param string $src The script location.
     * @param bool $end Add script to end of body instead of head.
     */
    public static function scriptFile($src, $end=false)
    {
        if (self::$_page) {
            self::$_page->scriptFile($src, $end);
        }
    }

    /**
     * Set page title.
     *
     * @param string $title The page title.
     */
    public static function title($title)
    {
        if (self::$_page) {
            self::$_page->title($title);
        }
    }

    // TODO: move this to the Asbestos class?
    /**
     * Set page title and metadata using the config (see site.metadata config).
     *
     * @param string $title The document title.
     * @param array $data The metadata array.
     * @param bool $merge Merge with current tags.
     */
    public static function setMetadata($title=null, $data=[], $merge=true)
    {
        if (self::$_page) {
            // format the title
            $simple_title = $title;
            if ($title) {
                $title = str_replace('{}', $title, Config::get('site.title-format', '{}'));
            } else {
                $simple_title = $title = Config::get('site.title', '');
            }
            self::$_page->title($title);
            // merge data with the global configuration
            if ($merge) {
                if ($config_data = Config::get('site.metadata', [])) {
                    $data = array_merge($config_data, $data);
                }
            }
            // set basic meta tags
            foreach (['description', 'keywords', 'author'] as $name) {
                if (!empty($data[$name])) {
                    self::$_page->metaTag($name, $data[$name]);
                }
            }
            // set tags for Twitter Cards
            if (isset($data['twitter']) && is_array($data['twitter'])) {
                self::$_page->ogTags($data['twitter'], false, 'twitter');
            } else {
                self::$_page->ogTags([], false, 'twitter');
            }
            // set Open Graph tags
            if (isset($data['og']) && is_array($data['og'])) {
                $og_tags = [
                    'title' => $simple_title,
                    'url' => Asbestos::request()->getUrl()
                ];
                if (!empty($data['description'])) {
                    $og_tags['description'] = $data['description'];
                }
                self::$_page->ogTags(array_merge($og_tags, $data['og']), false);
            } else {
                self::$_page->ogTags([], false);
            }
        }
    }

    /**
     * Get page head element.
     *
     * @return \Asbestos\Html\HeadElement
     */
    public static function head()
    {
        return (self::$_page ? self::$_page->head() : null);
    }

    /**
     * Get page body element.
     *
     * @return \Asbestos\Html\BodyElement
     */
    public static function body()
    {
        return (self::$_page ? self::$_page->body() : null);
    }

    /**
     * Get page element.
     *
     * @return \Asbestos\Html\Element
     */
    public static function get()
    {
        return self::$_page;
    }

    /**
     * Finalize and output page.
     */
    public static function end()
    {
        if (!self::$_page || ErrorHandling::lastError()) {
            return;
        }
        self::$_zones[self::$_outputZone]->append(ob_get_clean());
        self::$_page->output();

        // reset class
        self::$_page = null;
        self::$_zones = array();
        self::$_zoneStack = array();
        self::$_outputZone = 'body';

        echo '<!-- ';
        echo '~', Asbestos::executionTime();
        echo " -->\n";
    }
}
