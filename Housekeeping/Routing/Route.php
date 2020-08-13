<?php

namespace Housekeeping\Routing;

use Exception;
use Housekeeping\Routing\Middleware\Middleware;
use InvalidArgumentException;
use JsonSerializable;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Route
{
    /** @var String */
    private $method;

    /** @var String */
    private $path;

    /** @var Callable|String */
    private $function;

    /** @var Middleware */
    private $firstMiddleware;

    /** @var Middleware */
    private $lastMiddleware;

    /**
     * @param String $method
     * @param String $path
     * @param Callable|String $function
     */
    public function __construct(String $method, String $path, $function)
    {
        $this->method = $method;
        $this->path = $path;
        $this->function = $function;
        $this->firstMiddleware = $this->lastMiddleware = new Middleware();
    }

    /**
     *  @return Route[]
     */
    public static function getRoutes()
    {
        return self::$routes;
    }

    public function getPath(): String
    {
        return $this->path;
    }

    public function getMethod(): String
    {
        return $this->method;
    }

    /**
     * @return Callable|String
     */
    public function getFunction()
    {
        return $this->function;
    }

    /**
     * @return Route
     */
    public function middleware(string $middlewareClass)
    {
        if (!class_exists($middlewareClass)) {
            throw new Exception('Middleware not found');
        }
        $middleware = new $middlewareClass;
        $this->lastMiddleware->setNextMiddleware($middleware);
        $this->lastMiddleware = $middleware;
        return $this;
    }

    /**
     * @return Response
     */
    public function run(Request $request): Response
    {
        // Link the last middleware to the function of the route
        $this->lastMiddleware->setNextMiddleware(function (Request $request) {
            $function = $this->function;
            $response = null;

            // Attempt calling the function
            if (is_callable($function)) {
                $response = $function($request);
            } else {
                // Laravel style class method call
                $parts = explode('@', $function);
                if (count($parts) !== 2) {
                    throw new InvalidArgumentException("Route class method argument '{$this->function}' could not be parsed");
                }
                $controller_class = $parts[0];
                $method = $parts[1];
                if (!class_exists($controller_class)) {
                    throw new InvalidArgumentException("Route class method argument '{$this->function}': Class {$controller_class} Couldn't be found");
                }
                $controller = new $controller_class();
                if (!method_exists($controller, $method)) {
                    throw new InvalidArgumentException("Route class method argument '{$this->function}': Method {$method} Couldn't be found");
                }
                // Run method
                $response = $controller->$method($request);
            }

            // Attempt normalizing response if not of the correct type
            if (!$response instanceof Response) {
                // Is serializable to JSON
                if (is_array($response) || is_object($response) && $response instanceof JsonSerializable) {
                    $response = new JsonResponse($response);
                }
                // Is a string, scalar or an object which implements to string
                else if (is_string($response) || is_scalar($response) || (is_object($response) && method_exists($response, '__toString'))) {
                    $response = new Response($response, 200);
                }
                // Is an empty response
                else if ($response === null) {
                    return new Response(null, 204);
                }
                // Couldn't convert it
                else {
                    throw new Exception('Response type not supported');
                }
            }
            return $response;
        });
        // Run first middleware
        $middleware = $this->firstMiddleware;
        return $middleware($request);
    }

    // Static methods for adding routes easily

    /** @var Route[] All routes registered in the system */
    private static $routes = [];

    /**
     * @param String $path
     * @param Callable|String $function
     * @return Route
     */
    public static function get(String $path, $function): Route
    {
        return self::$routes[] = new Route('GET', $path, $function);
    }

    /**
     * @param String $path
     * @param Callable|String $function
     * @return Route
     */
    public static function post(String $path, $function): Route
    {
        return self::$routes[] = new Route('POST', $path, $function);
    }

    /**
     * @param String $path
     * @param Callable|String $function
     * @return Route
     */
    public static function put(String $path, $function): Route
    {
        return self::$routes[] = new Route('PUT', $path, $function);
    }

    /**
     * @param String $path
     * @param Callable|String $function
     * @return Route
     */
    public static function delete(String $path, $function): Route
    {
        return self::$routes[] = new Route('DELETE', $path, $function);
    }

    /**
     * @param String $path
     * @param Callable|String $function
     * @return Route
     */
    public static function patch(String $path, $function): Route
    {
        return self::$routes[] = new Route('PATCH', $path, $function);
    }
}
