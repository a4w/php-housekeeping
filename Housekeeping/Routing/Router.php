<?php

namespace Housekeeping\Routing;

use Exception;
use Symfony\Component\HttpFoundation\Request;

class Router
{
    /** @var Route[] */
    private $routes;

    /**
     * Add a GET route
     * @param String $path
     * @param Callable|String $function
     * @return Route
     */
    public function get(String $path, $function): Route
    {
        return $this->routes[] = new Route('GET', $path, $function);
    }

    /**
     * Add a POST route
     * @param String $path
     * @param Callable|String $function
     * @return Route
     */
    public function post(String $path, $function): Route
    {
        return $this->routes[] = new Route('POST', $path, $function);
    }

    /**
     * Add a PUT route
     * @param String $path
     * @param Callable|String $function
     * @return Route
     */
    public function put(String $path, $function): Route
    {
        return $this->routes[] = new Route('PUT', $path, $function);
    }

    /**
     * Add a DELETE route
     * @param String $path
     * @param Callable|String $function
     * @return Route
     */
    public function delete(String $path, $function): Route
    {
        return $this->routes[] = new Route('DELETE', $path, $function);
    }

    /**
     * Add a PATCH route
     * @param String $path
     * @param Callable|String $function
     * @return Route
     */
    public function patch(String $path, $function): Route
    {
        return $this->routes[] = new Route('PATCH', $path, $function);
    }


    /**
        Match request with registered routes
        @param Request $request The request object to match against routes
        @return Route
        @throws Exception
     */
    public function match(Request $request): Route
    {
        // Path
        $path = rtrim($request->getPathInfo(), '/');
        $method = $request->getMethod();
        // Start route matching
        $routes = $this->routes;
        // Attempt matching one by one
        $matched = null;
        foreach ($routes as $route) {
            $route_path = rtrim($route->getPath(), '/');
            // Parse path parameters
            $route_path = preg_quote($route_path, '/');
            $regex = '/^' . preg_replace('/\\\{([^\/]+)\\\}/', '([^\/]+)', $route_path) . '\/?$/';

            if (preg_match($regex, $path) && $route->getMethod() === $method) {
                // Match
                // Fill variables
                preg_match_all('/\\\{([^\/]+)\\\}/', $route_path, $variables_match);
                preg_match_all($regex, $path, $url_match);
                foreach ($variables_match[1] as $i => $tag) {
                    $request->attributes->set($tag, urldecode($url_match[$i + 1][0]));
                }
                $matched = $route;
                break;
            }
        }
        if ($matched === null) {
            throw new Exception('Route not found');
        }
        return $matched;
    }
}
