<?php

namespace Housekeeping;

use Exception;
use Housekeeping\Routing\Router;
use Symfony\Component\HttpFoundation\Request;

class Kernel
{
    /** @var Bool */
    private $debug;

    /** @var Router */
    private $router;

    /** @var Callable */
    private $exception_handler;

    /**
        @param Bool $debug Enable debugging mode
     */
    public function __construct(Bool $debug, Router $router, callable $exception_handler)
    {
        $this->debug = $debug;
        $this->router = $router;
        $this->exception_handler = $exception_handler;
    }

    /**
        Load routes and start routing requests
     */
    public function boot()
    {
        if ($this->debug) {
            error_reporting(E_ALL);
            ini_set('display_errors', true);
        }
        // Start routing
        try {
            $request = Request::createFromGlobals();
            // Match route
            $route = $this->router->match($request);
            // Get response
            $response = $route->run($request);
            return $response;
        } catch (Exception $e) {
            $exception_handler = $this->exception_handler;
            return $exception_handler($e);
        }
        return null;
    }
}
