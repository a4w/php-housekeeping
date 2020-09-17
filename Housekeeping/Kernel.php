<?php

namespace Housekeeping;

use Exception;
use Housekeeping\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Kernel
{
    /** @var Bool */
    private $debug;


    /** @var Callable */
    private $exception_handler;

    /**
        @param Bool $debug Enable debugging mode
     */
    public function __construct(Bool $debug, callable $exception_handler)
    {
        $this->debug = $debug;
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
            $route = Router::match($request);
            // Get response
            $response = $route->run($request);
            $response->send();
        } catch (Exception $e) {
            $exception_handler = $this->exception_handler;
            $response = $exception_handler($e);
            if ($response instanceof Response) {
                $response->send();
            }
        }
        return null;
    }
}
