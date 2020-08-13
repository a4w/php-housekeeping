<?php
// Autoload classes
include __DIR__ . './../vendor/autoload.php';

use Housekeeping\Kernel;
use Housekeeping\Routing\Router;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

$router = new Router();

$router->get('/hello', function (Request $request) {
    return "Hello world!";
});

$kernel = new Kernel(true, $router, function (Exception $exception) {
    return new JsonResponse(['error' => true, 'message' => $exception->getMessage(), 'code' => $exception->getCode(), 'trace' => $exception->getTraceAsString()]);
});

$kernel->boot();
