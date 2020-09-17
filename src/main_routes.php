<?php

use Housekeeping\Routing\Router;

Router::get('/', function () {
    return "Hello world";
});

Router::get('/admin', function () {
    return "This is admin route";
});
