<?php

namespace Housekeeping;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Kernel
{
    /** @var Bool */
    private $debug;

    /** @var String[] */
    private $route_files;

    /** @var Callable */
    private $exception_handler;

    /**
        @param Bool $debug Enable debugging mode
     */
    public function __construct(Bool $debug, array $route_files, callable $exception_handler)
    {
        $this->debug = $debug;
        $this->route_files = $route_files;
        $this->$exception_handler = $exception_handler;
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
        // Load routes
        foreach ($this->route_files as $route_file) {
            include($route_file);
        }

        // Start routing
        try {
            $request = Request::createFromGlobals();
            // Match route
            // Get response
        } catch (Exception $e) {
            $exception_handler = $this->exception_handler;
            $exception_handler($e);
        }
    }
}
