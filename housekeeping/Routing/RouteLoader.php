<?php

namespace Housekeeping\Routing;

class RouteLoader
{

    private static function routes()
    {
        return [
            'MainRoutes.php'
        ];
    }

    public static function loadRoutes($routes_dir)
    {
        $routes = self::routes();
        foreach ($routes as $route) {
            include $routes_dir . $route;
        }
    }
};
