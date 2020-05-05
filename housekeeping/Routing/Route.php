<?php

namespace Housekeeping\Routing;

use Housekeeping\Controller\Interfaces\ValidatableController;
use Housekeeping\Routing\Middleware\Middleware;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Route
{
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
     *  @return Route[]
     */
    public static function getRoutes()
    {
        return self::$routes;
    }

    private String $method;
    private String $path;

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
            throw new \Exception('Middleware not found'); // TODO: Custom exception
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
        $this->lastMiddleware->setNextMiddleware(function (Request $request) {
            $function = $this->function;
            $response = null;
            if (is_callable($function)) {
                $response = $function($request);
            } else {
                // TODO: Handle errors parsing
                $parts = explode('@', $function);
                $controller_class = $parts[0];
                $method = $parts[1];
                $controller = new $controller_class();
                // Validation
                if ($controller instanceof ValidatableController) {
                    $v = 'validateRequest';
                    /** @var Validation */
                    $validation = $controller->$v($request);
                    if ($validation->fails()) {
                        return new JsonResponse(['error' => true, 'messages' => $validation->errors()->all()], 400);
                    }
                }
                $response = $controller->$method($request);
            }
            if (is_string($response)) {
                $response = new Response($response, 200);
            } else if (is_array($response)) {
                $response = new JsonResponse($response);
            } else if ($response instanceof Response) {
                // Cool
            } else {
                throw new \Exception("Response type not supported"); // TODO Custom exception
            }
            return $response;
        });
        // Run middleware
        $middleware = $this->firstMiddleware;
        return $middleware($request);
    }
}
