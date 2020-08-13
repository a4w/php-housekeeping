<?php
// Autoload classes
include __DIR__ . './../vendor/autoload.php';

use Housekeeping\Kernel;
use Housekeeping\Routing\Router;
use Symfony\Component\HttpFoundation\JsonResponse;

$router = new Router();

$router->get('/', function () {
    return "Server is running...";
});

$router->get('/hello', function () {
    return "Hello world!";
});

$kernel = new Kernel(true, $router, function (Exception $exception) {
    return new JsonResponse(['error' => true, 'message' => $exception->getMessage(), 'code' => $exception->getCode(), 'trace' => $exception->getTraceAsString()]);
});

$kernel->boot();
