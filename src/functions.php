<?php

namespace Asbestos;

function safe_require($file)
{
    if (is_file($file)) {
        return require $file;
    }
}

function load_class($name, $path=ASBESTOS_CLASSES_DIR)
{
    safe_require($path . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $name) . '.php');
}
