<?php

require './asbestos/core.php';
use \Asbestos\Asbestos;

header('Content-Type: text/plain; charset=utf-8');
echo "Welcome to AsbestosPHP!\n";
echo Asbestos::executionTime(), "\n";

?>
