<?php

namespace Housekeeping\Routing;

use Symfony\Component\HttpFoundation\Request;

class Router
{

    public static function match(Request $request): Route
    {
        // Path
        $path = rtrim($request->getPathInfo(), '/');
        $method = $request->getMethod();
        // Start route matching
        $routes = Route::getRoutes();
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
        dump($matched);
        if ($matched === null) {
            throw new \Exception('Route not found: '); // TODO: Custom exception
        }
        return $matched;
    }
}
