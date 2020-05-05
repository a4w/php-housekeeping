<?php

use App\Middleware\AdminOnlyMiddleware;
use Housekeeping\Routing\Route;

Route::get('/', function () {
    return 'Welcome to Housekeeping!';
});

Route::get('/hello', 'App\Controller\WelcomeController@greet');
Route::get('/hello/admin', 'App\Controller\WelcomeController@greetAdmin')->middleware(AdminOnlyMiddleware::class);
Route::get('/toilet/{id}', 'App\Controller\ToiletController@findToilet');
Route::post('/toilet', 'App\Controller\ToiletController@addToilet');
Route::post('/toilet/update/{id}', 'App\Controller\ToiletController@updateToilet');
Route::post('/toilet/delete/{id}', 'App\Controller\ToiletController@deleteToilet');
Route::get('/toilets', 'App\Controller\ToiletController@getAll');

