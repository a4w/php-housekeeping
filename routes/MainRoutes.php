<?php

use Housekeeping\Routing\Route;
use Symfony\Component\HttpFoundation\Request;

Route::get('/', function (Request $request) {
    return $request->query->all();
});

