<?php
// Autoload classes
include __DIR__ . './../vendor/autoload.php';

use Housekeeping\Kernel;
use Symfony\Component\HttpFoundation\JsonResponse;

$kernel = new Kernel(
    /** Debug mode */
    true,
    /** Error handler */
    function (Exception $exception) {
        return new JsonResponse(['error' => true, 'message' => $exception->getMessage(), 'code' => $exception->getCode(), 'trace' => $exception->getTraceAsString()]);
    }
);

$kernel->boot();
