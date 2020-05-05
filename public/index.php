<?php
// Autoload classes
include __DIR__ . './../vendor/autoload.php';

use Housekeeping\Kernel;

$kernel = new Kernel(__DIR__ . '/../', true);
$kernel->boot();

$kernel->handleRequest();

